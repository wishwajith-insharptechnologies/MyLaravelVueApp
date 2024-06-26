<template>
    <MainLayout>
      <a-layout-header style="background: #fff; padding: 24px;">
        <h1>product List</h1>
      </a-layout-header>
      <a-layout-content style="padding: 24px;">
        <!-- Package List Table -->
        <a-table :columns="columns" :data-source="products" rowKey="id" :pagination="pagination" @change="handleTableChange">
          <template #name="{ text }">
            {{ text }}
          </template>
          <template #description="{ text }">
            {{ text }}
          </template>
          <template #price="{ text }">
            {{ text }}
          </template>
          <template #operation="{ record }">
            <span>
              <a-button type="primary" @click="editPackage(record)">Edit</a-button>
              <a-button type="danger" @click="deletePackage(record.id)">Delete</a-button>
            </span>
          </template>
        </a-table>

        <!-- Edit Package Modal -->
        <a-modal v-model:visible="editModalVisible" title="Edit Package" @ok="handleEditPackage" @cancel="closeEditModal">
            <ProductForm
                :product="updateProduct"
                @productUpdated="productUpdated"
              />
        </a-modal>

        <!-- Create Package Button -->
        <a-button type="primary" @click="showCreateModal">Create New Package</a-button>

        <!-- Create Package Modal -->
        <a-modal v-model:visible="createModalVisible" title="Create New Package" @ok="handleCreatePackage" @cancel="closeCreateModal">
          <a-form :model="createForm" ref="createFormRef">
            <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input the name!' }]">
              <a-input v-model:value="createForm.name" />
            </a-form-item>
            <a-form-item label="Description" name="description" :rules="[{ required: true, message: 'Please input the description!' }]">
              <a-input v-model:value="createForm.description" />
            </a-form-item>
            <a-form-item label="Price" name="price" :rules="[{ required: true, message: 'Please input the price!' }]">
              <a-input v-model:value="createForm.price" />
            </a-form-item>
          </a-form>
        </a-modal>
      </a-layout-content>
    </MainLayout>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import Http from '@/services/Http.js';
  import MainLayout from './../../../../components/layout/AdminLayout.vue';
  import ProductForm from '@/components/product/ProductForm.vue';
  import router from '@/router/index.js';


  const products = ref([]);
  const editModalVisible = ref(false);
  const createModalVisible = ref(false);
  const dataReady = ref(false);
  const editForm = ref({});
  const createForm = ref({});
  const editFormRef = ref(null);
  const createFormRef = ref(null);
  const updateProduct = ref(null);

  const pagination = ref({
    current: 1,
    pageSize: 10,
    total: 0
  });

  const getProducts = async (updatedPage = null) => {
    if (updatedPage) {
      pagination.value.current = updatedPage;
    }
    try {
      const { data } = await Http.get(`/api/projects?page=${pagination.value.current}&per=${pagination.value.pageSize}`);
      products.value = data.data;
      pagination.value.total = data.total;
      dataReady.value = true;
    } catch (error) {
      console.error('Error getting packages:', error);
      this.popToast({
        message: 'Error Getting Packages',
        timer: 5000,
        icon: 'error',
      });
      dataReady.value = true;
    }
  };

  const showCreateModal = () => {
    router.push({ name: 'ProductAddPage' });
  };

  const handleCreatePackage = async () => {
    try {
      await createFormRef.value.validate();
      // Call your API to create the package
      await Http.post('/api/packages', createForm.value);
      createModalVisible.value = false;
      getProducts();
    } catch (error) {
      console.error('Error creating package:', error);
    }
  };

  const handleEditPackage = async () => {
    try {
      await editFormRef.value.validate();
      // Call your API to update the package
      await Http.patch(`/api/packages/update-package/${editForm.value.id}`, editForm.value);
      editModalVisible.value = false;
      getProducts();
    } catch (error) {
      console.error('Error updating package:', error);
    }
  };

  const editPackage = (pkg) => {
    console.log(pkg);
    editForm.value = { ...pkg };
    updateProduct.value = { ...pkg };
    editModalVisible.value = true;
  };

  const deletePackage = async (id) => {
    try {
      await Http.delete(`/api/projects/delete/project/${id}`);
      getProducts();
    } catch (error) {
      console.error('Error deleting package:', error);
    }
  };

  const closeCreateModal = () => {
    createModalVisible.value = false;
  };

  const closeEditModal = () => {
    editModalVisible.value = false;
  };

  const handleTableChange = (pagination) => {
    getProducts(pagination.current);
  };

  const columns = [
    { dataIndex: 'projectName', title: 'Name', key: 'projectName' },
    { dataIndex: 'description', title: 'Description', key: 'description' },
    { dataIndex: 'link', title: 'Link', key: 'link' },
    { dataIndex: 'secretCode', title: 'Secret Code', key: 'secretCode' },
    {
      title: 'Operation',
      key: 'operation',
      slots: { customRender: 'operation' }
    }
  ];

  onMounted(() => {
    getProducts();
  });
  </script>

  <style scoped>
  h1 {
    margin: 0;
  }
  </style>
