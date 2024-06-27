<template>
    <MainLayout>
        <PackageForm></PackageForm>
    </MainLayout>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
//   import http from '../../../services/axios';
  import MainLayout from './../../../../components/layout/AdminLayout.vue';
  import PackageForm from '../../../../components/package/PackageForm.vue';

  onMounted(() => {
    // getPackages();
    console.log("test");
  });
  </script>

  <style scoped>
  h1 {
    margin: 0;
  }
  </style>
