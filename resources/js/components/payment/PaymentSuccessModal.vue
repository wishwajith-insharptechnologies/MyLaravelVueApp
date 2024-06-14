<template>
  <div
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 z-50"
  >
    <div
      class="relative bg-white p-8 w-full h-full flex flex-col items-center justify-center"
    >
      <button
        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
        @click="closeModal"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
      <div class="text-green-500 mb-4">
        <svg
          class="w-12 h-12 mx-auto"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M5 13l4 4L19 7"
          ></path>
        </svg>
      </div>
      <p class="text-lg font-bold text-green-600 mb-2">
        Your order was {{ paymentStatus }}
      </p>
      <p class="text-xl font-bold text-purple-600 mb-4">
        Thanks for your purchase!
      </p>
      <p class="text-gray-600">
        You'll receive an email confirming your order details
      </p>
      <button
        class="mt-6 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700"
        @click="closeModal"
      >
        Close
      </button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
  isOpen: Boolean,
  paymentIntent: Object,
});

const emit = defineEmits(['close']);

const paymentStatus = computed(() => {
  let status = '';
  console.log(props.paymentIntent.paymentIntent.status);
  switch (props.paymentIntent.paymentIntent.status) {
    case 'succeeded':
      status = 'Successful!';
      break;
    case 'processing':
      status = 'Processing.';
      break;
    case 'requires_payment_method':
      status = 'was not successful, please try again.';
      break;
    default:
      status = 'Something went wrong.';
      break;
  }
  return status;
});

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
/* Add any additional styling here if needed */
</style>
