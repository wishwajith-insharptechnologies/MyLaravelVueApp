<template>
    <a-layout-header style="background: #fff; padding: 24px">
        <h1>Product Management</h1>
    </a-layout-header>
    <a-layout-content style="padding: 24px">
        <!-- Product List Table -->
        <a-table
            :columns="columns"
            :data-source="products"
            :row-key="(record) => record.id"
        >
            <template #projectName="{ text }">
                {{ text }}
            </template>
            <template #projectType="{ text }"> {{ text }}tesr </template>
            <template #environmentType="{ text }">
                {{ text }}
            </template>
            <template #operation="{ record }">
                <span>
                    <a-button
                        type="primary"
                        style="margin: 10px"
                        @click="handelEditProduct(record)"
                        >Edit</a-button
                    >
                    <a-button
                        type="primary"
                        @click="handelDeleteProduct(record.id)"
                        danger
                        ghost
                        >Delete</a-button
                    >
                </span>
            </template>
        </a-table>

        <!-- Create Product Button -->
        <a-button type="primary" @click="handleCreateNewProject"
            >Create New Product</a-button
        >
    </a-layout-content>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { message, Modal } from "ant-design-vue";
import { getProjectTypes, getEnvironmentTypes } from "@/services/Utils.js";
import { useRouter } from "vue-router";
import { getProducts, deleteProject } from "@/services/ProjectService";
const router = useRouter();
const { confirm } = Modal;

const products = ref([]);
const dataReady = ref(false);
const projectTypes = ref([]);
const environmentTypes = ref([]);

onMounted(() => {
    loadProducts();
    fetchEnvironmentTypes();
    fetchProjectTypes();
});

const loadProducts = async () => {
    try {
        const { data } = await getProducts();
        console.log(data);
        products.value = data;
        dataReady.value = true;
    } catch (error) {
        console.error("Error getting products:", error);
        dataReady.value = true;
    }
};

const handelEditProduct = (record) => {
    router.push({ name: "productEditPage", params: { id: record.id } });
};
const handleCreateNewProject = () => {
    router.push({ name: "productAddPage" });
};

const handelDeleteProduct = async (id) => {
    try {
        confirm({
            title: "Are you sure you want to delete this item?",
            content: "This action cannot be undone",
            okText: "Yes",
            okType: "danger",
            cancelText: "No",
            onOk() {
                const response = deleteProject(id);
                loadProducts();
                message.success("Item deleted successfully");
            },
            onCancel() {
                message.info("Delete action cancelled");
            },
        });
    } catch (error) {
        console.error("Error deleting package:", error);
    }
};
//get comman function
const getProjectTypeName = (id) => {
    const type = projectTypes.value.find((type) => type.id === id);
    return type ? type.name : "Unknown";
};

const getEnvironmentTypeName = (id) => {
    const type = environmentTypes.value.find((type) => type.id === id);
    return type ? type.name : "Unknown";
};

const fetchProjectTypes = async () => {
    const data = await getProjectTypes();
    projectTypes.value = data.original;
};

const fetchEnvironmentTypes = async () => {
    const data = await getEnvironmentTypes();
    environmentTypes.value = data.original;
};

const columns = [
    { dataIndex: "projectName", title: "Project Name", key: "projectName" },
    {
        dataIndex: "projectType",
        title: "Project Type",
        key: "projectType",
        customRender: ({ text }) => getProjectTypeName(text),
    },
    {
        dataIndex: "environmentType",
        title: "Environment Type",
        key: "environmentType",
        customRender: ({ text }) => getEnvironmentTypeName(text),
    },
    { dataIndex: "description", title: "Description", key: "description" },
    { dataIndex: "link", title: "Link", key: "link" },
    {
        dataIndex: "secretCode",
        title: "Secret Code",
        key: "secretCode",
        ellipsis: true,
    },
    {
        title: "Operation",
        key: "operation",
        slots: { customRender: "operation" },
    },
];
</script>

<style scoped>
h1 {
    margin: 0;
}
</style>
