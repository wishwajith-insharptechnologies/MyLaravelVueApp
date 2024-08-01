<template>

        <UserProfileInfo />
        <UserProfileChangePassword />
    
</template>

<script setup>
import { ref, onMounted } from "vue";
import UserProfileInfo from "@/components/user/UserProfileInfo.vue";
import UserProfileChangePassword from "@/components/user/UserProfileChangePassword.vue";
import { getAuthUser } from "@/services/UserService";
import { useAuthStore } from "@/stores";
import cloneDeep from "lodash/cloneDeep";

const authStore = useAuthStore();

const authUser = cloneDeep(authStore.getUser);
</script>
