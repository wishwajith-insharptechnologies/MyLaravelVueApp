<template>
      <p class="text-[20px] font-bold m-0 mb-4">
        Package Management
      </p>
      <div>
        <!-- Package List Table -->
        <a-table :scroll="{ x: 767 }" :columns="columns" :data-source="packages" rowKey="id"  >
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
              <a-button type="primary"  style="margin: 10px;" @click="handelEditPackage(record)">Edit</a-button>
              <a-button type="primary" @click="handelDeletePackage(record.id)" danger ghost>Delete</a-button>
            </span>
          </template>
        </a-table>

        <!-- Create Package Button -->
        <a-button class="mt-6" type="primary" @click="handleCreateNewPackage">Create New Package</a-button>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { message, Modal } from "ant-design-vue";

  import Http from '@/services/Http.js';
  import { useRouter } from 'vue-router';
  import { getPackages, deletePackage } from '@/services/PackageService';

  const router = useRouter();
  const { confirm } = Modal;

  const packages = ref([]);
  const dataReady = ref(false);

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

  const handelEditPackage = (record) => {
    router.push({ name: 'packageEditPage', params: { id: record.id } });
  };
  const handleCreateNewPackage = () => {
    router.push({ name: 'packageAddPage' });
  };

  const handelDeletePackage = async (id) => {
    try {

      confirm({
        title: 'Are you sure you want to delete this item?',
        content: 'This action cannot be undone',
        okText: 'Yes',
        okType: 'danger',
        cancelText: 'No',
        onOk() {
            const response = deletePackage(id);
            loadPackages();
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

  const columns = [
    { dataIndex: 'title', title: 'Title', key: 'title' },
    { dataIndex: 'description', title: 'Description', key: 'description' },
    { dataIndex: 'price', title: 'Price (USD)', key: 'price' },
    { dataIndex: 'discount', title: 'Discount (%)', key: 'discount' },
    { dataIndex: 'rank', title: 'Rank', key: 'rank' },
    { dataIndex: 'validity', title: 'validity  (in days)', key: 'validity' },
    { dataIndex: 'trial_period', title: 'Trail (in days)', key: 'trial_period' },
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
