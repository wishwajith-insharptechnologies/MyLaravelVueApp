<template>
    <a-row justify="center">
        <a-col :span="30">
            <h1 class="heading">Checkout</h1>
            <a-form
                v-if="!authenticated"
                :model="form"
                layout="vertical"
                ref="formRef"
                @submit="handleSubmit"
            >
                <!-- Email Field -->
                <a-form-item
                    label="Email"
                    name="email"
                    :rules="[
                        {
                            required: true,
                            message: 'Email is required',
                            trigger: 'blur',
                        },
                        {
                            type: 'email',
                            message: 'Please enter a valid email address',
                            trigger: ['blur', 'change'],
                        },
                    ]"
                >
                    <a-input
                        v-model:value="form.email"
                        type="email"
                        placeholder="Enter email"
                        @input="handleInput"
                    />
                </a-form-item>

                <!-- Password Field -->
                <a-form-item
                    label="Password"
                    name="password"
                    :rules="[
                        {
                            required: true,
                            message: 'Password is required',
                            trigger: 'blur',
                        },
                        {
                            min: 8,
                            message: 'Password must be at least 8 characters',
                            trigger: 'blur',
                        },
                    ]"
                >
                    <a-input
                        v-model:value="form.password"
                        type="password"
                        placeholder="Enter password"
                    />
                </a-form-item>

                <a-form-item
                    v-if="form.isRegisterForm"
                    label="Confirm Password"
                    name="password_confirmation"
                    :rules="[
                        {
                            required: true,
                            message: 'Please confirm your password',
                            trigger: 'blur',
                        },
                        {
                            validator: checkPasswordConfirmation,
                            trigger: 'blur',
                        },
                    ]"
                >
                    <a-input
                        v-model:value="form.password_confirmation"
                        type="password"
                        name="password_confirmation"
                        :disabled="loading"
                    />
                </a-form-item>

                <!-- Checkbox Field -->
                <a-form-item>
                    <a-checkbox v-model:checked="form.isSameEmail">
                        Peaco user email is same as email
                    </a-checkbox>
                </a-form-item>

                <!-- Peaco User Email Field -->
                <a-form-item
                    label="Peaco User Email"
                    name="userEmail"
                    :rules="[
                        {
                            required: true,
                            message: 'Peaco User Email is required',
                            trigger: 'blur',
                        },
                        {
                            type: 'email',
                            message: 'Please enter a valid email address',
                            trigger: ['blur', 'change'],
                        },
                    ]"
                >
                    <a-input
                        v-model:value="form.userEmail"
                        type="email"
                        :disabled="form.isSameEmail"
                    />
                </a-form-item>

                <!-- Submit Button -->
                <!-- <a-form-item>
            <a-button type="primary" html-type="submit" :loading="loading">
              Submit
            </a-button>
          </a-form-item> -->
            </a-form>
        </a-col>
    </a-row>
</template>

<script setup>
import { computed, ref, defineEmits, watch } from "vue";
import { useAuthStore } from "@/stores/modules/auth.js";
import { isUserExists } from "@/services/UserService";

const props = defineProps({
    errors: Object,
});

const store = useAuthStore();
// const errors = ref(null);
const loading = ref(false);
const formRef = ref(null);
const timer = ref(null);
const form = ref({
    name: "",
    email: "",
    password: "",
    userEmail: "",
    isSameEmail: true,
    isRegisterForm: true,
    password_confirmation: "",
});

const emit = defineEmits(["form-change"]);

const isAuthenticated = store.isAuthenticated;

watch(form.value, (newValue, oldValue) => {
    console.log(newValue);
    emit("form-change", form.value);
});

const toggleForm = () => {
    form.value.isRegisterForm = !form.value.isRegisterForm;
};
const handleInput = (e) => {
    clearTimeout(timer.value);

    timer.value = setTimeout(() => {
        alert("searching...");
    }, 2500);
};
const checkPasswordConfirmation = (rule, value, callback) => {
    if (value !== form.value.password) {
        callback(new Error("The passwords do not match"));
    } else {
        callback();
    }
};
</script>
<style scoped>
.container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.heading {
    text-align: left;
    margin-top: 100px;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
}

.form {
    display: flex;
    flex-direction: column;
}

.input-group {
    margin-bottom: 15px;
}

.label {
    margin-bottom: 5px;
    display: block;
    font-weight: bold;
}

.input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

.checkbox {
    margin-right: 10px;
}

.checkbox-label {
    display: inline;
}
</style>
