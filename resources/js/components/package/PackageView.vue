<template>
    <a-row style="background-color: #F5F8FF;" justify="center">
      <a-col :span="10">
        <a-button type="text" class="back-button" icon>
          <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            />
          </svg>
        </a-button>
        <h1 class="title">Order Summary</h1>
        <p class="item-count">1 item(s)</p>
        <div class="details">
          <div class="flex-between">
            <span class="plan-name mb-5">{{ props.package.title }}</span>
            <span class="plan-price"><b>${{ props.package.price }}</b></span>
          </div>
          <span class="plan-name mb-5">Package details</span>
          <ul class="package-details mb-2">
            <li
              v-for="(feature, key) in props.package.limitation.limitation"
              :key="key"
            >
              <a-list-item v-if="feature.value">
                <a-icon type="check" class="check-icon" />
                {{ limitationValue(feature.value) + ' ' + feature.name }}
              </a-list-item>
            </li>
          </ul>
          <div class="flex-between">
            <span>{{ props.package.title }}</span>
            <span>${{ props.package.price }}</span>
          </div>
          <div class="flex-between">
            <span>Discount</span>
            <span>%{{ props.package.discount }}</span>
          </div>
          <div class="flex-between total">
            <span>Total</span>
            <span>${{ totalPrice }}</span>
          </div>
          <div class="flex-between total">
            <span>Total Billed Annually</span>
            <span>${{ totalPrice }}</span>
          </div>
          <p class="next-charge">Next charge date {{ validityEndDate }}</p>
        </div>
        <p class="note">
          Your purchase may be subject to local taxes based on the billing
          information you provide. The final charge may be different than the
          amount shown here and will be displayed on your invoice.
        </p>
        <div class="logo-container">
          <img
            :src="getImageUrl(props.package.images)"
            alt="PeacoHRM Logo"
            class="logo"
          />
        </div>
        <p class="footer">© 2024 PeacoHRM. All rights reserved.</p>
      </a-col>
    </a-row>
  </template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps(['package']);

const totalPrice = computed(() => {
  const price = parseFloat(props.package.price);
  const discountPercentage = parseFloat(props.package.discount);
  const discount = price * (discountPercentage / 100);
  return (price - discount).toFixed(2);
});
const validityEndDate = computed(() => {
  const currentDate = new Date();
  const validityDays = props.package.validity;
  const endDate = new Date(
    currentDate.getTime() + validityDays * 24 * 60 * 60 * 1000,
  );
  // Convert endDate to a human-readable format (e.g., "May 18, 2024")
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return endDate.toLocaleDateString(undefined, options);
});

const limitationValue = (value) => {
  if (value === true) {
    return '';
  }
  return value;
};
const getImageUrl = (imagePath) => {
    const trimmedImagePath = imagePath.startsWith('/') ? imagePath.slice(1) : imagePath;
  return `${import.meta.env.VITE_MEDIA_URL}${trimmedImagePath}`;
};
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f8fafc;
  padding: 2rem;
}

.content {
  width: 100%;
  max-width: 28rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.back-button {
  color: #6b7280;
  margin-bottom: 1rem;
}

.icon {
  height: 1.5rem;
  width: 1.5rem;
}

.title {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

.item-count {
  color: #4b5563;
  margin-bottom: 1.5rem;
}

.details {
  border-top: 1px solid #e5e7eb;
  padding-top: 1rem;
  margin-bottom: 1.5rem;
}

.flex-between {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

.plan-name {
  font-weight: 600;
}

.plan-price {
  color: #374151;
}

.package-details {
  margin-top: 1px;
  list-style: none;
  padding-left: 0;
}

.package-details li {
  display: flex;
  align-items: center;
  color: #374151;
  margin-bottom: 0.5rem;
}

.check-icon {
  color: #7c3aed;
  margin-right: 0.5rem;
}

.total {
  font-weight: 600;
  color: #111827;
  margin-bottom: 1.5rem;
}

.next-charge {
  color: #4b5563;
  margin-bottom: 1.5rem;
}

.note {
  color: #6b7280;
  font-size: 0.875rem;
  margin-bottom: 1.5rem;
}

.logo-container {
  display: flex;
  justify-content: left;
}

.logo {
  height: 2rem;
}

.footer {
  color: #6b7280;
  font-size: 0.875rem;
  text-align: center;
  margin-top: 1rem;
}
</style>
