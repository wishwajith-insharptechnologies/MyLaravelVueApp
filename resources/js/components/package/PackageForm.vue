<template>
    <a-form
      :model="form"
      layout="vertical"
      @submit.prevent="handleSubmit"
    >
      <a-form-item label="Title">
        <a-input v-model:value="form.title" placeholder="Enter title" />
      </a-form-item>

      <a-form-item label="Description">
        <a-input v-model:value="form.description" placeholder="Enter description" />
      </a-form-item>

      <a-form-item label="Project">
          <a-select v-model:value="form.product_id"   @change="projectSelectChange" placeholder="Select product ID">
          <a-select-option v-for="product in projectsList" :key="product.id" :value="product.id">
            {{ product.project_name }}
          </a-select-option>
        </a-select>
        </a-form-item>

      <a-form-item label="Rank">
        <a-input v-model:value="form.rank" placeholder="Enter rank" />
      </a-form-item>

      <a-form-item label="Validity">
        <a-input v-model:value="form.validity" placeholder="Enter validity period" />
      </a-form-item>

      <a-form-item label="Price">
        <a-input-number v-model:value="form.price" placeholder="Enter price" style="width: 100%;" />
      </a-form-item>

      <a-form-item label="Discount">
        <a-input-number v-model:value="form.discount" placeholder="Enter discount" style="width: 100%;" />
      </a-form-item>

      <a-form-item label="Images">
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

      <a-form-item label="Status">
        <a-switch v-model:checked="form.status" />
      </a-form-item>

      <a-form-item label="Trial Period">
        <a-input v-model:value="form.trial_period" placeholder="Enter trial period" />
      </a-form-item>

      <a-form-item label="Category ID">
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
  import Http from '@/services/Http.js';
  import LimitationForm from './PackageLimitationForm.vue';

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
  const errors = ref([]);
  const limitationErrors = ref(false);
  const submitting = ref(false);
  const projectsList = ref([]);
  const loadedLimitations = ref([]);

onMounted(() => {
    getProjectList();
});

const handleSubmit = async () => {
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
        const { data } = await Http.get(`/api/projects/list`);
        console.log(data);
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
    const { data } = await Http.get(`/api/project/${form.value.product_id}`);
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
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('description', form.value.description);
    formData.append('rank', form.value.rank);
    formData.append('validity', form.value.validity);
    formData.append('price', form.value.price);
    formData.append('discount', form.value.discount);
    formData.append('image', form.value.image);
    formData.append('status', (form.value.status ? 1 : 0));
    formData.append('trial_period', form.value.trial_period);
    formData.append('product_id', form.value.product_id);
    formData.append('category_id', form.value.category_id);
    formData.append('limitation', JSON.stringify(form.value.limitation));

    const { data } = await Http.post('/api/package/create-package', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    console.log(data);

    message.success('Form submitted successfully!')

    resetFormData();
  } catch (error) {
    console.error(error);
    // Handle error
  }
};
  </script>

  <style>
  /* Add your custom styles here */
  </style>
