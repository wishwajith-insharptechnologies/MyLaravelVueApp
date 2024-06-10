<template>
    <div class="container">
      <a-card class="ml-5 mr-20 bg-white max-w-mx dark:bg-slate-800 dark:text-gray-200">
        <!-- Title -->
        <h2 class="mb-4 text-lg font-bold">{{ product ? 'Edit' : 'Create' }} Product</h2>

        <!-- Error messages -->
        <Errors
          v-if="errors"
          :content="errors"
          :errors="errors"
          container-class="mb-5 w-100"
          type="error"
          @close="errors = null"
        />

        <!-- Form -->
        <a-form @submit.prevent="submit">
          <!-- project_name -->
          <a-form-item>
            <a-input
              id="projectName"
              v-model="form.projectName"
              placeholder="Project Name"
              :class="errors && errors.project_name ? 'ant-input-status-error' : ''"
            />
          </a-form-item>

          <!-- environment_type -->
          <a-form-item>
            <a-select
              id="environmentType"
              v-model="form.environmentType"
              placeholder="Select Environment Type"
              :class="errors && errors.environment_type ? 'ant-input-status-error' : ''"
            >
              <a-select-option value="" disabled>Select Environment Type</a-select-option>
              <a-select-option
                v-for="typeItem in environmentTypes"
                :key="typeItem.value"
                :value="typeItem.value"
              >
                {{ typeItem.name }}
              </a-select-option>
            </a-select>
          </a-form-item>

          <!-- project_type -->
          <a-form-item>
            <a-select
              id="projectType"
              v-model="form.projectType"
              placeholder="Select Project Type"
              :class="errors && errors.project_type ? 'ant-input-status-error' : ''"
            >
              <a-select-option value="" disabled>Select Project Type</a-select-option>
              <a-select-option
                v-for="typeItem in projectTypes"
                :key="typeItem.id"
                :value="typeItem.id"
              >
                {{ typeItem.name }}
              </a-select-option>
            </a-select>
          </a-form-item>

          <!-- project_description -->
          <a-form-item>
            <a-textarea
              id="projectDescription"
              v-model="form.description"
              placeholder="Project Description"
              rows="4"
              :class="errors && errors.description ? 'ant-input-status-error' : ''"
            />
          </a-form-item>

          <!-- project_image -->
          <a-form-item>
            <a-upload
              accept="image/*"
              custom-request="onProjectImageFileChange"
              :class="errors && errors.project_image ? 'ant-input-status-error' : ''"
            >
              <a-button icon="upload">Click to Upload</a-button>
            </a-upload>
          </a-form-item>

          <!-- project_secret_code -->
          <a-form-item>
            <a-input
              id="secretCode"
              v-model="form.secretCode"
              placeholder="Secret Code"
              :class="errors && errors.secret_code ? 'ant-input-status-error' : ''"
            />
          </a-form-item>

          <!-- product_link -->
          <a-form-item>
            <a-input
              id="productLink"
              v-model="form.link"
              placeholder="Product Link"
              :class="errors && errors.link ? 'ant-input-status-error' : ''"
            />
          </a-form-item>

          <!-- import limitations -->
          <ImprotLimitations
            :stored-limitation-data="storedLimitations"
            @limitationDataChanged="importLimitation"
          />

          <!-- Submit and Cancel Buttons -->
          <a-form-item>
            <a-button type="primary" html-type="submit" :loading="submitting">
              {{ product ? 'Update' : 'Submit' }}
            </a-button>
          </a-form-item>
        </a-form>
      </a-card>
    </div>
  </template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores';
import { UserCircleIcon } from '@heroicons/vue/24/outline';
import clonedeep from 'lodash.clonedeep';
import moment from 'moment';
// import Multiselect from '@vueform/multiselect';
// import Errors from '@components/Errors.vue';
import ImprotLimitations from "@/components/product/ImprotLimitations.vue";

// const { popToast } = mapActions('toast', ['popToast']);
const store = useAuthStore();
// Define the component props
const props = defineProps({
  product: { type: Object, default: null },
});

// Define reactive variables
const projectTypes = [
  { id: 1, name: 'web' },
  { id: 2, name: 'mobile' },
  { id: 3, name: 'SASS' },
  { id: 4, name: 'solution' },
];
const environmentTypes = [
  { value: 0, name: 'Web Base' },
  { value: 1, name: 'Stand 0lone' },
];
const improtsConfigTypes = [
  { id: 1, name: 'Json' },
  { id: 2, name: 'Web' },
];
const form = ref({
  id: null,
  image: '',
  link: '',
  projectName: '',
  description: '',
  secretCode: '',
  projectType: '',
  environmentType: '',
  improtsConfigType: improtsConfigTypes[0].id,
  improtsConfigLink: '',
  improtsConfigJsonFile: '',
  limitation: [],
});

