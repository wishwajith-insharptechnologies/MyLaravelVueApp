<template>
  <a-form
    :model="form"
    layout="vertical"
    ref="formRef"
    @finish="onFinish"
  >
    <a-form-item
      label="Title"
      :name="['form', 'title']"
      :rules="[{ required: true, message: 'Title is required', trigger: 'blur' }]"
    >
      <a-input v-model:value="form.title" placeholder="Enter title" />
    </a-form-item>

    <a-form-item
      label="Description"
      :name="['form', 'description']"
      :rules="[{ required: true, message: 'Description is required', trigger: 'blur' }]"
    >
      <a-input v-model:value="form.description" placeholder="Enter description" />
    </a-form-item>

    <a-form-item
      label="Project"
      :name="['form', 'project_id']"
      :rules="[{ required: true, message: 'Product is required', trigger: 'change' }]"
    >
      <a-select v-model:value="form.product_id" @change="projectSelectChange" placeholder="Select product ID">
        <a-select-option v-for="product in projectsList" :key="product.id" :value="product.id">
          {{ product.project_name }}
        </a-select-option>
      </a-select>
    </a-form-item>

    <a-form-item
      label="Rank"
      :name="['form', 'rank']"
      :rules="[{ required: true, message: 'Rank is required', trigger: 'blur' }]"
    >
      <a-input v-model:value="form.rank" placeholder="Enter rank" />
    </a-form-item>

    <a-form-item
      label="Validity"
      :name="['form', 'validity']"
      :rules="[{ required: true, message: 'Validity period is required', trigger: 'blur' }]"
    >
      <a-input v-model:value="form.validity" placeholder="Enter validity period" />
    </a-form-item>

    <a-form-item
      label="Price"
      :name="['form', 'price']"
      :rules="[{ required: true, type: 'number', message: 'Price is required', trigger: 'blur' }]"
    >
      <a-input-number v-model:value="form.price" placeholder="Enter price" style="width: 100%;" />
    </a-form-item>

    <a-form-item
      label="Discount"
      :name="['form', 'discount']"
      :rules="[{ type: 'number', message: 'Discount is required', trigger: 'blur' }]"
    >
      <a-input-number v-model:value="form.discount" placeholder="Enter discount" style="width: 100%;" />
    </a-form-item>

    <a-form-item
      label="Images"
      :name="['form', 'images']"
      :rules="[{ required: true, message: 'Please upload at least one image', trigger: 'change' }]"
    >
      <a-upload
        v-model:file-list="form.images"
        list-type="picture"
        action="/upload.do"
        :multiple="true"
      >
        <a-button>
          <a-icon type="upload" /> Upload
        </a-button>
      </a-upload>
    </a-form-item>

    <a-form-item
      label="Status"
      :name="['form', 'status']"
      :rules="[{ required: true, type: 'boolean', message: 'Status is required', trigger: 'change' }]"
    >
      <a-switch v-model:checked="form.status" />
    </a-form-item>

    <a-form-item
      label="Trial Period"
      :name="['form', 'trial_period']"
      :rules="[{ type: 'number', message: 'Trial period is required', trigger: 'blur' }]"
    >
      <a-input v-model:value="form.trial_period" placeholder="Enter trial period" />
    </a-form-item>

    <a-form-item
      label="Category ID"
      :name="['form', 'category_id']"
      :rules="[{ required: true, message: 'Category ID is required', trigger: 'blur' }]"
    >
      <a-input v-model:value="form.category_id" placeholder="Enter category ID" />
    </a-form-item>

    <LimitationForm
      v-model="form.limitation"
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
  import { onMounted, ref } from 'vue'
  import { message } from 'ant-design-vue'
  import LimitationForm from './PackageLimitationForm.vue';
  import { buildFormData } from "@/services/Utils.js";
  import { createPackage } from '@/services/PackageService';
  import { getProduct, getProjectsList } from "@/services/ProjectService.js";


  const props = defineProps({
  package: { type: Object, default: null },
});

  const form = ref({
    title: '',
    description: '',
    product_id: '',
    rank: '',
    validity: '',
    price: '',
    discount: '',
    images: [],
    status: true,
    trial_period: '',
    category_id: '',
    limitation: {},
  });
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
  required: '${label} is required!',
  types: {
    email: '${label} is not a valid email!',
    number: '${label} is not a valid number!',
  },
  number: {
    range: '${label} must be between ${min} and ${max}',
  },
};

const onFinish = values => {
  console.log('Success:', values);
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

const loadProjectLimitation = async () => {
  try {
    const data  = await getProduct(form.value.product_id);
    loadedLimitations.value = data.project.limitation.limitation;
    console.log(data);

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
    console.log(data);

    message.success('Package submitted successfully!')

    resetFormData();
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
