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
            name="product_id"
            :rules="[
                {
                    required: true,
                    message: 'Product is required',
                    trigger: 'change',
                },
            ]"
        >
            <a-select
                v-model:value="form.product_id"
                @change="projectSelectChange"
                placeholder="Select project ID"
                disabled
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

        >
            <a-input-number
                v-model:value="form.discount"
                placeholder="Enter discount"
                style="width: 100%"
            />
        </a-form-item>

        <a-form-item label="Image" name="image">
            <ImageUpload @uploadedImage="haddieUploadImage" />
        </a-form-item>

        <a-form-item label="Status" name="status">
            <a-switch v-model:checked="form.status" />
        </a-form-item>

        <a-form-item
            label="Trial Period"
            name="trial_period"
        >
            <a-input-number
                v-model:value="form.trial_period"
                placeholder="Enter trial period"
                style="width: 100%"
            />
        </a-form-item>

        <a-paragraph style="color: red" v-if="!isLimitationFulfillAllFiled"
            >Please fill required limitation value to submit</a-paragraph
        >
        <LimitationForm
            v-model:value="form.limitation"
            :limitations="loadedLimitations"
            @update:value="handleLimitationsUpdate"
            @update:errors="handleLimitationsErrors"
        />

        <a-form-item>
            <a-button type="primary" html-type="submit">Update</a-button>
        </a-form-item>
    </a-form>
</template>

<script setup>
import { onMounted, watch, ref } from "vue";
import { message } from "ant-design-vue";
import LimitationForm from "./PackageLimitationForm.vue";
import ImageUpload from "@/components/utility/ImageUpload.vue";
import { buildFormData } from "@/services/Utils.js";
import { updatePackage } from "@/services/PackageService";
import { getProduct, getProjectsList } from "@/services/ProjectService.js";

const props = defineProps({
    package: { type: Object, default: null },
});

const form = ref({
    title: "",
    description: "",
    product_id: "",
    rank: 1,
    validity: "",
    price: "",
    discount: "",
    images: null,
    status: true,
    trial_period: 0,
    category_id: 1,
    limitation: {},
});
const formRef = ref(null);
const errors = ref([]);
const limitationErrors = ref(false);
const submitting = ref(false);
const projectsList = ref([]);
const loadedLimitations = ref([]);
const isLimitationFulfillAllFiled = ref(true);

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

const haddieUploadImage = (imageData) => {
    if(imageData.url){
        form.value.images = imageData.url;
    }
};

const limitationValidationCheck = () => {
    isLimitationFulfillAllFiled.value = isLimitationValuesFulfill(
        form.value.limitation
    );
    return isLimitationFulfillAllFiled.value;
};
const isLimitationValuesFulfill = (limitations) => {
    if (!limitations || limitations.length === 0) {
        console.log("limitaion null");
        return false;
    }

    for (let limitation of limitations) {
        if (
            limitation.required &&
            (limitation.value === null || limitation.value === "")
        ) {
            console.log("limitaion value null");
            return false;
        }
    }

    return true;
};

const handleSubmit = async () => {
    try {

        await formRef.value.validate();
        if (!limitationValidationCheck()) {
            return;
        }
        errors.value = null;
        submitting.value = true;
        // const formData = buildFormData(form.value);
        // console.log(formData);
        await updatePackage(props.package.id, form.value);
        message.success('Package Updated');
        submitting.value = false;
    }
    catch (error){
        console.log(error);
    }
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
        if (form.value.product_id) {
            const data = await getProduct(form.value.product_id);
            console.log(data);
            loadedLimitations.value = data.limitation.limitation;
        }

        // items.value = clonedeep(JSON.parse(data.project.limitation.limitation));

        // updateFormLimitations();
    } catch (error) {
        console.error(error);
        // Handle error
    }
};
const setEditLimitation = async () => {
    try {
        loadedLimitations.value = form.value.limitation;
    } catch (error) {
        console.error(error);
        // Handle error
    }
};

watch(
    () => props.package,
    (packageData) => {
        if (packageData) {
            console.log(packageData.limitation?.limitation );
            form.value = {
                ...form.value,
                ...packageData,
                limitation: packageData.limitation?.limitation || {},
            };

            setEditLimitation();
        }
    },
    { immediate: true }
);
</script>

<style>
/* Add your custom styles here */
</style>
