<template>
      <a-layout-header style="background: #fff; padding: 24px;">
        <h1>Package Management</h1>
      </a-layout-header>
      <a-layout-content style="padding: 24px;">
        <!-- Package List Table -->
        <a-table :columns="columns" :data-source="packages" rowKey="id" :pagination="pagination" >
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
              <a-button type="danger" @click="handelDeletePackage(record.id)">Delete</a-button>
            </span>
          </template>
        </a-table>

        <!-- Create Package Button -->
        <a-button type="primary" @click="handleCreateNewPackage">Create New Package</a-button>
      </a-layout-content>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import Http from '@/services/Http.js';
  import { useRouter } from 'vue-router';
  import { getPackages, deletePackage } from '@/services/PackageService';

  const router = useRouter();

  const packages = ref([]);
  const dataReady = ref(false);
  const pagination = ref({
    current: 1,
    pageSize: 10,
    total: 0
  });

  onMounted(() => {
    loadPackages();
  });

  const loadPackages = async () => {
    try {
      const data = await getPackages();
      console.log(data);
      packages.value = data;
      dataReady.value = true;
    } catch (error) {
      console.error('Error getting packages:', error);
      dataReady.value = true;
    }
  };

  const handleCreateNewPackage = () => {
    router.push({ name: 'packageAddPage' });
  };

  const handelDeletePackage = async (id) => {
    try {
      const response = deletePackage(id);
      getPackages();
    } catch (error) {
      console.error('Error deleting package:', error);
    }
  };

  const columns = [
    { dataIndex: 'title', title: 'Title', key: 'title' },
    { dataIndex: 'description', title: 'Description', key: 'description' },
    { dataIndex: 'price', title: 'Price', key: 'price' },
    { dataIndex: 'discount', title: 'Discount', key: 'discount' },
    { dataIndex: 'rank', title: 'Rank', key: 'rank' },
    { dataIndex: 'validity', title: 'validity in days', key: 'validity' },
    { dataIndex: 'trial_period', title: 'Trail in days', key: 'trial_period' },
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
