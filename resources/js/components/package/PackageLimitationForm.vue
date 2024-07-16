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
            <label> <span v-if="limitation.required" style="color: red" >* </span>{{ limitation.name }} </label>
                <template v-if="limitation.required">
                    <!-- Scenario 1: Show text box for required limitations -->

                    <a-form-item
                        :name="limitation.name"
                    >
                        <a-input-number
                            v-model:value="limitation.value"
                            @input="emitValue(limitation)"
                            @focus="emitValue(limitation)"
                            class="form-input"
                        />
                    </a-form-item>
                    <!-- Display required value error message -->
                    <a-paragraph
                        style="color: red"
                        v-if="validationErrors[limitation.featureId]?.required"
                    >
                        {{ validationErrors[limitation.featureId]?.required }}
                    </a-paragraph>
                    <!-- Display max value error message -->
                    <a-paragraph
                        style="color: red"
                        v-if="validationErrors[limitation.featureId]?.maxValue"
                    >
                        {{ validationErrors[limitation.featureId]?.maxValue }}
                    </a-paragraph>
                    <!-- Display min value error message -->
                    <a-paragraph
                        style="color: red"
                        v-if="validationErrors[limitation.featureId]?.minValue"
                    >
                        {{ validationErrors[limitation.featureId]?.minValue }}
                    </a-paragraph>
                </template>

                <template v-else-if="limitation.type === 'int'">
                    <!-- Scenario 2: Show checkbox for non-required limitations -->
                    <a-checkbox
                        v-model:checked="limitation.IsEnabled"
                        class="form-switch"
                    />
                    <span class="form-switch-label">{{ isClicked(limitation.IsEnabled) }}</span>
                    <span v-if="limitation.IsEnabled" style="color: red" >* </span>
                    <a-input-number
                        v-if="limitation.IsEnabled"
                        v-model:value="limitation.value"
                        @input="emitValue(limitation)"
                        class="form-input"
                    />
                </template>

                <template v-else-if="limitation.type === 'bool'">
                    <!-- Scenario 3: Show checkbox for boolean limitations -->
                    <a-checkbox
                        v-model:checked="limitation.value"
                        @change="emitValue"
                        class="form-switch"
                    />
                    <span class="form-switch-label">{{ isClicked(limitation.value) }}</span>
                </template>
                <template v-else-if="limitation.type === 'upload'">
                    <!-- Scenario 2: Show checkbox for non-required limitations -->
                    <a-checkbox
                        v-model:checked="limitation.IsEnabled"
                        class="form-switch"
                    />
                    <span class="form-switch-label"
                        >Yes </span
                    >
                    <div v-if="limitation.IsEnabled">
                        <span
                            ><span  style="color: red" >* </span>File size in
                            {{ getFormatType(limitation.parameters) }}</span
                        >
                        <a-input-number
                            v-model:value="limitation.value"
                            :max="getMaxValue(limitation)"
                            :min="getMinValue(limitation)"
                            @change="emitValue(limitation)"
                            class="form-input"
                        />

                        <!-- Display max value error message -->
                        <a-paragraph
                            style="color: red"
                            v-if="
                                validationErrors[limitation.featureId]?.maxValue
                            "
                        >
                            {{
                                validationErrors[limitation.featureId]?.maxValue
                            }}
                        </a-paragraph>
                        <!-- Display min value error message -->
                        <a-paragraph
                            style="color: red"
                            v-if="
                                validationErrors[limitation.featureId]?.minValue
                            "
                        >
                            {{
                                validationErrors[limitation.featureId]?.minValue
                            }}
                        </a-paragraph>
                    </div>
                </template>
                <template v-else>
                    <!-- Default: Show text box for other types -->
                    <a-input-number
                        v-model:value="limitation.value"
                        :max="getMaxValue(limitation)"
                        :min="getMinValue(limitation)"
                        @change="emitValue(limitation)"
                        class="form-input"
                    />
                </template>
            </div>
            <!-- <button type="submit" class="form-submit">Submit</button> -->
        </form>
    </div>
</template>
<script setup>
import { defineProps, ref, watch } from "vue";

const props = defineProps({
    limitations: { type: Array, default: () => [] },
});
const emit = defineEmits(["update:value", "update:errors"]);
const sortedLimitations = ref([]);
const validationErrors = ref([]);
const isLimitationError = ref([]);

watch(
    () => props.limitations,
    (newLimitations, oldLimitations) => {
        sortedLimitations.value = newLimitations.map((limitation) => ({
            ...limitation,
            value: setLimitationDefaultValue(limitation),
            IsEnabled:
                limitation.IsEnabled !== undefined
                    ? limitation.IsEnabled
                    : limitation.required,
        }));
    }
);

const emitValue = (limitation) => {
    emit("update:value", sortedLimitations);
    emit("update:errors", isLimitationError);
    validateValue(limitation);
};

const getArrayOptions = (parameters) => {
    // Extracts the array options from the parameters
    return parameters.map((param) => param.value);
};

const setLimitationDefaultValue = (limitation) => {
  return limitation.type === "bool" ? (limitation.value? limitation.value :false) : (limitation.value === undefined ? null : limitation.value);
};
const isClicked = ((bool) => {
  return bool ? 'Yes' : 'No';
});

const phoneValidator = async (_, value) => {
    console.log(value);
};

const getContainerStyle = (limitation) => {
    if (limitation.parentId) {
        return {
            marginLeft: "100px",
        };
    }
    return {};
};

const getFormatType = (parameters) => {
    const item = parameters.find((item) => item.name === "Format");
    return item ? item.value : "";
};

const getMaxValue = (limitation) => {
    const maxValueParam = limitation.parameters.find(
        (param) => param.name === "MaxValue"
    );

    if (maxValueParam) {
        return parseInt(
            maxValueParam.value == null ? Infinity : maxValueParam.value
        );
    }
    return Infinity;
};

const getMinValue = (limitation) => {
    const minValueParam = limitation.parameters.find(
        (param) => param.name === "MinValue"
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
    console.log(limitation.value);

    if(typeof maxValue === "number" && limitation.value == null) {
        errors.required = `${limitation.name} is required`;
    } else{
        if (typeof maxValue === "number" && limitation.value > maxValue) {
            console.log("limitation.value > minValue");
            errors.maxValue = `Value for ${limitation.name} exceeds the maximum limit of ${maxValue}.`;
        }
        if (typeof minValue === "number" && limitation.value < minValue) {
            console.log("limitation.value < minValue");

            errors.minValue = `Value for ${limitation.name} is below the minimum limit of ${minValue}.`;
        }
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
