<template>
    <header class="navbar">
      <div class="navbar__logo">
        <img src="path-to-logo.png" alt="Peaco HRM" />
      </div>
      <a-dropdown>
        <div class="navbar__account" @click="toggleDropdown">
          <span>My Account</span>
          <i class="arrow-down"></i>
        </div>
        <template #overlay>
          <a-menu>
            <a-menu-item key="profile">
              <router-link to="/profile">Profile</router-link>
            </a-menu-item>
            <a-menu-item key="logout" @click="logout">
              Logout
            </a-menu-item>
          </a-menu>
        </template>
      </a-dropdown>
    </header>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { Menu, Dropdown } from 'ant-design-vue';
  import Auth from "@/services/Auth"
  import { useRouter } from 'vue-router';
  import { useAuthStore } from '@/stores/modules/auth.js';

  const showDropdown = ref(false);
  const router = useRouter();
  const authStore = useAuthStore();


  const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value;
  };


  const logout = async () => {
      await authStore.signOut();
      await Auth.logout().then(async () => {
        await router.push('/login');
    });
  };
  </script>

  <style scoped>
  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background-color: #fff;
  }

  .navbar__logo img {
    height: 40px;
  }

  .navbar__account {
    display: flex;
    align-items: center;
    color: #3c007b;
    cursor: pointer;
  }

  .arrow-down {
    border: solid #3c007b;
    border-width: 0 2px 2px 0;
    display: inline-block;
    padding: 3px;
    margin-left: 5px;
    transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
  }
  </style>
