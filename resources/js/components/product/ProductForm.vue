<template>
    <div class="container">
        <a-card
            class="ml-5 mr-20 bg-white max-w-mx dark:bg-slate-800 dark:text-gray-200"
        >
            <!-- Title -->
            <h2 class="mb-4 text-lg font-bold">
                {{ product ? "Edit" : "Create" }} Product
            </h2>

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
            <a-form
                :model="form"
                ref="productFormRef"
                @submit.prevent="submit"
                @finish="submit"
                :validateMessages="validateMessages"
            >
                <!-- project_name -->
                <a-form-item
                    label="Project Name"
                    name="projectName"
                    :rules="[
                        {
                            required: true,
                            message: 'Project Name is required',
                            trigger: 'blur',
                        },
                    ]"
                    required
                >
                    <a-input
                        id="projectName"
                        v-model:value="form.projectName"
                        placeholder="Project Name"
                    />
                </a-form-item>

                <!-- environment_type -->
                <a-form-item
                label="Environment Type"
                    name="projectName"
                    :rules="[
                        {
                            required: true,
                            message: 'Environment Type is required',
                            trigger: 'blur',
                        },
                    ]"
                    required
                >
                    <a-select
                        id="environmentType"
                        v-model:value="form.environmentType"
                        placeholder="Select Environment Type"
                        :class="
                            errors && errors.environment_type
                                ? 'ant-input-status-error'
                                : ''
                        "
                    >
                        <a-select-option value="" disabled
                            >Select Environment Type</a-select-option
                        >
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
                <a-form-item
                label="Project Type"
                    name="projectType"
                    :rules="[
                        {
                            required: true,
                            message: 'Project Type is required',
                            trigger: 'blur',
                        },
                    ]"
                    required
                >
                    <a-select
                        id="projectType"
                        v-model:value="form.projectType"
                        placeholder="Select Project Type"
                        :class="
                            errors && errors.project_type
                                ? 'ant-input-status-error'
                                : ''
                        "
                    >
                        <a-select-option value="" disabled
                            >Select Project Type</a-select-option
                        >
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
                <a-form-item
                    label="Project Description"
                    name="projectDescription"
                >
                    <a-textarea
                        id="projectDescription"
                        v-model:value="form.description"
                        placeholder="Project Description"
                        rows="4"
                        :class="
                            errors && errors.description
                                ? 'ant-input-status-error'
                                : ''
                        "
                    />
                </a-form-item>

                <!-- project_image -->
                <a-form-item
                    label="Image"
                    name="project_image"
                >
                    <a-upload
                        accept="image/*"
                        custom-request="onProjectImageFileChange"
                        :class="
                            errors && errors.project_image
                                ? 'ant-input-status-error'
                                : ''
                        "
                    >
                        <a-button icon="upload">Click to Upload</a-button>
                    </a-upload>
                </a-form-item>

                <!-- project_secret_code -->
                <a-form-item
                label="Secret Code"
                    name="secretCode"
                    :rules="[
                        {
                            required: true,
                            message: 'Secret Code is required',
                            trigger: 'blur',
                        },
                    ]"
                    required
                >
                    <a-input
                        id="secretCode"
                        :disabled="true"
                        v-model:value="form.secretCode"
                        placeholder="Secret Code"
                        :class="
                            errors && errors.secret_code
                                ? 'ant-input-status-error'
                                : ''
                        "
                    >
                        <template #suffix>
                            <a-icon
                                :component="CopyOutlined"
                                @click="copyToClipboard"
                                class="copy-icon"
                                >Copy</a-icon
                            >
                        </template>
                    </a-input>
                </a-form-item>

                <!-- product_link -->
                <a-form-item
                    label="Product Link"
                    name="productLink"
                >
                    <a-input
                        id="productLink"
                        v-model:value="form.link"
                        placeholder="Product Link"
                        :class="
                            errors && errors.link
                                ? 'ant-input-status-error'
                                : ''
                        "
                    />
                </a-form-item>

                <!-- import limitations -->
                <a-form-item
                    name="limitation"
                    :rules="[
                        {
                            required: true,
                            message: 'Upload JSON Config File  is required',
                            trigger: 'blur',
                        },
                    ]"
                    required
                >
                    <ImprotLimitations
                        ref="limitationImport"
                        :stored-limitation-data="storedLimitations"
                        :errors="errors"
                        @limitationDataChanged="importLimitation"
                    />
                </a-form-item>
                <p></p>

                <!-- Submit and Cancel Buttons -->
                <a-form-item>
                    <a-button
                        type="primary"
                        html-type="submit"
                        :loading="submitting"
                    >
                        {{ product ? "Update" : "Submit" }}
                    </a-button>
                </a-form-item>
            </a-form>
        </a-card>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import Http from "@/services/Http.js";
import { useAuthStore } from "@/stores/modules/auth.js";
import ImprotLimitations from "@/components/product/ImprotLimitations.vue";
import { generateRandomString, buildFormData } from "@/services/Utils.js";
import { CopyOutlined } from "@ant-design/icons-vue";
import { message } from "ant-design-vue";
import { createProduct, updateProduct } from "@/services/ProjectService.js";

const store = useAuthStore();
const props = defineProps({
    product: { type: Object, default: null },
});

const limitationImport = ref(null);
const submitting = ref(false);
const productFormRef = ref(null);
const errors = ref({});
const ready = ref(false);

const projectTypes = [
    { id: 1, name: "web" },
    { id: 2, name: "mobile" },
    { id: 3, name: "SASS" },
    { id: 4, name: "solution" },
];
const environmentTypes = [
    { value: 0, name: "Web Base" },
    { value: 1, name: "Stand 0lone" },
];
const improtsConfigTypes = [
    { id: 1, name: "Json" },
    { id: 2, name: "Web" },
];

const initialFormState = () => ({
    id: null,
    image: "",
    link: "",
    projectName: "",
    description: "",
    secretCode: "",
    projectType: "",
    environmentType: "",
    improtsConfigType: improtsConfigTypes[0].id,
    improtsConfigLink: "",
    improtsConfigJsonFile: "",
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
            limitation: props.product.limitation
                ? props.product.limitation.limitation
                : [],
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
    navigator.clipboard
        .writeText(form.value.secretCode)
        .then(() => {
            message.success("Copied to clipboard");
        })
        .catch((err) => {
            console.error("Could not copy text: ", err);
            message.error("Could not copy text: ");
        });
};

const getErrors = (field) => {
    return errors.value?.[field]?.[0] || "";
};

const submit = async () => {
    await productFormRef.value.validate();
    errors.value = null;
    submitting.value = true;

    try {
        if (!props.product) {
            await submitForm();
        } else {
            await updateProductForm();
        }
    } catch (error) {
        console.error("Error submitting form:", error);
    } finally {
        submitting.value = false;
    }
};

const submitForm = async () => {
    try {
        const formData = buildFormData(form.value);

        const response = await createProduct(formData);
        console.log(response.data);
        message.success("Project created successfully.");
        clearFormData();
    } catch (error) {
        if (error.response && error.response.data) {
            errors.value = error.response.data.errors;
        }
        message.error("Failed to create project");
        console.error("Error creating project:", error);
    }
};

const updateProductForm = async () => {
    try {
        const formData = buildFormData(form.value);

        const response = await updateProduct(props.product.id, formData);
        message.success("Project Updated successfully.");
    } catch (error) {
        message.error("Failed to update project");
    }
};

const clearFormData = () => {
    form.value = initialFormState();
    limitationImport.value.clearLimitationData();
};

function generateSecretCode(length) {
    form.value.secretCode = generateRandomString(length);
}
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
