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
              :disabled="true"
              v-model:value="form.secretCode"
              placeholder="Secret Code"
              :class="errors && errors.secret_code ? 'ant-input-status-error' : ''"
            >
            <template #suffix>
                <a-icon
                :component="CopyOutlined"
                @click="copyToClipboard"
                class="copy-icon"
                >Copy</a-icon>
            </template>
            </a-input>
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
import Http from '@/services/Http.js';
import { useAuthStore } from '@/stores/modules/auth.js';
import ImprotLimitations from "@/components/product/ImprotLimitations.vue";
import { generateRandomString } from '@/services/Utils.js';
import { CopyOutlined } from '@ant-design/icons-vue';

const store = useAuthStore();
const props = defineProps({
  product: { type: Object, default: null },
});

const submitting = ref(false);
const errors = ref(null);
const ready = ref(false);

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
    form.value = {
      ...props.product,
      limitation: props.product.limitation ? props.product.limitation.limitation : [],
    };
    setTimeout(() => {
      ready.value = true;
    }, 100);
  } else {
    ready.value = true;
    generateSecretCode(32);
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

const copyToClipboard = () => {
      navigator.clipboard.writeText(form.value.secretCode).then(() => {
        console.log('Copied to clipboard');
      }).catch(err => {
        console.error('Could not copy text: ', err);
      });
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

const submitForm = async () => {
  try {
    const formData = new FormData();
    Object.entries(form.value).forEach(([key, value]) => {
      if (key === 'limitation') {
        formData.append(key, JSON.stringify(value));
      } else {
        formData.append(key, value);
      }
    });

    const response = await Http.post(
      'projects/create-project',
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      },
    );

    // Reset form data after successful submission
    resetFormData();

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

    const response = await Http.post(
      `projects/update-project/${props.product.id}`,
      updateFormData,
    );

    // Dispatch success toast notification

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

function generateSecretCode(length) {
    form.value.secretCode = generateRandomString(length);
}

// Method to handle form submission errors
const handleSubmissionError = (error) => {
  if (Http.isAxiosError(error)) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
        // alert Error Submitting Form

    }
  } else {
    console.error('Unexpected error occurred:', error);
  }
};
</script>

<style scoped>
.copy-icon {
  cursor: pointer;
  color: rgba(0, 0, 0, 0.45);
  transition: color 0.3s;
  width: 20px;
  height: 20px;
}
.copy-icon:hover {
  color: rgba(0, 0, 0, 0.75);
}
</style>




