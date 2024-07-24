<template>
    <a-card>
        <a-typography-title level="3">Profile</a-typography-title>
        <UserProfileInfo />
        <UserProfileChangePassword />
    </a-card>
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
