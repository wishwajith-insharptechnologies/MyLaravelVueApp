// resources/js/router/routes.js

import Login from '/resources/js/views/pages/Login.vue';
import Register from '/resources/js/views/pages/Register.vue';
import Dashboard from '/resources/js/views/pages/Dashboard.vue';
import AdminDashboard from '/resources/js/views/pages/AdminDashboard.vue';
import UserPage from '/resources/js/views/pages/admin/UserPage.vue';
import RolePage from '/resources/js/views/pages/admin/RolePage.vue';
import PermissionPage from '/resources/js/views/pages/admin/PermissionPage.vue';
import PackagePage from '/resources/js/views/pages/admin/PackagePage.vue';
import AddPackage from '../views/pages/admin/package/AddPackage.vue';
import AddProduct from '../views/pages/admin/product/AddProduct.vue';
import ListProduct from '../views/pages/admin/product/ProductList.vue';
import PaymentPage from '../views/pages/Payment.vue';
// import UserPage from '/resources/js/views/pages/UserPage.vue';

const routes = [
  { path: '/', name: 'Home', component: Dashboard },
  { path: '/login', name: 'Login', component: Login, meta: { middleware: "guest", title: `Login`} },
  { path: '/register', name: 'Register', component: Register },
  { path: '/dashboard', name: 'Dashboard', component: Dashboard },
  { path: '/admin_dashboard', name: 'AdminDashboard', component: AdminDashboard },
  { path: '/user', name: 'UserPage', component: UserPage},
  { path: '/role', name: 'RolePage', component: RolePage},
  { path: '/permission', name: 'PermissionPage', component: PermissionPage},
  { path: '/package', name: 'PackagePage', component: PackagePage},
  { path: '/package/create', name: 'PackageAddPage', component: AddPackage},
  { path: '/product/create', name: 'ProductAddPage', component: AddProduct},
  { path: '/product/list', name: 'ListProductPage', component: ListProduct},

  {path: '/payment/:id',
    props: (route) => ({
      id: route.params.id,
    }),
    component: PaymentPage,
    name: 'payment',
  },

];

export default routes;
