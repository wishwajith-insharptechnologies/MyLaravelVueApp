<template>
    <div>
        <form>
            <!-- project_type -->
            <div class="mb-3">
                <a-form-item label="Feature Import Config">
                    <a-select
                        v-model:value="form.improtsConfigType"
                        @change="onImprotsConfigTypeChange"
                    >
                        <a-select-option
                            v-for="configType in improtsConfigTypes"
                            :key="configType.id"
                            :value="configType.id"
                        >
                            {{ configType.name }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </div>

            <!-- project config json -->
            <span
                v-if="!isConfigFieldFill"
                style="color: red; margin-left: 200px"
            >
                Please import project config
            </span>
            <div v-if="form.improtsConfigType != improtsConfigTypes[0].id" >
                <a-form-item
                    v-if="form.improtsConfigType === improtsConfigTypes[1].id"
                    label="Upload JSON Config File"
                    required
                >
                    <a-upload
                        id="ImprotJsonConfigFile"
                        :show-upload-list="false"
                        :before-upload="beforeUpload"
                        :onChange="onJsonConfigFileChange"
                        class="w-full"
                    >
                        <a-button>Click to Upload</a-button>
                    </a-upload>
                    <a-button
                        class="ml-2"
                        type="primary"
                        @click="improtFileLimitation"
                    >
                        Import
                    </a-button>
                </a-form-item>

                <!-- project config link -->
                <a-form-item
                    v-if="form.improtsConfigType === improtsConfigTypes[2].id"
                    label="Feature Import Config Link"
                    required
                >
                    <a-input
                        v-model:value="form.improtsConfigLink"
                        placeholder="Feature Import Config Link"
                        required
                        style="width: 200px"
                    />
                    <a-button
                        style="margin-left: 10px"
                        type="primary"
                        @click="importWebLimitation"
                    >
                        Import
                    </a-button>
                </a-form-item>
                </div>
                    <div class="feature-list">
                        <div
                            v-for="feature in form.limitationData"
                            :key="feature.featureId"
                        >
                            <template v-if="feature.parentId === 0">
                                <div class="parent">
                                    {{ feature.featureId }} - {{ feature.name }}
                                </div>
                                <p class="ml-5 text-gray">
                                    ({{ feature.description }})
                                </p>
                                <ul class="child-list">
                                    <li
                                        v-for="child in getChildFeatures(
                                            feature.featureId
                                        )"
                                        :key="child.featureId"
                                        class="child"
                                    >
                                        {{ child.featureId }} - {{ child.name }}
                                        <p class="ml-5 text-gray">
                                            {{ child.description }}
                                        </p>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
        </form>
    </div>
</template>
<script setup lang="ts">
import { ref, onMounted, computed, watch } from "vue";
import Http from "@/services/Http.js";

const props = defineProps({
    storedLimitationData: { type: Object, default: null },
});
const isConfigFieldFill = ref(true);
const improtsConfigTypes = [
    { id: 0, name: "Select Import Config Type" },
    { id: 1, name: "Json" },
    { id: 2, name: "Web API" },
];
const form = ref({
    improtsConfigType: improtsConfigTypes[0].id,
    improtsConfigLink: null,
    improtsConfigJsonFile: null,
    limitationData: [],
});
const emit = defineEmits(["limitationDataChanged"]);

// onMounted(() => {
//     if (props.storedLimitationData) {
//         form.value.limitationData = props.storedLimitationData;
//     }
// });

watch(
  () => props.storedLimitationData,
  (propsData) => {
    if (propsData) {
      form.value.limitationData = propsData;
      emit("limitationDataChanged", propsData);
    }
  },
  { immediate: true }
);

watch(
    () => form.value.limitationData,
    async (newVal, oldVal) => {
        console.log(newVal);
        await newVal;
        emit("limitationDataChanged", newVal);
    },
);

const onJsonConfigFileChange = (event) => {
    const file = event.file.originFileObj;
    const reader = new FileReader();

    reader.onload = () => {
        const fileContent = reader.result;
        try {
            form.value.improtsConfigJsonFile = JSON.parse(fileContent);
            // emit('improtLimitation', form.value.limitationData);
            console.log("JSON data:", form.value.improtsConfigJsonFile);
        } catch (error) {
            console.error("Error parsing JSON file:", error);
            alert("Error parsing JSON file:", error);
        }
    };

    reader.readAsText(file);
};

const getChildFeatures = (parentId) => {
    return form.value.limitationData.filter(
        (feature) => feature.parentId === parentId
    );
};

const fetchWebLimitationData = async () => {
    try {
        const response = await Http.get(form.value.improtsConfigLink, {
            withCredentials: true,
        });
        form.value.limitationData = response.data;
        // emit('limitationDataChanged', form.value.limitationData);
        console.log(response.data);
    } catch (error) {
        console.error("Error fetching data:", error);
        alert("Error fetching data:", error);
    }
};

const onImprotsConfigTypeChange = () => {
    form.value.limitationData = [];
};
const improtFileLimitation = () => {
    limitationImportValidation(form.value.improtsConfigJsonFile);
    form.value.limitationData = form.value.improtsConfigJsonFile;
};
const importWebLimitation = async () => {
    limitationImportValidation(form.value.improtsConfigLink);
    await fetchWebLimitationData();
};

const limitationImportValidation = async (field) => {
    isConfigFieldFill.value = true;
    if (!field) {
        isConfigFieldFill.value = false;
        return;
    }
};
const clearLimitationData = async () => {
    form.value.limitationData = [];
};
defineExpose({ clearLimitationData });
</script>
<style scoped>
.parent {
    font-weight: bold;
    margin-bottom: 5px;
}

.child-list {
    list-style: none;
    margin-left: 20px;
}

.child {
    margin-bottom: 5px;
}
</style>
