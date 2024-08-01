<template>
    <a-config-provider
      :theme="{
        token: {
          colorPrimary: '#2D31A6',
          fontFamily: 'Inter' ,
          borderRadius: '8px',
        },
        Components:{


        },

      }"
    >
      <a-layout class="layout ">
        <!-- Header with logo and hamburger button for mobile view -->
        <a-layout-header class=" bg-primary flex items-center justify-between ">

            <img class="h-[40px]" src="../../../Images/peaco 1.png" alt="Logo" />


          <a-button
            class="menu-toggle"
            type="text"

            @click="toggleDrawer"
          >
          <MenuOutlined /> </a-button>
          <a-dropdown class="hidden md:block">
  <a class="text-white cursor-pointer flex items-center">
    My Account
    <DownOutlined class="ml-2" />
  </a>
  <template #overlay>
    <a-menu class="bg-gray-800 text-white">
      <a-menu-item key="0">
        <a href="/profile">
          Profile
        </a>
      </a-menu-item>
      <a-menu-item key="1">
        <a href="/settings">
          Settings
        </a>
      </a-menu-item>
      <a-menu-item key="logout" @click="logout">
        <a >
            Logout
        </a>
      </a-menu-item>

    </a-menu>
  </template>
</a-dropdown>

        </a-layout-header>





        <!-- Drawer for mobile view -->
        <a-drawer

          title="Menu"
          placement="left"
          :visible="drawerVisible"
          @close="toggleDrawer"
        >
          <a-menu
            mode="inline"
            theme="light"
            :default-selected-keys="['admin']"
            :default-open-keys="['products', 'packages']"
            @select="handleMenuSelect"
          >
            <router-link to="/admin_dashboard">
              <a-menu-item key="admin">
                <p class="m-0">Admin</p>
              </a-menu-item>
            </router-link>

            <router-link to="/user">
              <a-menu-item key="users">
                <p class="m-0">Users</p>
              </a-menu-item>
            </router-link>

            <router-link to="/role">
              <a-menu-item key="roles">
                <p class="m-0">Roles</p>
              </a-menu-item>
            </router-link>

            <router-link to="/permission">
              <a-menu-item key="permissions">
                <p class="m-0">Permissions</p>

              </a-menu-item>
            </router-link>

            <a-sub-menu key="products" title="Products">
              <router-link :to="{ name: 'productAddPage' }">
                <a-menu-item key="add-product">
                  <p>Add Product</p>
                </a-menu-item>
              </router-link>

              <router-link :to="{ name: 'listProductPage' }">
                <a-menu-item key="view-products">
                  <p>View Products</p>
                </a-menu-item>
              </router-link>
            </a-sub-menu>

            <a-sub-menu key="packages" title="Packages">
              <router-link :to="{ name: 'packageAddPage' }">
                <a-menu-item key="add-packages">
                  <p>Add Packages</p>
                </a-menu-item>
              </router-link>

              <router-link to="/package">
                <a-menu-item key="view-packages">
                  <p>View Packages</p>
                </a-menu-item>
              </router-link>
            </a-sub-menu>

            <a-sub-menu  key="my-account" title="My Account">
              <router-link to="/profile">
                <a-menu-item key="profile">
                  <p>Profile</p>
                </a-menu-item>
              </router-link>
              <router-link to="/settings">
                <a-menu-item key="settings">
                  <p>Settings</p>
                </a-menu-item>
              </router-link>
              <router-link to="/settings">
                <a-menu-item key="settings">
                  <p>Settings</p>
                </a-menu-item>
              </router-link>


            </a-sub-menu>

            <router-link >
              <a-menu-item key="logout" @click="logout">
                <p class="m-0">Logout </p>

              </a-menu-item>
            </router-link>





          </a-menu>
        </a-drawer>

        <!-- Regular sidebar for desktop view -->
        <a-layout>
          <a-layout-sider width="200"   v-show="!isMobileView">
            <a-menu
              mode="inline"

              :default-selected-keys="['admin']"
              :default-open-keys="['products', 'packages']"
              @select="handleMenuSelect"
            >
              <router-link to="/admin_dashboard">
                <a-menu-item key="admin">
                  <p>Admin</p>
                </a-menu-item>
              </router-link>

              <router-link to="/user">
                <a-menu-item key="users">
                  <p>Users</p>
                </a-menu-item>
              </router-link>

              <router-link to="/role">
                <a-menu-item key="roles">
                  <p>Roles</p>
                </a-menu-item>
              </router-link>

              <router-link to="/permission">
                <a-menu-item key="permissions">
                  <p>Permissions</p>
                </a-menu-item>
              </router-link>

              <a-sub-menu key="products" title="Products">
                <router-link :to="{ name: 'productAddPage' }">
                  <a-menu-item key="add-product">
                    <p>Add Product</p>
                  </a-menu-item>
                </router-link>

                <router-link :to="{ name: 'listProductPage' }">
                  <a-menu-item key="view-products">
                    <p>View Products</p>
                  </a-menu-item>
                </router-link>
              </a-sub-menu>

              <a-sub-menu key="packages" title="Packages">
                <router-link :to="{ name: 'packageAddPage' }">
                  <a-menu-item key="add-packages">
                    <p>Add Packages</p>
                  </a-menu-item>
                </router-link>

                <router-link to="/package">
                  <a-menu-item key="view-packages">
                    <p>View Packages</p>
                  </a-menu-item>
                </router-link>
              </a-sub-menu>

              <a-sub-menu class="md:hidden" key="my-account" title="My Account">
                <router-link to="/profile">
                  <a-menu-item key="profile">
                    <p>Profile</p>
                  </a-menu-item>
                </router-link>
                <router-link to="/settings">
                  <a-menu-item key="settings">
                    <p>Settings</p>
                  </a-menu-item>
                </router-link>
              </a-sub-menu>
            </a-menu>
          </a-layout-sider>

          <a-layout-content class="p-6  ">
            <a-breadcrumb style="margin: 16px 0">
              <a-breadcrumb-item>Home</a-breadcrumb-item>
              <a-breadcrumb-item>{{ currentBreadcrumb }}</a-breadcrumb-item>
            </a-breadcrumb>
            <div class="p-6 content-layout bg-white"  >
              <router-view></router-view>
            </div>
          </a-layout-content>
        </a-layout>
      </a-layout>
      <Footer />
    </a-config-provider>
  </template>

  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue';
  import { Layout, Menu, Breadcrumb, Drawer, Button } from 'ant-design-vue';
  import { MenuOutlined } from '@ant-design/icons-vue';
  import Auth from "@/services/Auth"
  import { useAuthStore } from '@/stores/modules/auth.js';
  import { useRouter } from 'vue-router';
  import NavBar from './NavBar.vue';
  import Footer from './Footer.vue';
  import '../../../css/app.css'
