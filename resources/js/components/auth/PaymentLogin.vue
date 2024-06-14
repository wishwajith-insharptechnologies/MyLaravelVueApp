<template>
    <a-row justify="center">
      <a-col :span="8">
        <h1 class="heading">Checkout</h1>
        <a-form v-if="!authenticated" layout="vertical" @submit="handleSubmit">
          <!-- Name Field -->
          <a-form-item
            v-if="form.isRegisterForm"
            label="Name"
            :validate-status="props.errors?.name ? 'error' : ''"
            :help="props.errors?.name ? props.errors.name[0] : ''"
          >
            <a-input
              v-model="form.name"
              type="text"
              name="name"
              :disabled="loading"
            />
          </a-form-item>

          <!-- Email Field -->
          <a-form-item
            label="Email"
            :validate-status="props.errors?.email ? 'error' : ''"
            :help="props.errors?.email ? props.errors.email[0] : ''"
          >
            <a-input
              v-model="form.email"
              type="email"
              name="email"
              :disabled="loading"
            />
          </a-form-item>

          <!-- Password Field -->
          <a-form-item
            label="Password"
            :validate-status="props.errors?.password ? 'error' : ''"
            :help="props.errors?.password ? props.errors.password[0] : ''"
          >
            <a-input
              v-model="form.password"
              type="password"
              placeholder="Must be at least 8 characters"
            />
          </a-form-item>

          <!-- Confirm Password Field -->
          <a-form-item
            v-if="form.isRegisterForm"
            label="Confirm Password"
            :validate-status="props.errors?.password_confirmation ? 'error' : ''"
            :help="props.errors?.password_confirmation ? props.errors.password_confirmation[0] : ''"
          >
            <a-input
              v-model="form.password_confirmation"
              type="password"
              name="password_confirmation"
              :disabled="loading"
            />
          </a-form-item>

          <!-- Toggle Form Link -->
          <a-form-item>
            <a @click="toggleForm">
              {{ form.isRegisterForm ? 'Login' : 'Register' }}
            </a>
          </a-form-item>

          <!-- Checkbox Field -->
          <a-form-item>
            <a-checkbox v-model="form.isSameEmail">
              Peaco user email is same as email
            </a-checkbox>
          </a-form-item>

          <!-- Peaco User Email Field -->
          <a-form-item label="Peaco User Email">
            <a-input
              v-model="form.userEmail"
              type="email"
              :disabled="form.isSameEmail"
            />
          </a-form-item>

          <!-- Submit Button -->
          <a-form-item>
            <a-button type="primary" html-type="submit" :loading="loading">
              Submit
            </a-button>
          </a-form-item>
        </a-form>
      </a-col>
    </a-row>
  </template>

<script setup>
import { computed, ref, defineEmits, watch } from 'vue';
import { useAuthStore } from '@/stores';

const props = defineProps({
  errors: Object,
});

const store = useAuthStore();
// const errors = ref(null);
const loading = ref(false);
const form = ref({
  name: '',
  email: '',
  password: '',
  userEmail: '',
  isSameEmail: true,
  isRegisterForm: false,
  password_confirmation: '',
});

const emit = defineEmits(['form-change']);

const authenticated = computed(() => store.getters['auth/authenticated']);

watch(form.value, (newValue, oldValue) => {
  console.log(newValue);
  emit('form-change', form.value);
});

const toggleForm = () => {
  form.value.isRegisterForm = !form.value.isRegisterForm;
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
