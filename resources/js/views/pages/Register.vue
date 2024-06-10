<template>
    <div class="register-wrapper">
      <div class="register-container">
        <div class="logo">
          <img src="/assets/logo.png" alt="Logo" />
        </div>
        <h2>Create a new account</h2>
        <p>Welcome! Please fill in your details to sign up.</p>
        <a-form @submit.prevent="handleSubmit" layout="vertical">
          <a-form-item>
            <a-input
              v-model:value="name"
              placeholder="Enter your name"
              size="large"
              type="text"
            />
          </a-form-item>
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
            <a-input-password
              v-model:value="confirmPassword"
              placeholder="Confirm Password"
              size="large"
            />
          </a-form-item>
          <a-form-item>
            <a-button type="primary" html-type="submit" block>
              Sign up
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

  const name = ref('');
  const email = ref('');
  const password = ref('');
  const confirmPassword = ref('');
  const router = useRouter();
  const authStore = useAuthStore();

  const handleSubmit = async () => {
    if (!name.value || !email.value || !password.value || !confirmPassword.value) {
      message.error('Please fill in all fields');
      return;
    }

    if (password.value !== confirmPassword.value) {
      message.error('Passwords do not match');
      return;
    }

    try {
      authStore.register({ name: name.value, email: email.value, password: password.value, password_confirmation: confirmPassword.value });
      message.success('Registration successful');
      router.push('/dashboard'); // Redirect after successful registration
    } catch (error) {
        console.log(error);
      message.error('Registration failed');
    }
  };
  </script>

  <style scoped>
  .register-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f2f5;
  }

  .register-container {
    background: none;
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
