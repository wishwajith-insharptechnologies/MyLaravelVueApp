<template>
<NavBar/>
    <a-layout class="layout">
        <a-layout>
            <a-layout-sider theme="light" width="200">
                <a-menu mode="inline" theme="light" :default-selected-keys="['admin']"
                    :default-open-keys="['products', 'packages']" @select="handleMenuSelect">

                    <router-link to="/admin_dashboard">
                        <a-menu-item key="admin">
                            <span>Admin</span>
                        </a-menu-item>
                    </router-link>

                    <router-link to="/user">
                        <a-menu-item key="users">
                            <span>Users</span>
                        </a-menu-item>
                    </router-link>

                    <router-link to="/role">
                        <a-menu-item key="roles">
                            <span>Roles</span>
                        </a-menu-item>
                    </router-link>

                    <router-link to="/permission">
                        <a-menu-item key="permissions">
                            <span>Permissions</span>
                        </a-menu-item>
                    </router-link>

                    <a-sub-menu key="products" title="Products">
                        <router-link :to="{ name: 'productAddPage' }">
                            <a-menu-item key="add-product">
                                <span>Add Product</span>
                            </a-menu-item>
                        </router-link>

                        <router-link :to="{ name: 'listProductPage' }">
                            <a-menu-item key="view-products">
                                <span>View Products</span>
                            </a-menu-item>
                        </router-link>

                    </a-sub-menu>
                    <a-sub-menu key="packages" title="Packages">
                        <router-link :to="{ name: 'packageAddPage' }">
                            <a-menu-item key="add-packages">
                                <span>Add Packages</span>
                            </a-menu-item>
                        </router-link>

                        <router-link to="/package">
                            <a-menu-item key="view-packages">
                                <span>View Packages</span>
                            </a-menu-item>
                        </router-link>
                    </a-sub-menu>
                </a-menu>
            </a-layout-sider>

            <a-layout-content style="padding: 0 24px; min-height: 280px;">
                <a-breadcrumb style="margin: 16px 0">
                    <a-breadcrumb-item>Home</a-breadcrumb-item>
                    <a-breadcrumb-item>{{ currentBreadcrumb }}</a-breadcrumb-item>
                </a-breadcrumb>
                <div :style="{ background: '#fff', padding: '24px', minHeight: '280px' }">
                    <router-view></router-view>
                </div>
            </a-layout-content>
        </a-layout>
        </a-layout>
    <Footer/>
</template>

  <script setup>
  import { ref } from 'vue';
//   import 'ant-design-vue/dist/antd.css';
  import { Layout, Menu, Breadcrumb } from 'ant-design-vue';
  import NavBar from "./NavBar.vue";
  import Footer from "./Footer.vue";


  const { Header, Sider, Content } = Layout;
  const { Item, SubMenu } = Menu;

  const selectedKeys = ref(['1']);

  const componentMapping = {
    admin: 'Admin Section',
    users: 'Users Section',
    roles: 'Roles Section',
    permissions: 'Permissions Section',
    'add-product': 'Add Product',
    'view-products': 'View Products',
    'add-packages': 'Add Packages',
    'view-packages': 'View Packages'
  };

  const currentBreadcrumb = ref('Admin');

  const handleMenuSelect = ({ key }) => {
    currentBreadcrumb.value = componentMapping[key] || 'Admin';
  };
  </script>

  <style scoped>
  .layout {
    min-height: 100vh;
  }

  .logo img {
    height: 40px;
    margin-right: 16px;
  }

  .layout-header {
    display: flex;
    align-items: center;
    background-color: #001529;
    padding: 0 50px;
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

  .ant-layout-sider .ant-menu-item {
    padding-left: 24px;
  }
  </style>