const submitting = ref(false);
const errors = ref(null);
const changed = ref(false);
const ready = ref(false);
const items = ref([{ name: '', value: '' }]);

const isEditable = computed(() => {
  return props.product !== null;
});

const storedLimitations = computed(() => {
  if (props.product && props.product.limitation) {
    return props.product.limitation.limitation;
  } else {
    return null;
  }
});

onMounted(() => {
  if (props.product) {
    const editableProduct = {
      ...props.product,
      limitation: storedLimitations.value,
    };
    form.value = clonedeep(editableProduct);
    setTimeout(() => {
      ready.value = true;
    }, 100);
  } else {
    ready.value = true;
    generateRandomString(32);
  }
});

// Define methods
const onProjectImageFileChange = (event) => {
  console.log(event.target.files[0].type);

  form.value.image = event.target.files[0];
};

const importLimitation = (updatedLimitaionData) => {
  form.value.limitation = updatedLimitaionData;
  console.log(updatedLimitaionData);
  // console.log(form.value.limitation);
};
const productUpdated = (data) => {
  emit('productUpdated', data);
};

const generateRandomString = (length) => {
  if (props.product) return;
  const characters =
    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let result = '';
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * characters.length));
  }
  form.value.secretCode = result;
};

const resetFormData = () => {
  // Reset form data to initial state
  form.value = {
    id: null,
    image: '',
    link: '',
    projectName: '',
    description: '',
    secretCode: '',
    projectType: '',
    environmentType: '',
    improtsConfigType: improtsConfigTypes[0].id,
    improtsConfigLink: '',
    improtsConfigJsonFile: '',
    limitation: [],
  };
};

const submit = async () => {
  errors.value = null;
  submitting.value = true;

  if (!props.product) {
    await submitForm();
  } else {
    await updateProduct();
  }

  submitting.value = false;
};

const submitForm = async () => {
  try {
    const formData = new FormData();

    // Append each form field to FormData
    Object.entries(form.value).forEach(([key, value]) => {
      // If the key is 'limitation', stringify the JSON array and append it
      if (key === 'limitation') {
        formData.append(key, JSON.stringify(value));
      } else {
        formData.append(key, value);
      }
    });
    console.log(formData);

    const response = await axios.post(
      '/api/projects/create-project',
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      },
    );

    console.log(response.data);

    const toast = {
      icon: 'success',
      message: 'Project Successfully Created!',
      position: 'bottom-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      showCloseButton: false,
    };
    store.dispatch('toast/popToast', toast);

    resetFormData();
  } catch (error) {
    // Handle axios errors
    if (axios.isAxiosError(error)) {
      if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors;
      } else {
        const toast = {
          icon: 'error',
          message: 'Error Creating Project',
          position: 'bottom-end',
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
          showCloseButton: false,
        };
        store.dispatch('toast/popToast', toast);
      }
    } else {
      // Handle non-axios errors
      console.error('Error creating project:', error);
    }
  }
};

const updateProduct = async () => {
  try {
    const updateFormData = new FormData();
    console.log(form.value);

    // Append each form field to FormData
    for (const [key, value] of Object.entries(form.value)) {
      // If the key is 'limitation', stringify the JSON array and append it
      if (key === 'limitation') {
        updateFormData.append(key, JSON.stringify(value));
      } else {
        updateFormData.append(key, value);
      }
    }
    console.log(updateFormData);

    const response = await axios.patch(
      `/api/projects/update-project/${props.product.id}`,
      form.value,
    );

    const toast = {
      icon: 'success',
      message: `User ${response.data.user.name} Successfully Updated!`,
      position: 'bottom-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      showCloseButton: false,
    };

    store.dispatch('toast/popToast', toast);
    emit('userUpdated', response.data.user);
    productUpdated(response.data);
  } catch (error) {
    if (axios.isAxiosError(error)) {
      if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors;
      } else {
        const toast = {
          icon: 'error',
          message: 'Error Updating Project',
          position: 'bottom-end',
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
          showCloseButton: false,
        };
        store.dispatch('toast/popToast', toast);
      }
    }
  }
};
</script>



