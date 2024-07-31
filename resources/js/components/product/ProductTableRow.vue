<template>
    <a-tr class="bg-gray-100 text-gray-900 dark:bg-slate-700 dark:text-gray-100">
      <a-td class="border-b border-gray-300 px-5 py-5 text-sm dark:border-gray-500">
        <div class="flex items-center">
          <div class="h-10 w-10 flex-shrink-0">
            <img
              class="h-full w-full rounded-full"
              :src="'images/product/' + product.image"
              :alt="product.name"
            />
          </div>
          <div class="ml-3">
            <p class="whitespace-nowrap text-xs">
              {{ product.projectName }}
            </p>
          </div>
        </div>
      </a-td>
      <a-td class="border-b border-gray-300 px-5 py-5 text-xs dark:border-gray-500">
        <div class="flex items-center">
          {{ getNameById(product.projectType) }}
        </div>
      </a-td>
      <a-td
        class="whitespace-nowrap border-b border-gray-300 px-5 py-5 text-xs dark:border-gray-500"
      >
        {{ product.description }}
      </a-td>
      <a-td class="border-b border-gray-300 px-5 py-5 text-xs dark:border-gray-500">
        <p v-if="product.createdAt" class="whitespace-nowrap">
          {{ parseDisplayDate(product.createdAt) }}
        </p>
      </a-td>
      <a-td class="border-b border-gray-300 px-5 py-5 text-xs dark:border-gray-500">
        <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
          <div class="whitespace-nowrap">
            <div
              class="inline rounded px-1 py-0"
              :class="
                product.email_verified_at
                  ? 'bg-green-700 text-gray-100'
                  : 'bg-red-700 text-gray-100'
              "
            >
              {{ product.email_verified_at ? 'Active' : 'Unverified' }}
            </div>
          </div>
          <div v-if="product.email_verified_at" class="inset-0 py-1">
            {{ parseDisplayDate(product.email_verified_at) }}
          </div>
        </span>
      </a-td>
      <a-td
        class="whitespace-nowrap border-b border-gray-300 px-5 py-5 text-sm dark:border-gray-500"
      >
        <a-button
          type="primary"
          :loading="!dataReady"
          class="mr-2 px-1 py-1 text-sm"
          @click="triggerEditProduct"
        >
          <template #icon>
            <a-icon type="edit" v-if="dataReady" />
            <a-icon type="loading" v-if="!dataReady" />
          </template>
          Edit Product
        </a-button>
        <a-button
          type="danger"
          :loading="!dataReady"
          class="mr-2 px-1 py-1 text-sm"
          @click="triggerDeleteProduct"
        >
          <template #icon>
            <a-icon type="delete" v-if="dataReady" />
            <a-icon type="loading" v-if="!dataReady" />
          </template>
          Delete Product
        </a-button>
      </a-td>
    </a-tr>
  </template>

  <script setup lang="ts">
  import { ref, watch } from 'vue';
  import moment from 'moment';
  import { Button, Icon } from 'ant-design-vue';

  // Props
  const props = defineProps({
    product: { type: Object, required: true },
    dataReady: { type: Boolean, default: false },
    availableRoles: { type: Array, default: null },
    lockJiggled: { type: Boolean, default: false },
  });

  // Emit events
  const emit = defineEmits(['editProduct', 'deleteProduct']);

  // State
  const projectTypes = [
    { id: 1, name: 'web' },
    { id: 2, name: 'mobile' },
    { id: 3, name: 'SASS' },
    { id: 4, name: 'solution' },
  ];

  watch(() => props.lockJiggled, (newVal) => {
    if (newVal) {
      locked.value = true;
    }
  });

  const getNameById = (id) => {
    const project = projectTypes.find((project) => project.id === id);
    return project ? project.name : null;
  };

  const parseDisplayDate = (date) => {
    return moment(date).format('MMM Do YYYY, h:mma');
  };

  const triggerEditProduct = () => {
    emit('editProduct', props.product);
  };

  const triggerDeleteProduct = () => {
    emit('deleteProduct', props.product);
  };
  </script>

  <style scoped>
  .ant-table-tbody > tr > td {
    border-bottom: 1px solid #f0f0f0;
  }
  </style>
