<template>
    <div id="users" class="bg-white p-3 dark:bg-slate-800 dark:text-gray-200">
        <nav class="mb-6 text-sm font-semibold" aria-label="Breadcrumb">
            <a-breadcrumb>
                <a-breadcrumb-item
                    v-if="
                        authenticated &&
                        roles &&
                        (roles.admin || roles.superAdmin)
                    "
                >
                    <router-link to="{ name: 'admin' }">Admin</router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-separator>
                    <a-icon type="right" />
                </a-breadcrumb-separator>
                <a-breadcrumb-item
                    v-if="
                        authenticated &&
                        roles &&
                        (roles.admin || roles.superAdmin)
                    "
                >
                    <router-link to="{ name: 'users' }">Products</router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-separator>
                    <a-icon type="right" />
                </a-breadcrumb-separator>
                <a-breadcrumb-item
                    v-if="
                        authenticated &&
                        roles &&
                        (roles.admin || roles.superAdmin)
                    "
                >
                    <router-link to="{ name: 'users' }">Edit</router-link>
                </a-breadcrumb-item>
            </a-breadcrumb>
        </nav>
        <ProductEditForm :product="product" />
    </div>
</template>
<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "@/stores/modules/auth.js";
import { Button, Breadcrumb, Icon } from "ant-design-vue";
import ProductEditForm from "@/components/product/ProductEditForm.vue";
import { getProduct } from "@/services/ProjectService";

const props = defineProps({
    id: { type: String, required: true },
});

onMounted(() => {
    loadProduct();
    console.log('here');

});

const product = ref(null);

const loadProduct = async () => {
    console.log('load');

    try {
        const { data } = await getProduct(props.id);
        product.value = data;
        console.log(data);
        product.value = data;
    } catch (error) {
        console.error("Error getting packages:", error);
    }
};
</script>

<style scoped>
input:focus,
select:focus,
textarea:focus,
button:focus,
option:focus {
    outline: none !important;
    border: none !important;
}
</style>
<style lang="scss" scoped></style>
