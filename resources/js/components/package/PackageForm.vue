<template>
    <a-form
        :model="form"
        layout="vertical"
        ref="formRef"
        @finish="handleSubmit"
        :validateMessages="validateMessages"
    >
        <a-form-item
            label="Title"
            name="title"
            :rules="[
                {
                    required: true,
                    message: 'Title is required',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input v-model:value="form.title" placeholder="Enter title" />
        </a-form-item>

        <a-form-item
            label="Description"
            name="description"
            :rules="[{ message: 'Description is required', trigger: 'blur' }]"
        >
            <a-input
                v-model:value="form.description"
                placeholder="Enter description"
            />
        </a-form-item>

        <a-form-item
            label="Project"
            name="project_id"
            :rules="[
                {
                    required: true,
                    message: 'Product is required',
                    trigger: 'change',
                },
            ]"
        >
            <a-select
                v-model:value="form.project_id"
                @change="projectSelectChange"
                placeholder="Select project ID"
            >
                <a-select-option
                    v-for="project in projectsList"
                    :key="project.id"
                    :value="project.id"
                >
                    {{ project.project_name }}
                </a-select-option>
            </a-select>
        </a-form-item>

        <a-form-item
            label="Rank"
            name="rank"
            :rules="[
                {
                    required: true,
                    message: 'Rank is required',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input v-model:value="form.rank" placeholder="Enter rank" />
        </a-form-item>

        <a-form-item
            label="Validity"
            name="validity"
            :rules="[
                {
                    required: true,
                    message: 'Validity period is required',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input
                v-model:value="form.validity"
                placeholder="Enter validity period"
            />
        </a-form-item>

        <a-form-item
            label="Price"
            name="price"
            :rules="[
                {
                    required: true,
                    type: 'number',
                    message: 'Price is required',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input-number
                v-model:value="form.price"
                placeholder="Enter price"
                style="width: 100%"
            />
        </a-form-item>

        <a-form-item
            label="Discount"
            name="discount"
            :rules="[
                {
                    type: 'number',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input-number
                v-model:value="form.discount"
                placeholder="Enter discount"
                style="width: 100%"
            />
        </a-form-item>

        <a-form-item label="Image" name="image">
            <a-upload :multiple="false"
                :custom-request="handleCustomRequest"
            >
                <a-button> <a-icon type="upload" /> Select Image </a-button>
                <div slot="fileList">
                    <a-image
                        v-if="previewImage"
                        :src="previewImage"
                        width="100%"
                    />
                </div>
            </a-upload>
        </a-form-item>

        <a-form-item label="Status" name="status">
            <a-switch v-model:checked="form.status" />
        </a-form-item>

        <a-form-item
            label="Trial Period"
            name="trial_period"
            :rules="[{ type: 'number', trigger: 'blur' }]"
        >
            <a-input-number
                v-model:value="form.trial_period"
                placeholder="Enter trial period"
                style="width: 100%"
            />
        </a-form-item>

        <a-form-item
            label="Category ID"
            name="category_id"
            :rules="[
                {
                    required: true,
                    message: 'Category ID is required',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input-number
                v-model:value="form.category_id"
                placeholder="Enter category ID"
                style="width: 100%"
            />
        </a-form-item>

        <LimitationForm
            v-model:value="form.limitation"
            :limitations="loadedLimitations"
            @update:value="handleLimitationsUpdate"
            @update:errors="handleLimitationsErrors"
        />

        <a-form-item>
            <a-button type="primary" html-type="submit">Submit</a-button>
        </a-form-item>
    </a-form>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { message } from "ant-design-vue";
import LimitationForm from "./PackageLimitationForm.vue";
import { buildFormData } from "@/services/Utils.js";
import { createPackage } from "@/services/PackageService";
import { getProduct, getProjectsList } from "@/services/ProjectService.js";

const props = defineProps({
    package: { type: Object, default: null },
});

const initialFormState = () => ({
    title: "",
    description: "",
    product_id: "",
    rank: "",
    validity: "",
    price: "",
    discount: "",
    images: null,
    status: true,
    trial_period: 0,
    category_id: "",
    limitation: {},
});
const form = ref(initialFormState());
const formRef = ref(null);
const errors = ref([]);
const limitationErrors = ref(false);
const submitting = ref(false);
const projectsList = ref([]);
const loadedLimitations = ref([]);

onMounted(() => {
    getProjectList();
});

const validateMessages = {
    required: "${label} is required!",
    types: {
        email: "${label} is not a valid email!",
        number: "${label} is not a valid number!",
    },
    number: {
        range: "${label} must be between ${min} and ${max}",
    },
};

const beforeUpload = (file) => {
    const isJpegOrPng = file.type === "image/jpeg" || file.type === "image/png";
    if (!isJpegOrPng) {
        message.error("You can only upload JPEG/PNG files!");
        return false;
    }
    return true;
};

const handleCustomRequest = async (option) => {
    const { file, onSuccess } = option;
    form.value.images = file;
    onSuccess();
};

const handleUploadChange = ({ file }) => {
    console.log(file);
    if (file.status === "done") {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file.originFileObj);
        form.value.images = [file.originFileObj];
    } else if (file.status === "removed") {
        imagePreview.value = null;
        form.value.images = [];
    }
};

const onFinish = (values) => {
    console.log("Success:", values);
};

const handleSubmit = async () => {
    await formRef.value.validate();
    errors.value = null;
    submitting.value = true;

    if (!props.package) {
        await submitForm();
    } else {
        await updatePackage();
    }

    submitting.value = false;
};

const getProjectList = async () => {
    try {
        const data = await getProjectsList();
        projectsList.value = data;
    } catch (error) {
        console.error(error);
        // Handle error
    }
};

const projectSelectChange = async (event) => {
    console.log("project select change");
    loadProjectLimitation();
};

const handleLimitationsUpdate = (limitationUpdate) => {
    form.value.limitation = limitationUpdate;
    console.log(limitationUpdate);
};

const handleLimitationsErrors = (isLimitationError) => {
    limitationErrors.value = isLimitationError.value.length === 0;
    console.log(isLimitationError.value);
    console.log(limitationErrors.value);
};

const clearFormData = () => {
    form.value = initialFormState();
    loadedLimitations.value = [];
};

const loadProjectLimitation = async () => {
    try {
        const data = await getProduct(form.value.project_id);
        console.log(data);
        loadedLimitations.value = data.limitation.limitation;

        // items.value = clonedeep(JSON.parse(data.project.limitation.limitation));

        // updateFormLimitations();
    } catch (error) {
        console.error(error);
        // Handle error
    }
};
const submitForm = async () => {
    try {
        const formData = buildFormData(form.value);

        const response = await createPackage(formData);

        message.success("Package submitted successfully!");

        clearFormData();
    } catch (error) {
        if (error.response && error.response.data) {
            errors.value = error.response.data.errors;
        }
        message.error("Failed to create project");
        console.error("Error creating project:", error);
    }
};
</script>

<style>
/* Add your custom styles here */
</style>