import { Components } from 'ant-design-vue/es/date-picker/generatePicker';

  const authStore = useAuthStore();
  const router = useRouter();

  const selectedKeys = ref(['1']);
  const drawerVisible = ref(false);
  const isMobileView = ref(false);

  const componentMapping = {
    admin: 'Admin Section',
    users: 'Users Section',
    roles: 'Roles Section',
    permissions: 'Permissions Section',
    'add-product': 'Add Product',
    'view-products': 'View Products',
    'add-packages': 'Add Packages',
    'view-packages': 'View Packages',
    profile: 'Profile',
    settings: 'Settings',
  };

  const currentBreadcrumb = ref('Admin');

  const handleMenuSelect = ({ key }) => {
    currentBreadcrumb.value = componentMapping[key] || 'Admin';
  };

  const toggleDrawer = () => {
    drawerVisible.value = !drawerVisible.value;
  };

  const handleResize = () => {
    isMobileView.value = window.innerWidth <= 768;
  };

  const logout = async () => {
      await authStore.signOut();
      await Auth.logout().then(async () => {
        await router.push('/login');
    });
  };

  onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize);
  });

  onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
  });

  </script>

  <style scoped>
  .layout {
    min-height: 100vh;
  }

  .menu-toggle {
    display: none;
  }

  @media (max-width: 768px) {
    .menu-toggle {
      display:  block;

    }

    .ant-layout-sider {
      display: none;
    }
  }





  .layout-content {
    /* padding: 0 50px; */
  }

  .site-layout-content {
    padding: 24px;
    background: #fff;
    min-height: 280px;
  }

  .layout-footer {
    text-align: center;
    background: #f0f2f5;
  }

  .ant-layout-sider {
    background: #fff;
  }


  .ant-layout .ant-layout-header {
    padding-inline: 0px ;
    padding: 16px 24px;
  }







  </style>
