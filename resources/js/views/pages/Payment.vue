<template>
    <div>
      <!-- Loading indicator -->
      <a-spin v-if="isLoading" class="flex justify-center items-center h-screen">
        <span>Loading...</span>
      </a-spin>

      <!-- Main content -->
      <a-row v-else class="h-screen">
        <a-col :span="12">
          <PackageView :package="packageDetails" />
        </a-col>
        <a-col :span="12" class="flex flex-col h-screen">
          <a-row :span="16" class="p-4">
            <PaymentLogin
              :errors="authFormErrorMessage"
              @form-change="handleFormChange"
            />
            <a-alert
              v-if="commonErrorMessage"
              type="error"
              message="Error"
              description="commonErrorMessage"
              show-icon
            />
          </a-row>
          <a-alert
            v-if="errorMessage"
            type="error"
            message="Error"
            description="errorMessage"
            show-icon
          />
          <a-row :span="8" class="p-4">
            <div class="container">
              <div id="payment-element" class="my-4 card-element"></div>
              <a-button
                type="primary"
                :loading="loading"
                class="btn btn-primary mx-auto w-400 mt-2"
                @click="handleSubmit"
              >
                <template v-if="loading" #icon>
                  <a-spin />
                </template>
                Pay
                <!-- <span v-else id="button-text" class="inline-block text-center">
                  Pay {{ packageTotalPrice }}
                </span> -->
              </a-button>
            </div>
          </a-row>
        </a-col>
      </a-row>
      <SuccessModal
        v-if="isSuccessModalOpen"
        :payment-intent="paymentIntentStatus"
        @close="handleSuccessModalClose"
      />
    </div>
  </template>

  <script setup>
  import { ref, onMounted, computed, nextTick, watch } from 'vue';
  import http from '@/services/axios.js';
  import { loadStripe } from '@stripe/stripe-js';
  import { useAuthStore } from '@/stores';
  import { useRouter } from 'vue-router';
  import PackageView from '@/components/package/PackageView.vue';
  import PaymentLogin from '@/components/auth/PaymentLogin.vue';
  import SuccessModal from '@/components/payment/PaymentSuccessModal.vue';
  import { message } from 'ant-design-vue';

  const store = useAuthStore();
  const router = useRouter();
  const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);

  const packageDetails = ref({});
  const sessionDetails = ref();
  const isLoading = ref(true);
  const loading = ref(false);
  const errorMessage = ref();
  const authFormErrorMessage = ref();
  const commonErrorMessage = ref();
  const form = ref({});
  const paymentElement = ref(null);
  const stripe = ref(null);
  const elements = ref(null);
  const isSuccessModalOpen = ref(false);
  const paymentIntentStatus = ref(null);
  const paymentToken = ref(null);

  const isAuthenticated = store.isAuthenticated;

  const props = defineProps({
    id: {
      type: Number,
      required: true,
    },
  });

  onMounted(async () => {
    await fetchPackageDetails();
    await getSession();
    isLoading.value = false;
  });

  watch(isLoading, async (newVal) => {
    if (!newVal) {
      await nextTick(); // Ensure the DOM is updated before initializing the payment element
      await initializePaymentElement();
      // checkStatus();
    }
  });

  const packageTotalPrice = computed(() => {
    return sessionDetails.value.amount / 100;
  });

  const handleFormChange = (updatedForm) => {
    form.value = updatedForm;
  };

  const fetchPackageDetails = async () => {
    try {
      const response = await http.get(`/api/package/${props.id}`);
      packageDetails.value = response.data;
    } catch (err) {
      console.log(err);
    }
  };

  const getSession = async () => {
    const response = await http.post(`/api/payment/get-session`, {
      productId: props.id,
    });
    if (response && response.data && response.data.id) {
      console.log(response);
      sessionDetails.value = response.data;
      paymentToken.value = response.data.metadata.payment_token;
    }
  };

  const updatePaymentStatus = async () => {
    const response = await http.post(`/api/payment/complete`, {
      payment_token: paymentToken.value,
      payment_Intent_Id: paymentIntentStatus.value.paymentIntent.id,
    });
    if (response) {
      console.log('success');
    }
  };

  const initializePaymentElement = async () => {
    stripe.value = await stripePromise;
    elements.value = stripe.value.elements({
      clientSecret: sessionDetails.value.client_secret,
    });

    const paymentElementOptions = {
      layout: 'tabs',
    };

    paymentElement.value = elements.value.create(
      'payment',
      paymentElementOptions,
    );
    paymentElement.value.mount('#payment-element');
  };

  const handleSubmit = async (e) => {
    loading.value = true;
    errorMessage.value = '';

    try {
      if (!isAuthenticated.value) {
        await authenticationFlow();
      }
      // if (!isAuthenticated.value) {
      //   return;
      // }

      if (!paymentElement.value) {
        throw new Error('Payment element is not mounted.');
      }

      const { error } = await stripe.value.confirmPayment({
        elements: elements.value,
        redirect: 'if_required',
      });
      if (error) {
        errorMessage.value = error.message;
        console.log(error.message);
      } else {
        checkStatus();
        console.log('Payment successful!');
      }
    } catch (e) {
      console.error(e);
      errorMessage.value = e.message;
    } finally {
      loading.value = false;
    }
  };

  const checkStatus = async () => {
    const clientSecret = sessionDetails.value.client_secret;

    if (!clientSecret) {
      return;
    }

    paymentIntentStatus.value = await stripe.value.retrievePaymentIntent(
      clientSecret,
    );
    isSuccessModalOpen.value = true;

    updatePaymentStatus();
  };

  const authenticationFlow = async () => {
    if (form.value.isRegisterForm) {
      register(form.value);
    } else {
      login(form.value);
    }
  };

  const register = async (registerForm) => {
    console.log('call redister');
    // try {
    const response = await store.dispatch('auth/register', registerForm);
    console.log(response);
    // } catch (e) {
    //   // console.log(e);
    //   console.log(e);
    //   handleError(e);
    //   // handleError(e.response);
    // }
  };

  const login = async (loginForm) => {
    try {
      const response = await store.dispatch('auth/login', loginForm);
      console.log(response);
    } catch (e) {
      handleError(e);
    }
  };

  const handleError = (response) => {
    console.log(response);
    if (response && response.status === 422) {
      console.log(response.data.errors);
      authFormErrorMessage.value = response.data.errors;
    }
    if (response && response.status === 401) {
      commonErrorMessage.value = 'Invalid Email or Password';
    }
  };

  const handleSuccessModalClose = () => {
    isSuccessModalOpen.value = false;
    loading.value = true;
    router.push({ name: 'user-dashboard' });
  };
  </script>

  <style scoped>
  .card-element {
    margin: auto;
    width: 400px;
  }

  .error {
    color: red;
    text-align: center;
    margin: auto;
  }

  .btn {
    @apply bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded;
  }

  #payment-element {
    margin: auto;
    width: 400px;
  }

  #spinner {
    @apply inline-block;
  }

  #button-text {
    @apply inline-block text-center;
  }
  </style>
