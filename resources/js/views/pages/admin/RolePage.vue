<template>
    <MainLayout>
      <a-layout-header style="background: #fff; padding: 24px;">
        <h1>Role Management</h1>
      </a-layout-header>
      <a-layout-content style="padding: 24px;">
        <!-- Role List Table -->
        <a-table :columns="columns" :data-source="roles" rowKey="id" :pagination="pagination" @change="handleTableChange">
          <template #name="{ text }">
            {{ text }}
          </template>
          <template #description="{ text }">
            {{ text }}
          </template>
          <template #operation="{ record }">
            <span>
              <a-button type="primary" @click="editRole(record)">Edit</a-button>
              <a-button type="danger" @click="deleteRole(record.id)">Delete</a-button>
            </span>
          </template>
        </a-table>

        <!-- Edit Role Modal -->
        <a-modal v-model:visible="editModalVisible" title="Edit Role" @ok="handleEditRole" @cancel="closeEditModal">
          <a-form :model="editForm" ref="editFormRef">
            <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input the name!' }]">
              <a-input v-model:value="editForm.name" />
            </a-form-item>
            <a-form-item label="Description" name="description" :rules="[{ required: true, message: 'Please input the description!' }]">
              <a-input v-model:value="editForm.description" />
            </a-form-item>
          </a-form>
        </a-modal>

        <!-- Create Role Button -->
        <a-button type="primary" @click="showCreateModal">Create New Role</a-button>

        <!-- Create Role Modal -->
        <a-modal v-model:visible="createModalVisible" title="Create New Role" @ok="handleCreateRole" @cancel="closeCreateModal">
          <a-form :model="createForm" ref="createFormRef">
            <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input the name!' }]">
              <a-input v-model:value="createForm.name" />
            </a-form-item>
            <a-form-item label="Description" name="description" :rules="[{ required: true, message: 'Please input the description!' }]">
              <a-input v-model:value="createForm.description" />
            </a-form-item>
          </a-form>
        </a-modal>
      </a-layout-content>
    </MainLayout>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import http from '../../../services/axios';
  import MainLayout from './../../../components/layout/AdminLayout.vue';

  const roles = ref([]);
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

  const getRoles = async (updatedPage = null) => {
    if (updatedPage) {
      pagination.value.current = updatedPage;
    }
    try {
      const { data } = await http.get(`/api/roles?page=${pagination.value.current}&per=${pagination.value.pageSize}`);
      roles.value = data.data;
      pagination.value.total = data.total;
      dataReady.value = true;
    } catch (error) {
      console.error('Error getting roles:', error);
      this.popToast({
        message: 'Error Getting Roles',
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

  const handleCreateRole = async () => {
    try {
      await createFormRef.value.validate();
      // Call your API to create the role
      await http.post('/api/roles', createForm.value);
      createModalVisible.value = false;
      getRoles();
    } catch (error) {
      console.error('Error creating role:', error);
    }
  };

  const handleEditRole = async () => {
    try {
      await editFormRef.value.validate();
      // Call your API to update the role
      await http.patch(`/api/roles/update-role/${editForm.value.id}`, editForm.value);
      editModalVisible.value = false;
      getRoles();
    } catch (error) {
      console.error('Error updating role:', error);
    }
  };

  const editRole = (role) => {
    editForm.value = { ...role };
    editModalVisible.value = true;
  };

  const deleteRole = async (id) => {
    try {
      await http.delete(`/api/roles/delete/role/${id}`);
      getRoles();
    } catch (error) {
      console.error('Error deleting role:', error);
    }
  };

  const closeCreateModal = () => {
    createModalVisible.value = false;
  };

  const closeEditModal = () => {
    editModalVisible.value = false;
  };

  const handleTableChange = (pagination) => {
    getRoles(pagination.current);
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
    getRoles();
  });
  </script>

  <style scoped>
  h1 {
    margin: 0;
  }
  </style>
