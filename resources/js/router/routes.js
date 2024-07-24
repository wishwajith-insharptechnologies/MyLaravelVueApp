// resources/js/router/routes.js

import Login from "/resources/js/views/pages/guest/auth/Login.vue";
import Register from "/resources/js/views/pages/guest/auth/Register.vue";
import ClientDashboard from "/resources/js/views/pages/client/ClientDashboard.vue";
import AdminDashboard from "/resources/js/views/pages/admin/AdminDashboard.vue";
import UserPage from "/resources/js/views/pages/admin/user/UserPage.vue";
import RolePage from "/resources/js/views/pages/admin/role/RolePage.vue";
import PermissionPage from "/resources/js/views/pages/admin/permission/PermissionPage.vue";
import PackagePage from "/resources/js/views/pages/admin/package/PackagePage.vue";
import AddPackage from "../views/pages/admin/package/AddPackage.vue";
import EditPackage from "../views/pages/admin/package/PackageEditPage.vue";
import AddProduct from "../views/pages/admin/product/AddProduct.vue";
import ProductPage from "../views/pages/admin/product/ProductPage.vue";
import EditProduct from "../views/pages/admin/product/EditProduct.vue";
// import ListProduct from "../views/pages/admin/product/ProductList.vue";
import PaymentPage from "../views/pages/guest/payment/Payment.vue";
import AdminLayout from "../components/layout/AdminLayout.vue";
import ProfilePage from "../views/pages/guest/user/ProfilePage.vue";

const routes = [
    {
        path: "/",
        component: AdminLayout,
        children: [
            { path: "/user", name: "userPage", component: UserPage },
            { path: "/role", name: "rolePage", component: RolePage },
            {
                path: "/permission",
                name: "permissionPage",
                component: PermissionPage,
            },
            { path: "/package", name: "packagePage", component: PackagePage },
            {
                path: "/package/create",
                name: "packageAddPage",
                component: AddPackage,
            },
            {
                path: "/profile",
                name: "ProfilePage",
                component: ProfilePage,
            },
            {
                path: "/package/edit/:id",
                props: (route) => ({
                    id: route.params.id,
                }),
                component: EditPackage,
                name: "packageEditPage",
            },
            {
                path: "/product/create",
                name: "productAddPage",
                component: AddProduct,
            },
            {
                path: "/product/list",
                name: "listProductPage",
                component: ProductPage,
            },
            {
                path: "/product/edit/:id",
                props: (route) => ({
                    id: route.params.id,
                }),
                component: EditProduct,
                name: "productEditPage",
            },
        ],
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: { middleware: "guest", title: `Login` },
    },
    { path: "/register", name: "register", component: Register },
    { path: "/dashboard", name: "dashboard", component: ClientDashboard },
    {
        path: "/admin_dashboard",
        name: "adminDashboard",
        component: AdminDashboard,
    },

    {
        path: "/payment/:id",
        props: (route) => ({
            id: route.params.id,
        }),
        component: PaymentPage,
        name: "payment",
    },
];

export default routes;
