<template>
    <div class="login-wrapper">
      <div class="login-container">
        <div class="logo">
          <img src="/assets/logo.png" alt="Logo" />
        </div>
        <h2>Log in to your account</h2>
        <p>Welcome! Please enter your details.</p>
        <a-form @submit.prevent="handleSubmit" layout="vertical">
          <a-form-item>
            <a-input
              v-model:value="email"
              placeholder="Enter your email"
              size="large"
              type="email"
            />
          </a-form-item>
          <a-form-item>
            <a-input-password
              v-model:value="password"
              placeholder="Password"
              size="large"
            />
          </a-form-item>
          <a-form-item>
            <a-button type="primary" html-type="submit" block>
              Sign in
            </a-button>
          </a-form-item>
        </a-form>
      </div>
    </div>
  </template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { message } from 'ant-design-vue';
import { useAuthStore } from '@/stores';

const email = ref('');
const password = ref('');
const router = useRouter();
const authStore = useAuthStore();

const handleSubmit = async () => {
  if (!email.value || !password.value) {
    message.error('Please fill in all fields');
    return;
  }

  try {
    await authStore.login({ email: email.value, password: password.value });
    message.success('Login successful');
    router.push('/dashboard'); // Redirect after successful login
  } catch (error) {
    message.error('Login failed');
  }
};
</script>

  <style scoped>
  .login-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f2f5;
  }

  .login-container {
    background: none; /* Remove background */
    padding: 40px;
    text-align: center;
    max-width: 400px;
    width: 100%;
  }

  .logo {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
  }

  .logo img {
    height: 40px;
  }

  h2 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  p {
    margin-bottom: 20px;
    color: #888;
  }

  .ant-form-item {
    margin-bottom: 16px;
  }

  .ant-btn-primary {
    background-color: #722ed1;
    border-color: #722ed1;
  }
  </style>
