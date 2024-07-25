<template>
    <a-card class="overflow-x-auto rounded-lg shadow" style="width: 100%">
      <a-table
        :columns="columns"
        :dataSource="dataReady ? products : []"
        :loading="!dataReady"
        rowKey="id"
        pagination="false"
      >
        <template #bodyCell="{ text, record, column }">
          <ProductTableRow
            v-if="column.dataIndex === 'actions'"
            :product="record"
            :data-ready="dataReady"
            :available-roles="availableRoles"
            :lock-jiggled="lockJiggled"
            @delete-product="deleteProduct"
            @edit-product="editProduct"
            @impersonate-user-triggered="impersonateUserTriggered"
          />
          <span v-else>{{ text }}</span>
        </template>
      </a-table>
      <div v-if="dataReady && products.length >= 1" class="flex flex-col items-center px-3 py-3">
        <Pagination :pagination="pagination" :offset="4" @paginate="getUsers" />
      </div>
    </a-card>
  </template>

<script lang="ts">
import { mapGetters, mapActions } from 'vuex';
import Pagination from '@components/Pagination.vue';
import ProductTableRow from '@components/products/ProductTableRow.vue';

export default {
  name: 'UsersTable',
  components: {
    Pagination,
    ProductTableRow,
  },
  props: {
    dataReady: { type: Boolean, default: false },
    lockJiggled: { type: Boolean, default: false },
    products: {
      type: Array,
      default() {
        return [];
      },
    },
    pagination: {
      type: Object,
      default() {
        return {};
      },
    },
    availableRoles: { type: Array, default: null },
  },
  setup() {
    return {};
  },
  data() {
    return {
      offset: 4,
    };
  },
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated',
      user: 'auth/user',
      roles: 'auth/roles',
    }),
  },
  watch: {},
  created() {},
  mounted() {},
  beforeUnmount() {},
  updated() {},
  methods: {
    ...mapActions({
      popToast: 'toast/popToast',
    }),
    getUsers(data) {
      this.$emit('getUsers', data);
    },
    confirmUnVerifyUser(value) {
      this.$emit('confirmUnVerifyUser', value);
    },
    confirmVerifyUser(value) {
      this.$emit('confirmVerifyUser', value);
    },
    deleteProduct(value) {
      this.$emit('deleteProduct', value);
    },
    editProduct(value) {
      this.$emit('editProduct', value);
    },
    sendUserVerification(value) {
      this.$emit('sendUserVerification', value);
    },
    impersonateUserTriggered(value) {
      this.$emit('impersonateUserTriggered', value);
    },
  },
};
</script>
