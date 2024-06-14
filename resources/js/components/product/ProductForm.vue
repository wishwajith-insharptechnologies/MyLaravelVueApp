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
              v-model:value="form.projectName"
              placeholder="Project Name"
              :class="errors && errors.project_name ? 'ant-input-status-error' : ''"
            />
          </a-form-item>

          <!-- environment_type -->
          <a-form-item>
            <a-select
              id="environmentType"
              v-model:value="form.environmentType"
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
              v-model:value="form.projectType"
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
              v-model:value="form.description"
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
              v-model:value="form.secretCode"
              placeholder="Secret Code"
              :class="errors && errors.secret_code ? 'ant-input-status-error' : ''"
            />
          </a-form-item>

          <!-- product_link -->
          <a-form-item>
            <a-input
              id="productLink"
              v-model:value="form.link"
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
import ImprotLimitations from "@/components/product/ImprotLimitations.vue";

const store = useAuthStore();
const props = defineProps({
  product: { type: Object, default: null },
});

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

// Define form as a reactive reference
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
const ready = ref(false);

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

// Initialize form data when component is mounted
onMounted(() => {
  if (props.product) {
    // Update form data with product details
    console.log(props.product);
    console.log(props.product.limitation ? props.product.limitation.limitation : [] );

    form.value = {
      ...props.product,
      limitation: props.product.limitation ? props.product.limitation.limitation : [],
    };
    setTimeout(() => {
      ready.value = true;
    }, 100);
  } else {
    ready.value = true;
    generateRandomString(32);
  }
});

// Method to handle file change for project image
const onProjectImageFileChange = (event) => {
  form.value.image = event.target.files[0];
};

// Method to update limitations
const importLimitation = (updatedLimitationData) => {
  form.value.limitation = updatedLimitationData;
};

// Method to handle form submission
const submit = async () => {
  errors.value = null;
  submitting.value = true;

  try {
    if (!props.product) {
      await submitForm();
    } else {
      await updateProduct();
    }
  } catch (error) {
    console.error('Error submitting form:', error);
  } finally {
    submitting.value = false;
  }
};

// Method to submit form data for creating new project
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

    const response = await axios.post(
      '/api/projects/create-project',
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      },
    );

    // Reset form data after successful submission
    resetFormData();

    // Dispatch success toast notification
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

  } catch (error) {
    // Handle errors
    handleSubmissionError(error);
  }
};

// Method to update existing project
const updateProduct = async () => {
  try {
    const updateFormData = new FormData();

    // Append each form field to FormData
    for (const [key, value] of Object.entries(form.value)) {
      if (key === 'limitation') {
        updateFormData.append(key, JSON.stringify(value));
      } else {
        updateFormData.append(key, value);
      }
    }

    const response = await axios.post(
      `/api/projects/update-project/${props.product.id}`,
      updateFormData,
    );

    // Dispatch success toast notification
    const toast = {
      icon: 'success',
      message: `Project Updated Successfully!`,
      position: 'bottom-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      showCloseButton: false,
    };
    store.dispatch('toast/popToast', toast);

  } catch (error) {
    // Handle errors
    handleSubmissionError(error);
  }
};

// Method to reset form data
const resetFormData = () => {
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

// Method to generate random string
const generateRandomString = (length) => {
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let result = '';
  for (let i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * characters.length));
  }
  form.value.secretCode = result;
};

// Method to handle form submission errors
const handleSubmissionError = (error) => {
  if (axios.isAxiosError(error)) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      const toast = {
        icon: 'error',
        message: 'Error Submitting Form',
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        showCloseButton: false,
      };
      store.dispatch('toast/popToast', toast);
    }
  } else {
    console.error('Unexpected error occurred:', error);
  }
};
</script>




