<template>
    <a-form
        :model="form"
        ref="formRef"
        layout="vertical"
        @submit.prevent="handleSubmit"
    >

    <a-form-item>
            <p class="text-[18px] font-bold m-0 mb-1">Personal info</p>
            <p class="text-[14px] m-0 text-gray-600">Update personal details here.</p>
        </a-form-item>
        <div class=" grid grid-cols-1 md:grid-cols-2 gap-4">
        <a-form-item
            label="First name"
            name="name"
            :rules="[
                {
                    required: true,
                    message: 'Please input your first name',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input v-model:value="form.name" placeholder="First name" />
        </a-form-item>
        <a-form-item
            label="Last name"
            name="lastName"
            :rules="[
                {
                    required: false,
                    message: 'Please input your last name',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input v-model:value="form.lastName"  placeholder="Last name" />
        </a-form-item>
    </div>
        <a-form-item
            label="Email"
            name="email"
            :rules="[
                {
                    required: true,
                    type: 'email',
                    message: 'Please input a valid email address',
                    trigger: 'blur',
                },
            ]"
        >
            <a-input v-model:value="form.email" :disabled="true" placeholder="Email" />
        </a-form-item>
        <a-form-item>
            <a-button type="primary" @click="handleSubmit" :disabled="isSubmit"
                >Save changes</a-button
            >
        </a-form-item>
    </a-form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { message, Modal } from "ant-design-vue";
import { getAuthUser, updateUser } from "@/services/UserService";

const form = ref({
    name: "",
    lastName: "",
    email: "",
});
const isSubmit = ref(false);
const formRef = ref();
const handleSubmit = async () => {
    try {
        isSubmit.value = true;
        await formRef.value.validate();
        await updateUser(form.value);
        message.success("Personal info updated successfully");
        isSubmit.value = false;
    } catch (error) {
        isSubmit.value = false;
        console.error("Error updating user:", error);
    }
};

const loadAuthUser = async () => {
    const { data } = await getAuthUser();
    console.log(data);
    form.value = data;
};

onMounted(() => {
    loadAuthUser();
});
</script>
