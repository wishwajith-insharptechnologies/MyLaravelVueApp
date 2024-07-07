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
          <a-form-item :label="labelWithRequired('Project Name')" required>
            <a-input
              id="projectName"
              v-model:value="form.projectName"
              placeholder="Project Name"
              :class="errors && errors.project_name ? 'ant-input-status-error' : ''"
            />
          </a-form-item>

          <!-- environment_type -->
          <a-form-item :label="labelWithRequired('Environment Type')" required>
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
          <a-form-item :label="labelWithRequired('Project Type')" required>
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
          <a-form-item :label="labelWithRequired('Project Description')" >
            <a-textarea
              id="projectDescription"
              v-model:value="form.description"
              placeholder="Project Description"
              rows="4"
              :class="errors && errors.description ? 'ant-input-status-error' : ''"
            />
          </a-form-item>

          <!-- project_image -->
          <a-form-item :label="labelWithRequired('Image')" >
            <a-upload
              accept="image/*"
              custom-request="onProjectImageFileChange"
              :class="errors && errors.project_image ? 'ant-input-status-error' : ''"
            >
              <a-button icon="upload">Click to Upload</a-button>
            </a-upload>
          </a-form-item>

          <!-- project_secret_code -->
          <a-form-item :label="labelWithRequired('Secret Code')" required>
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
          <a-form-item :label="labelWithRequired('Product Link')" >
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
    import { message } from 'ant-design-vue';


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

    const initialFormState = () => ({
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
    const form = ref(initialFormState());
    const limitations = ref(props.product);

    const isEditable = computed(() => {
        return props.product !== null;
    });

    const storedLimitations = computed(() => {
        if (limitations.value && limitations.value.limitation) {
                return limitations.value.limitation.limitation;
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

    const onProjectImageFileChange = (event) => {
        form.value.image = event.target.files[0];
    };

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

            message.success('Project created successfully.');
            clearFormData();

        } catch (error) {
            handleSubmissionError(error);
        }
    };

    const updateProduct = async () => {
        try {
            const updateFormData = new FormData();

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


        } catch (error) {
            handleSubmissionError(error);
        }
    };

    const clearFormData = () => {
        form.value = initialFormState();
        limitations.value = null;
    };

    function generateSecretCode(length) {
        form.value.secretCode = generateRandomString(length);
    }

    const handleSubmissionError = (error) => {
        if (Http.isAxiosError(error)) {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
            } else {
                message.error('Error project submit');
            }
        } else {
            console.error('Unexpected error occurred:', error);
            message.error('Unexpected error occurred');
        }
    };
    const labelWithRequired = (label) => {
        return `${label} `;
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




