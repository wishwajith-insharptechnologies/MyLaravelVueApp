<template>
    <a-layout-header style="background: #fff; padding: 24px;">
      <h1>Role Management</h1>
    </a-layout-header>
    <a-layout-content style="padding: 24px;">
      <!-- Role List Table -->
      <a-table :columns="columns" :dataSource="roles" rowKey="id" @change="handleTableChange">
        <template #operation="{ record }">
          <span>
            <a-button type="primary" @click="editRole(record)">Edit</a-button>
            <a-button type="danger" @click="handleDeleteRole(record.id)">Delete</a-button>
          </span>
        </template>
      </a-table>

      <!-- Edit Role Modal -->
      <a-modal v-model:visible="editModalVisible" title="Edit Role" @ok="handleEditRole" @cancel="closeEditModal">
        <a-form :model="editForm" ref="editFormRef" :initialValues="editForm">
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
        <a-form :model="createForm" ref="createFormRef" :initialValues="createForm">
          <a-form-item label="Name" name="name" :rules="[{ required: true, message: 'Please input the name!' }]">
            <a-input v-model:value="createForm.name" />
          </a-form-item>
          <a-form-item label="Description" name="description" :rules="[{ required: true, message: 'Please input the description!' }]">
            <a-input v-model:value="createForm.description" />
          </a-form-item>
        </a-form>
      </a-modal>
    </a-layout-content>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { message, Modal } from "ant-design-vue";
  import { getAllRoles, createRole, updateRole, deleteRole } from '@/services/RoleService';

  const roles = ref([]);
  const editModalVisible = ref(false);
  const createModalVisible = ref(false);
  const editForm = ref({});
  const createForm = ref({});
  const editFormRef = ref(null);
  const createFormRef = ref(null);
  const { confirm } = Modal;
  const columns = ref([
  { title: 'ID', dataIndex: 'id', key: 'id' },
  { title: 'Name', dataIndex: 'name', key: 'name' },
  { title: 'Description', dataIndex: 'description', key: 'description' },
  {
    title: 'Operation',
    key: 'operation',
    slots: { customRender: 'operation' }
  }
]);

  const getRoles = async (updatedPage = null) => {
    if (updatedPage) {
      pagination.current = updatedPage;
    }
    try {
      const { data } = await getAllRoles();
      console.log(data);
      roles.value = data;
    } catch (error) {
      console.error('Error getting roles:', error);
    }
  };

  const showCreateModal = () => {
    createForm.value = {};
    createModalVisible.value = true;
  };

  const handleCreateRole = async () => {
    try {
      await createFormRef.value.validate();
      await createRole(createForm.value);
      createModalVisible.value = false;
      getRoles();
      message.success("Role created successfully");
    } catch (error) {
      console.error('Error creating role:', error);
    }
  };

  const handleEditRole = async () => {
    try {
      await editFormRef.value.validate();
      await updateRole(editForm.value.id, editForm.value);
      editModalVisible.value = false;
      getRoles();
      message.success("Role edited successfully");
    } catch (error) {
      console.error('Error updating role:', error);
    }
  };

  const editRole = (role) => {
    editForm.value = { ...role };
    editModalVisible.value = true;
  };

  const handleDeleteRole = async (id) => {
    try {
        confirm({
            title: "Are you sure you want to delete this Role?",
            content: "This action cannot be undone",
            okText: "Yes",
            okType: "danger",
            cancelText: "No",
            onOk() {
                const response = deleteRole(id);
                getRoles();
                message.success("Role deleted successfully");
            },
            onCancel() {
                message.info("Delete action cancelled");
            },
        });

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

  onMounted(() => {
    getRoles();
  });
  </script>

  <style scoped>
  h1 {
    margin: 0;
  }
  </style>
