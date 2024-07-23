<template>
    <a-layout-header style="background: #fff; padding: 24px">
        <h1>User Management</h1>
    </a-layout-header>
    <a-layout-content style="padding: 24px">
        <!-- User List Table -->
        <a-table
            :columns="columns"
            :data-source="users"
            rowKey="id"
            @change="handleTableChange"
        >
            <template #name="{ text }">
                {{ text }}
            </template>
            <template #email="{ text }">
                {{ text }}
            </template>
            <template #operation="{ record }">
                <span>
                    <a-button type="primary" @click="triggerEditUser(record)"
                        >Edit</a-button
                    >
                    <a-button type="danger" @click="triggerDeleteUser(record.id)"
                        >Delete</a-button
                    >
                </span>
            </template>
        </a-table>

        <!-- Edit User Modal -->
        <a-modal
            v-model:visible="editModalVisible"
            title="Edit User"
            @ok="handleEditUser"
            @cancel="closeEditModal"
        >
            <a-form :model="editForm" ref="editFormRef">
                <a-form-item
                    label="Name"
                    name="name"
                    :rules="[
                        { required: true, message: 'Please input the name!' },
                    ]"
                >
                    <a-input v-model:value="editForm.name" />
                </a-form-item>
                <a-form-item
                    label="Email"
                    name="email"
                    :rules="[
                        { required: true, message: 'Please input the email!' },
                    ]"
                >
                    <a-input v-model:value="editForm.email" />
                </a-form-item>
                <a-form-item
                    label="Role"
                    name="role"
                    :rules="[
                        { required: true, message: 'Please input the role!' },
                    ]"
                >
                    <a-select v-model:value="editForm.role">
                        <a-select-option
                            v-for="role in roles"
                            :key="role.id"
                            :value="role.id"
                        >
                            {{ role.name }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </a-form>
        </a-modal>

        <!-- Create User Button -->
        <a-button type="primary" @click="showCreateModal"
            >Create New User</a-button
        >

        <!-- Create User Modal -->
        <a-modal
            v-model:visible="createModalVisible"
            title="Create New User"
            @ok="handleCreateUser"
            @cancel="closeCreateModal"
        >
            <a-form :model="createForm" ref="createFormRef">
                <a-form-item
                    label="Name"
                    name="name"
                    :rules="[
                        { required: true, message: 'Please input the name!' },
                    ]"
                >
                    <a-input v-model:value="createForm.name" />
                </a-form-item>
                <a-form-item
                    label="Email"
                    name="email"
                    :rules="[
                        { required: true, message: 'Please input the email!' },
                    ]"
                >
                    <a-input v-model:value="createForm.email" />
                </a-form-item>
                <a-form-item
                    label="Role"
                    name="role"
                    :rules="[
                        { required: true, message: 'Please input the role!' },
                    ]"
                >
                    <a-select v-model:value="createForm.role">
                        <a-select-option
                            v-for="role in roles"
                            :key="role.id"
                            :value="role.id"
                        >
                            {{ role.name }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </a-form>
        </a-modal>
    </a-layout-content>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Http from "@/services/Http.js";
import {  message, Modal  } from "ant-design-vue";
import { getAllRoles } from "@/services/RoleService";
import { getAllUsers, createUser, editUser ,deleteUser } from "@/services/UserService";

const users = ref([]);
const roles = ref([]);
const editModalVisible = ref(false);
const createModalVisible = ref(false);
const dataReady = ref(false);
const editForm = ref({});
const createForm = ref({});
const editFormRef = ref(null);
const createFormRef = ref(null);
const { confirm } = Modal;

const getUsers = async (updatedPage = null) => {

    try {
        const { data } = await getAllUsers();
        console.log(data);
        users.value = data;
        dataReady.value = true;
    } catch (error) {
        console.error("Error getting users:", error);
        dataReady.value = true;
    }
};

const getRoles = async (updatedPage = null) => {
    if (updatedPage) {
        pagination.current = updatedPage;
    }
    try {
        const { data } = await getAllRoles();
        roles.value = data;
    } catch (error) {
        console.error("Error getting roles:", error);
    }
};

const showCreateModal = () => {
    createForm.value = {};
    createModalVisible.value = true;
};

const handleCreateUser = async () => {
    try {
        await createFormRef.value.validate();
        await createUser(createForm.value);
        createModalVisible.value = false;
        getUsers();
        message.success("User created successfully");
    } catch (error) {
        console.error("Error creating user:", error);
    }
};

const handleEditUser = async () => {
    try {
        await editFormRef.value.validate();
        await editUser(editForm.value);
        editModalVisible.value = false;
        message.success("User updated successfully");
        getUsers();
    } catch (error) {
        console.error("Error updating user:", error);
    }
};

const triggerEditUser = (user) => {
    editForm.value = { ...user };
    editModalVisible.value = true;
};

const triggerDeleteUser = async (id) => {
    try {
        confirm({
            title: "Are you sure you want to delete this user?",
            content: "This action cannot be undone",
            okText: "Yes",
            okType: "danger",
            cancelText: "No",
            onOk() {
                deleteUser(id);
                getUsers();
                message.success("User deleted successfully");
            },
            onCancel() {
                message.info("Delete action cancelled");
            },
        });

    } catch (error) {
        console.error("Error deleting user:", error);
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
    { dataIndex: "name", title: "Name", key: "name" },
    { dataIndex: "email", title: "Email", key: "email" },
    { dataIndex: "roleName", title: "Role", key: "roleName" },
    {
        title: "Operation",
        key: "operation",
        slots: { customRender: "operation" },
    },
];

onMounted(() => {
    getUsers();
    getRoles();
});
</script>

<style scoped>
h1 {
    margin: 0;
}
</style>
