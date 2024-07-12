<template>
        <h1>Package Edit</h1>
        <PackageEditForm
        :package="packageData"
        />
</template>

<script setup>
import { ref, onMounted } from "vue";
import { message, Modal } from "ant-design-vue";
import Http from "@/services/Http.js";
import { useRouter } from "vue-router";
import { getPackage } from "@/services/PackageService";
import PackageEditForm from '@/components/package/PackageEditForm.vue';

const props = defineProps({
    id: { type: String, required: true },
});

const router = useRouter();
const { confirm } = Modal;

const packageData = ref([]);
const dataReady = ref(false);

onMounted(() => {
    loadPackages();
});

const loadPackages = async () => {
    try {
        const data = await getPackage(props.id);
        console.log(data);
        packageData.value = data;
        dataReady.value = true;
    } catch (error) {
        console.error("Error getting packages:", error);
        dataReady.value = true;
    }
};
</script>

<style scoped>
h1 {
    margin: 0;
}
</style>
