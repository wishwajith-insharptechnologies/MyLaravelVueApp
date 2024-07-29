<!-- src/views/NotFoundPage.vue -->
<template>
    <div class="not-found">
      <h1>404 - Page Not Found</h1>
      <p>The page you're looking for does not exist.</p>
      <router-link to="/">Go to Home</router-link>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  </script>

  <style scoped>
  .not-found {
    text-align: center;
    padding: 50px;
  }

  h1 {
    font-size: 3rem;
    color: #ff0000;
  }

  p {
    font-size: 1.5rem;
  }

  a {
    color: #0000ff;
    text-decoration: underline;
  }
  </style>
