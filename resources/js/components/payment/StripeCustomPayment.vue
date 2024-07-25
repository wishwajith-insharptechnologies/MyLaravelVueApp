<template>
  <div v-if="isLoading" class="flex justify-center items-center h-screen">
    <p>Loading...</p>
  </div>
  <div v-else class="payment-form">
    <h2>Stripe Payment</h2>
    <div id="payment-element" class="payment-element"></div>
    <button :disabled="loading" @click="handleSubmit">
      {{ loading ? 'Processing...' : 'Pay now' }}
    </button>
    <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { loadStripe } from '@stripe/stripe-js';

const stripePromise = loadStripe('YOUR_PUBLISHABLE_KEY');
const isLoading = ref(true);
const loading = ref(false);
const errorMessage = ref('');
const clientSecret = ref('');
const paymentElement = ref(null);

const fetchPaymentIntent = async () => {
  try {
    const response = await fetch(
      'http://localhost:3000/create-payment-intent',
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ amount: 1000, currency: 'usd' }),
      },
    );
    const { clientSecret: secret } = await response.json();
    clientSecret.value = secret;
  } catch (error) {
    console.error('Error fetching payment intent:', error);
  }
};

const handleSubmit = async () => {
  loading.value = true;
  errorMessage.value = '';

  try {
    const stripe = await stripePromise;
    const { error } = await stripe.confirmPayment({
      elements: paymentElement.value,
      confirmParams: {
        return_url: 'http://localhost:8080/success',
      },
    });

    if (error) {
      errorMessage.value = error.message;
      loading.value = false;
      return;
    }
  } catch (error) {
    errorMessage.value = 'An error occurred during payment.';
    console.error('Payment error:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  try {
    await fetchPaymentIntent();
    const stripe = await stripePromise;
    const elements = stripe.elements();
    const paymentElementOptions = {
      layout: 'tabs',
    };
    paymentElement.value = elements.create('payment', paymentElementOptions);
    paymentElement.value.mount('#payment-element');
  } catch (error) {
    console.error('Error during setup:', error);
  } finally {
    isLoading.value = false;
  }
});
</script>

<style scoped>
.payment-element {
  margin-bottom: 20px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.error {
  color: red;
}
</style>
