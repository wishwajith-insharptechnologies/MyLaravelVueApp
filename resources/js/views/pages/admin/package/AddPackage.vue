<template>
        <PackageForm></PackageForm>
</template>

  <script setup>
  import { ref, onMounted } from 'vue';
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
