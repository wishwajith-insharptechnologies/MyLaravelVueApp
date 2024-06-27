<template>
  <div>
    <h1><b>Limitation Form</b></h1>
    <form @submit.prevent="submitForm">
      <div
        v-for="limitation in sortedLimitations"
        :key="limitation.name"
        :class="['form-field', { required: limitation.required }]"
        :style="getContainerStyle(limitation)"
      >
        <label>{{ limitation.name }}</label>
        <template v-if="limitation.required">
          <!-- Scenario 1: Show text box for required limitations -->
          <label class="text-red-500 ml-8">*Required</label>

          <input
            v-model="limitation.value"
            type="number"
            class="form-input"
            :max="getMaxValue(limitation)"
            :min="getMinValue(limitation)"
            @input="emitValue(limitation)"
          />
          <!-- Display max value error message -->
          <p
            v-if="validationErrors[limitation.featureId]?.maxValue"
            class="text-red-500 error"
          >
            {{ validationErrors[limitation.featureId]?.maxValue }}
          </p>
          <!-- Display min value error message -->
          <p
            v-if="validationErrors[limitation.featureId]?.minValue"
            class="text-red-500 error"
          >
            {{ validationErrors[limitation.featureId]?.minValue }}
          </p>
        </template>

        <template v-else-if="limitation.type === 'int'">
          <!-- Scenario 2: Show checkbox for non-required limitations -->
          <input
            v-model="limitation.IsEnabled"
            type="checkbox"
            class="form-switch"
          />
          <span class="form-switch-label">Yes</span>
          <input
            v-if="limitation.IsEnabled"
            v-model="limitation.value"
            type="number"
            class="form-input"
            @input="emitValue(limitation)"
          />
        </template>

        <template v-else-if="limitation.type === 'bool'">
          <!-- Scenario 3: Show checkbox for boolean limitations -->
          <input
            v-model="limitation.value"
            type="checkbox"
            class="form-switch"
            @input="emitValue"
          />
          <span class="form-switch-label">Yes</span>
        </template>
        <template v-else-if="limitation.type === 'upload'">
          <!-- Scenario 2: Show checkbox for non-required limitations -->
          <input
            v-model="limitation.IsEnabled"
            type="checkbox"
            class="form-switch"
          />
          <span class="form-switch-label">Yes {{ limitation.IsEnabled }}</span>
          <div v-if="limitation.IsEnabled">
            <span>File size in {{ getFormatType(limitation.parameters) }}</span>
            <input
              v-model="limitation.value"
              type="number"
              class="form-input"
              :max="getMaxValue(limitation)"
              :min="getMinValue(limitation)"
              @input="emitValue(limitation)"
            />
            <!-- Display max value error message -->
            <p
              v-if="validationErrors[limitation.featureId]?.maxValue"
              class="text-red-500 error"
            >
              {{ validationErrors[limitation.featureId]?.maxValue }}
            </p>
            <!-- Display min value error message -->
            <p
              v-if="validationErrors[limitation.featureId]?.minValue"
              class="text-red-500 error"
            >
              {{ validationErrors[limitation.featureId]?.minValue }}
            </p>
          </div>
        </template>
        <template v-else>
          <!-- Default: Show text box for other types -->
          <input
            v-model="limitation.value"
            type="number"
            class="form-input"
            :max="getMaxValue(limitation)"
            :min="getMinValue(limitation)"
            @input="emitValue(limitation)"
          />
        </template>
      </div>
      <!-- <button type="submit" class="form-submit">Submit</button> -->
    </form>
  </div>
</template>
<script setup>
import { defineProps, ref, watch } from 'vue';

const props = defineProps({
  limitations: { type: Array, default: () => [] },
});
const emit = defineEmits(['update:value', 'update:errors']);
const sortedLimitations = ref([]);
const validationErrors = ref([]);
const isLimitationError = ref([]);

watch(
  () => props.limitations,
  (newLimitations, oldLimitations) => {
    sortedLimitations.value = newLimitations.map((limitation) => ({
      ...limitation,
      value: limitation.value === undefined ? null : limitation.value,
      IsEnabled:
        limitation.IsEnabled !== undefined
          ? limitation.IsEnabled
          : limitation.required,
    }));
  },
);

const emitValue = (limitation) => {
  emit('update:value', sortedLimitations);
  emit('update:errors', isLimitationError);
  validateValue(limitation);
};

const getArrayOptions = (parameters) => {
  // Extracts the array options from the parameters
  return parameters.map((param) => param.value);
};

const getContainerStyle = (limitation) => {
  if (limitation.parentId) {
    return {
      marginLeft: '100px',
    };
  }
  return {};
};

const getFormatType = (parameters) => {
  const item = parameters.find((item) => item.name === 'Format');
  return item ? item.value : '';
};

const getMaxValue = (limitation) => {
  const maxValueParam = limitation.parameters.find(
    (param) => param.name === 'MaxValue',
  );

  if (maxValueParam) {
    return parseInt(
      maxValueParam.value == null ? Infinity : maxValueParam.value,
    );
  }
  return Infinity;
};

const getMinValue = (limitation) => {
  const minValueParam = limitation.parameters.find(
    (param) => param.name === 'MinValue',
  );
  if (minValueParam) {
    return parseInt(minValueParam.value == null ? 1 : minValueParam.value);
  }
  return 1;
};

const validateValue = (limitation) => {
  const maxValue = getMaxValue(limitation);
  const minValue = getMinValue(limitation);
  const errors = {};
  console.log(maxValue);

  if (typeof maxValue === 'number' && limitation.value > maxValue) {
    console.log('limitation.value > minValue');
    errors.maxValue = `Value for ${limitation.name} exceeds the maximum limit.`;
  }
  if (typeof minValue === 'number' && limitation.value < minValue) {
    console.log('limitation.value < minValue');

    errors.minValue = `Value for ${limitation.name} is below the minimum limit.`;
  }
  isLimitationError.value = Object.keys(errors).length > 0;
  validationErrors.value[limitation.featureId] = errors;
};
</script>

<style scoped>
.form-field {
  width: 50%;
  margin-bottom: 1rem;
}

.form-switch {
  width: 1.5rem;
  height: 1.5rem;
  margin-right: 0.5rem;
}

.form-switch-label {
  margin-left: 0.5rem;
}

.form-select {
  width: 100%;
  padding: 0.5rem;
}

.form-input {
  width: 100%;
  padding: 0.5rem;
}

.form-submit {
  margin-top: 1rem;
}
</style>
