<template>
    <a-layout-header style="background: #fff; padding: 24px;">
      <h1>User Management</h1>
    </a-layout-header>
    <a-layout-content style="padding: 24px;">
      <!-- User List Table -->
      <a-table :columns="columns" :data-source="users" rowKey="id" :pagination="pagination" @change="handleTableChange">
        <template #name="{ text }">
          {{ text }}
        </template>
        <template #email="{ text }">
          {{ text }}
        </template>
        <template #operation="{ record }">
          <span>
            <a-button type="primary" @click="editUser(record)">Edit</a-button>
            <a-button type="danger" @click="deleteUser(record.id)">Delete</a-button>
          </span>
        </template>
      </a-table>

      <!-- Edit User Modal -->
      <a-modal v-model:visible="editModalVisible" title="Edit User" @ok="handleEditUser" @cancel="closeEditModal">
        <a-form :model="editForm" ref="editFormRef">
          <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input the name!' }]">
            <a-input v-model:value="editForm.name" />
          </a-form-item>
          <a-form-item label="Email" name="email" :rules="[{ required: true, message: 'Please input the email!' }]">
            <a-input v-model:value="editForm.email" />
          </a-form-item>
        </a-form>
      </a-modal>

      <!-- Create User Button -->
      <a-button type="primary" @click="showCreateModal">Create New User</a-button>

      <!-- Create User Modal -->
      <a-modal v-model:visible="createModalVisible" title="Create New User" @ok="handleCreateUser" @cancel="closeCreateModal">
        <a-form :model="createForm" ref="createFormRef">
          <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input the name!' }]">
            <a-input v-model:value="createForm.name" />
          </a-form-item>
          <a-form-item label="Email" name="email" :rules="[{ required: true, message: 'Please input the email!' }]">
            <a-input v-model:value="createForm.email" />
          </a-form-item>
        </a-form>
      </a-modal>
    </a-layout-content>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Http from '@/services/Http.js';
import { Table, Button, Modal, Form, Input } from 'ant-design-vue';

const users = ref([]);
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

const getUsers = async (updatedPage = null) => {
  if (updatedPage) {
    pagination.value.current = updatedPage;
  }
  try {
    const { data } = await Http.get(`users?page=${pagination.value.current}&per=${pagination.value.pageSize}`);
    users.value = data.data;
    pagination.value.total = data.total;
    dataReady.value = true;
  } catch (error) {
    console.error('Error getting users:', error);
    this.popToast({
      message: 'Error Getting Users',
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

const handleCreateUser = async () => {
  try {
    await createFormRef.value.validate();
    // Call your API to create the user
    await Http.post('users', createForm.value);
    createModalVisible.value = false;
    getUsers();
  } catch (error) {
    console.error('Error creating user:', error);
  }
};

const handleEditUser = async () => {
  try {
    await editFormRef.value.validate();
    // Call your API to update the user
    await Http.patch(`users/update-user/${editForm.value.id}`, editForm.value);
    editModalVisible.value = false;
    getUsers();
  } catch (error) {
    console.error('Error updating user:', error);
  }
};

const editUser = (user) => {
  editForm.value = { ...user };
  editModalVisible.value = true;
};

const deleteUser = async (id) => {
  try {
    await Http.delete(`users/delete/user/${id}`);
    getUsers();
  } catch (error) {
    console.error('Error deleting user:', error);
  }
};

const closeCreateModal = () => {
  createModalVisible.value = false;
};

const closeEditModal = () => {
  editModalVisible.value = false;
};

const handleTableChange = (pagination) => {
  getUsers(pagination.current);
};

const columns = [
  { dataIndex: 'name', title: 'Name', key: 'name' },
  { dataIndex: 'email', title: 'Email', key: 'email' },
  {
    title: 'Operation',
    key: 'operation',
    slots: { customRender: 'operation' }
  }
];

onMounted(() => {
  getUsers();
});
</script>

<style scoped>
h1 {
  margin: 0;
}
</style>
