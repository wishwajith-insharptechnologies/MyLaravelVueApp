<template>
      <a-layout-header style="background: #fff; padding: 24px;">
        <h1>Product Management</h1>
      </a-layout-header>
      <a-layout-content style="padding: 24px;">
        <!-- Product List Table -->
        <a-table :columns="columns" :data-source="products":row-key="record => record.id" >
          <template #name="{ text }">
            {{ text }}
          </template>
          <template #description="{ text }">
            {{ text }}tesr
          </template>
          <template #price="{ text }">
            {{ text }}
          </template>
          <template #operation="{ record }">
            <span>
              <a-button type="primary"  style="margin: 10px;" @click="handelEditProduct(record)">Edit</a-button>
              <a-button type="primary" @click="handelDeleteProduct(record.id)" danger ghost>Delete</a-button>
            </span>
          </template>
        </a-table>

        <!-- Create Product Button -->
        <a-button type="primary" @click="handleCreateNewProject">Create New Product</a-button>
      </a-layout-content>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { message, Modal } from "ant-design-vue";

  import { useRouter } from 'vue-router';
  import { getProducts, deleteProject } from '@/services/ProjectService';
  const router = useRouter();
  const { confirm } = Modal;

  const products = ref([]);
  const dataReady = ref(false);

  const projectTypes = [
  { id: 1, name: 'web' },
  { id: 2, name: 'mobile' },
  { id: 3, name: 'SASS' },
  { id: 4, name: 'solution' },
];

const environmentTypes = [
  { value: 0, name: 'Web Base' },
  { value: 1, name: 'Standalone' },
];

  onMounted(() => {
    loadProducts();
  });

  const loadProducts = async () => {
    try {
      const {data} = await getProducts();
      console.log(data);
      products.value = data;
      dataReady.value = true;
    } catch (error) {
      console.error('Error getting products:', error);
      dataReady.value = true;
    }
  };

  const handelEditProduct = (record) => {
    router.push({ name: 'productEditPage', params: { id: record.id } });
  };
  const handleCreateNewProject = () => {
    router.push({ name: 'productAddPage' });
  };

  const handelDeleteProduct = async (id) => {
    try {

      confirm({
        title: 'Are you sure you want to delete this item?',
        content: 'This action cannot be undone',
        okText: 'Yes',
        okType: 'danger',
        cancelText: 'No',
        onOk() {
            const response = deleteProject(id);
            loadProducts();
            message.success('Item deleted successfully');
        },
        onCancel() {
          message.info('Delete action cancelled');
        },
      });
    } catch (error) {
      console.error('Error deleting package:', error);
    }
  };

  // Function to map project type ID to name
const getProjectTypeName = (id) => {
  const type = projectTypes.find(type => type.id === id);
  return type ? type.name : 'Unknown';
};

// Function to map environment type value to name
const getEnvironmentTypeName = (value) => {
  const type = environmentTypes.find(type => type.value === value);
  return type ? type.name : 'Unknown';
};

  const columns = [
  { dataIndex: 'projectName', title: 'Project Name', key: 'projectName' },
  {
    dataIndex: 'projectType',
    title: 'Project Type',
    key: 'projectType',
    customRender: ({ text }) => getProjectTypeName(text)
  },
  {
    dataIndex: 'environmentType',
    title: 'Environment Type',
    key: 'environmentType',
    customRender: ({ text }) => getEnvironmentTypeName(text)
  },
  { dataIndex: 'description', title: 'Description', key: 'description' },
  { dataIndex: 'link', title: 'Link', key: 'link' },
  { dataIndex: 'secretCode', title: 'Secret Code', key: 'secretCode' },
  {
    title: 'Operation',
    key: 'operation',
    slots: { customRender: 'operation' }
  }
];
  </script>

  <style scoped>
  h1 {
    margin: 0;
  }
  </style>
