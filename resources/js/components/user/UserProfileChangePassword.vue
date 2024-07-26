<template>
    <a-form
        ref="formRef"
        :model="form"
        layout="vertical"
        @submit.prevent="handleSubmit"
    >
            <a-form-item>
                <h2
                    >Change Password</h2
                >
                <a-typography-text
                    >Update personal details here.</a-typography-text
                >
            </a-form-item>
            <a-form-item
                label="Current password"
                name="currentPassword"
                :rules="[
                    {
                        required: true,
                        message: 'Please input your current password',
                        trigger: 'blur',
                    },
                    {
                        min: 8,
                        message: 'Password must be at least 8 characters long',
                        trigger: 'blur',
                    },
                ]"
            >
                <a-input
                    v-model:value="form.currentPassword"
                    type="password"
                    placeholder="Current password"
                />
            </a-form-item>
            <a-form-item
                label="New password"
                name="newPassword"
                :rules="[
                    {
                        required: true,
                        message: 'Please input your new password',
                        trigger: 'blur',
                    },
                    {
                        min: 8,
                        message: 'Password must be at least 8 characters long',
                        trigger: 'blur',
                    },
                ]"
            >
                <a-input
                    v-model:value="form.newPassword"
                    type="password"
                    placeholder="New password"
                />
            </a-form-item>
            <a-form-item
                label="Confirm new password"
                name="confirmNewPassword"
                :rules="[
                    {
                        required: true,
                        message: 'Please confirm your new password',
                        trigger: 'blur',
                    },
                    {
                        min: 8,
                        message: 'Password must be at least 8 characters long',
                        trigger: 'blur',
                    },
                    {
                        validator: confirmNewPasswordValidator,
                        trigger: 'blur',
                    },
                ]"
            >
                <a-input
                    v-model:value="form.confirmNewPassword"
                    type="password"
                    placeholder="Confirm new password"
                />
            </a-form-item>
            <a-form-item>
                <a-button type="primary" @click="handleSubmit" :disabled="isSubmit"
                    >Save changes</a-button
                >
            </a-form-item>
    </a-form>
</template>

<script setup>
import { ref } from "vue";
import { message} from "ant-design-vue";
import { updatePassword } from "@/services/UserService";


const formRef = ref(null);
const isSubmit = ref(false);

const form = ref({
    currentPassword: "",
    newPassword: "",
    confirmNewPassword: "",
});

const confirmNewPasswordValidator = (rule, value) => {
    if (value !== form.value.newPassword) {
        return Promise.reject("The two passwords do not match");
    }
    return Promise.resolve();
};

const handleSubmit = async () => {
    try {
        isSubmit.value = true;
        await formRef.value.validate();
        await updatePassword(form.value);
        message.success("Password updated successfully");
        isSubmit.value = false;
    } catch (errorInfo) {
        isSubmit.value = false;
        console.log("Validation failed:", errorInfo);
    }
};
</script>

<style scoped>
.a-form-item {
    margin-bottom: 16px;
}
</style>
