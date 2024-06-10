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

      <a-form-item label="Product ID">
        <a-input v-model:value="form.product_id" placeholder="Enter product ID" />
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

      <a-form-item label="Limitation">
        <a-input v-model:value="form.limitation" placeholder="Enter limitation" />
      </a-form-item>

      <a-form-item>
        <a-button type="primary" html-type="submit">Submit</a-button>
      </a-form-item>
    </a-form>
  </template>

  <script setup>
  import { ref } from 'vue'
  import { message } from 'ant-design-vue'

  const form = ref({
    title: '',
    description: '',
    product_id: '',
    rank: '',
    validity: '',
    price: '',
    discount: '',
    images: [],
    status: 1,
    trial_period: '',
    category_id: '',
    limitation: {},
  })

  const handleSubmit = () => {
    // Validate form and handle submission logic
    console.log('Form submitted:', form.value)
    message.success('Form submitted successfully!')
  }
  </script>

  <style>
  /* Add your custom styles here */
  </style>
