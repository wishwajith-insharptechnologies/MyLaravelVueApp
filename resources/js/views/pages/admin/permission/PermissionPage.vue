<template>
      <p class="text-[20px] font-bold m-0 mb-4">
        Permission Management
      </p>
      <div>
        <!-- Permission List Table -->
        <a-table :scroll="{ x: 767 }" :columns="columns" :data-source="permissions" rowKey="id" :pagination="pagination" @change="handleTableChange">
          <template #name="{ text }">
            {{ text }}
          </template>
          <template #description="{ text }">
            {{ text }}
          </template>
          <template #operation="{ record }">
            <span>
              <a-button type="primary" @click="editPermission(record)">Edit</a-button>
              <a-button type="danger" @click="deletePermission(record.id)">Delete</a-button>
            </span>
          </template>
        </a-table>

        <!-- Edit Permission Modal -->
        <a-modal v-model:visible="editModalVisible" title="Edit Permission" @ok="handleEditPermission" @cancel="closeEditModal">
          <a-form layout="vertical" :model="editForm" ref="editFormRef">
            <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input the name!' }]">
              <a-input v-model:value="editForm.name" />
            </a-form-item>
            <a-form-item label="Description" name="description" :rules="[{ required: true, message: 'Please input the description!' }]">
              <a-input v-model:value="editForm.description" />
            </a-form-item>
          </a-form>
        </a-modal>

        <!-- Create Permission Button -->
        <a-button class="mt-6" type="primary" @click="showCreateModal">Create New Permission</a-button>

        <!-- Create Permission Modal -->
        <a-modal v-model:visible="createModalVisible" title="Create New Permission" @ok="handleCreatePermission" @cancel="closeCreateModal">
          <a-form layout="vertical" :model="createForm" ref="createFormRef">
            <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input the name!' }]">
              <a-input v-model:value="createForm.name" />
            </a-form-item>
            <a-form-item label="Description" name="description" :rules="[{ required: true, message: 'Please input the description!' }]">
              <a-input v-model:value="createForm.description" />
            </a-form-item>
          </a-form>
        </a-modal>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import Http from '@/services/Http.js';

  const permissions = ref([]);
  const editModalVisible = ref(false);
  const createModalVisible = ref(false);
  const dataReady = ref(false);
  const editForm = ref({});
  const createForm = ref({});
  const editFormRef = ref(null);
  const createFormRef = ref(null);

  const pagination = ref({
    current: 1,
    pageSize: 10,
    total: 0
  });

  const getPermissions = async (updatedPage = null) => {
    if (updatedPage) {
      pagination.value.current = updatedPage;
    }
    try {
      const { data } = await Http.get(`permissions?page=${pagination.value.current}&per=${pagination.value.pageSize}`);
      permissions.value = data.data;
      pagination.value.total = data.total;
      dataReady.value = true;
    } catch (error) {
      console.error('Error getting permissions:', error);
      this.popToast({
        message: 'Error Getting Permissions',
        timer: 5000,
        icon: 'error',
      });
      dataReady.value = true;
    }
  };

  const showCreateModal = () => {
    createForm.value = {};
    createModalVisible.value = true;
  };

  const handleCreatePermission = async () => {
    try {
      await createFormRef.value.validate();
      // Call your API to create the permission
      await Http.post('permissions', createForm.value);
      createModalVisible.value = false;
      getPermissions();
    } catch (error) {
      console.error('Error creating permission:', error);
    }
  };

  const handleEditPermission = async () => {
    try {
      await editFormRef.value.validate();
      // Call your API to update the permission
      await Http.patch(`permissions/update-permission/${editForm.value.id}`, editForm.value);
      editModalVisible.value = false;
      getPermissions();
    } catch (error) {
      console.error('Error updating permission:', error);
    }
  };

  const editPermission = (permission) => {
    editForm.value = { ...permission };
    editModalVisible.value = true;
  };

  const deletePermission = async (id) => {
    try {
      await Http.delete(`permissions/delete/permission/${id}`);
      getPermissions();
    } catch (error) {
      console.error('Error deleting permission:', error);
    }
  };

  const closeCreateModal = () => {
    createModalVisible.value = false;
  };

  const closeEditModal = () => {
    editModalVisible.value = false;
  };

  const handleTableChange = (pagination) => {
    getPermissions(pagination.current);
  };

  const columns = [
    { dataIndex: 'name', title: 'Name', key: 'name' },
    { dataIndex: 'description', title: 'Description', key: 'description' },
    {
      title: 'Operation',
      key: 'operation',
      slots: { customRender: 'operation' }
    }
  ];

  onMounted(() => {
    getPermissions();
  });
  </script>

  <style scoped>
  h1 {
    margin: 0;
  }
  </style>
