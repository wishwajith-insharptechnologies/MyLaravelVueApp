<template>
       <MainLayout>
           <div id="users" class="bg-white p-3 dark:bg-slate-800 dark:text-gray-200">
             <nav class="mb-6 text-sm font-semibold" aria-label="Breadcrumb">
               <a-breadcrumb>
                 <a-breadcrumb-item v-if="authenticated && roles && (roles.admin || roles.superAdmin)">
                   <router-link to="{ name: 'admin' }">Admin</router-link>
                 </a-breadcrumb-item>
                 <a-breadcrumb-separator>
                   <a-icon type="right" />
                 </a-breadcrumb-separator>
                 <a-breadcrumb-item v-if="authenticated && roles && (roles.admin || roles.superAdmin)">
                   <router-link to="{ name: 'users' }">Products</router-link>
                 </a-breadcrumb-item>
                 <a-breadcrumb-separator>
                   <a-icon type="right" />
                 </a-breadcrumb-separator>
                 <a-breadcrumb-item v-if="authenticated && roles && (roles.admin || roles.superAdmin)">
                   <router-link to="{ name: 'users' }">Create</router-link>
                 </a-breadcrumb-item>
               </a-breadcrumb>
             </nav>
             <ProductForm />
           </div>
       </MainLayout>
  </template>
  <script setup lang="ts">
  import { ref, computed } from 'vue';
  import { useAuthStore } from '@/stores/modules/auth.js';
  import { Button, Breadcrumb, Icon } from 'ant-design-vue';
  import ProductForm from '../../../../components/product/ProductForm.vue';
  import MainLayout from './../../../../components/layout/AdminLayout.vue';

  const store = useAuthStore();

//   const authenticated = computed(() => store.getters['auth/authenticated']);
//   const user = computed(() => store.getters['auth/user']);
//   const roles = computed(() => store.getters['auth/roles']);

  const users = ref([]);
  const pagination = ref({});
  const perPage = ref(10);
  const dataReady = ref(false);
  const showCreateUserForm = ref(false);
  const userEditing = ref(null);
  const creatingNewUser = ref(false);
  const userFormKey = ref(432489);
  const availableRoles = ref([]);
  const rolesDataReady = ref(false);
  const lockJiggled = ref(false);

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

