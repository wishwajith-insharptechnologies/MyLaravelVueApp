<template>

    <p class="text-[20px] font-bold m-0 mb-4">
        User Management
    </p>

    <div >
        <!-- User List Table -->
        <a-table
            :columns="columns"
            :data-source="users"
            :scroll="{ x: 767 }"
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
                    <a-button
                        v-show="!isAuthUserCanDelete(record.id)"
                        type="danger"
                        @click="triggerDeleteUser(record.id)"
                        ><span style="color: red">Delete</span></a-button
                    >
                </span>
            </template>
        </a-table>

        <!-- Edit User Modal -->
        <a-modal
            v-model:visible="editModalVisible"
            title="Edit User"
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
                    <a-input v-model:value="editForm.email" :disabled="true"/>
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
            <template #footer>
                <a-button @click="closeEditModal">Cancel</a-button>
                <a-button
                    type="primary"
                    :loading="loading"
                    :disabled="isSubmit"
                    @click="handleEditUser"
                >
                    Edit User
                </a-button>
            </template>
        </a-modal>

        <!-- Create User Button -->
        <a-button class="mt-6" type="primary" @click="showCreateModal"
            >Create New User</a-button
        >

        <!-- Create User Modal -->
        <a-modal
            v-model:visible="createModalVisible"
            title="Create New User"
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
                        { required: true, message: 'Please select the role!' },
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
            <template #footer>
                <a-button @click="closeCreateModal">Cancel</a-button>
                <a-button
                    type="primary"
                    :loading="loading"
                    :disabled="isSubmit"
                    @click="handleCreateUser"
                >
                    Create User
                </a-button>
            </template>
        </a-modal>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/stores";
import { message, Modal } from "ant-design-vue";
import { getAllRoles } from "@/services/RoleService";
import {
    getAllUsers,
    createUser,
    updateUser,
    deleteUser,
} from "@/services/UserService";

const users = ref([]);
const roles = ref([]);
const editModalVisible = ref(false);
const createModalVisible = ref(false);
const dataReady = ref(false);
const isSubmit = ref(false);
const editForm = ref({});
const createForm = ref({});
const editFormRef = ref(null);
const createFormRef = ref(null);
const { confirm } = Modal;
const authStore = useAuthStore();
const authUser = authStore.getUser;

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

const isAuthUserCanDelete = (id) => {
    console.log(id == authUser.id);
    return id == authUser.id;
};

const handleCreateUser = async () => {
    try {
        isSubmit.value = true;
        await createFormRef.value.validate();
        await createUser(createForm.value);
        createModalVisible.value = false;
        createModalVisible.value = false;
        getUsers();
        isSubmit.value = false;
        message.success("User created successfully");
    } catch (error) {
        isSubmit.value = false;
        console.error("Error creating user:", error);
    }
};

const handleEditUser = async () => {
    try {
        isSubmit.value = true;
        await editFormRef.value.validate();
        await updateUser(editForm.value);
        editModalVisible.value = false;
        message.success("User updated successfully");
        getUsers();
        isSubmit.value = false;
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
            onOk: async () => {
                try {
                    await deleteUser(id);
                    await getUsers();
                    message.success("User deleted successfully");
                } catch (error) {
                    console.error("Error deleting user:", error);
                }
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
