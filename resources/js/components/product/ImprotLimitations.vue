<template>
    <div>
      <a-form>
        <!-- project_type -->
        <a-form-item label="Feature import config">
          <a-select
            v-model="form.improtsConfigType"
            placeholder="Select a config type"
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

        <!-- project config json -->
        <a-form-item
          v-if="form.improtsConfigType == improtsConfigTypes[0].id"
          label="Upload JSON Config File"
        >
          <a-upload
            :accept="'application/json'"
            @change="onJsonConfigFileChange"
          >
            <a-button>
              <a-icon type="upload" /> Click to Upload
            </a-button>
          </a-upload>
          <a-button
            type="primary"
            class="ml-2"
            @click="improtFileLimitation"
          >
            Import
          </a-button>
        </a-form-item>

        <!-- project config link -->
        <a-form-item
          v-if="form.improtsConfigType == improtsConfigTypes[1].id"
          label="Feature Import Config Link"
        >
          <a-input
            v-model="form.improtsConfigLink"
            placeholder="Feature Import Config Link"
          />
          <a-button
            type="primary"
            class="ml-2"
            @click="importWebLimitation"
          >
            Import
          </a-button>
        </a-form-item>

        <div class="feature-list">
          <div v-for="feature in form.limitationData" :key="feature.featureId">
            <template v-if="feature.parentId === 0">
              <div class="parent">
                {{ feature.featureId }} - {{ feature.name }}
              </div>
              <p class="ml-5 text-gray">({{ feature.description }})</p>
              <ul class="child-list">
                <li
                  v-for="child in getChildFeatures(feature.featureId)"
                  :key="child.featureId"
                  class="child"
                >
                  {{ child.featureId }} - {{ child.name }}
                  <p class="ml-5 text-gray">{{ child.description }}</p>
                </li>
              </ul>
            </template>
          </div>
        </div>
      </a-form>
    </div>
  </template>
<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  storedLimitationData: { type: Object, default: null },
});

const improtsConfigTypes = [
  { id: 1, name: 'Json' },
  { id: 2, name: 'Web' },
];
const form = ref({
  improtsConfigType: improtsConfigTypes[0].id,
  improtsConfigLink: '',
  improtsConfigJsonFile: {},
  limitationData: [],
});
const emit = defineEmits(['limitationDataChanged']);

onMounted(() => {
  if (props.storedLimitationData) {
    form.value.limitationData = props.storedLimitationData;
  }
});

watch(
  () => form.value.limitationData,
  async (newVal, oldVal) => {
    console.log(newVal);
    await newVal;
    emit('limitationDataChanged', newVal);
  },
);

const onJsonConfigFileChange = (event) => {
  const file = event.target.files[0];
  const reader = new FileReader();

  reader.onload = () => {
    const fileContent = reader.result;
    try {
      form.value.improtsConfigJsonFile = JSON.parse(fileContent);
      // emit('improtLimitation', form.value.limitationData);
      console.log('JSON data:', form.value.improtsConfigJsonFile);
    } catch (error) {
      console.error('Error parsing JSON file:', error);
      alert('Error parsing JSON file:', error);
    }
  };

  reader.readAsText(file);
};

const getChildFeatures = (parentId) => {
  return form.value.limitationData.filter(
    (feature) => feature.parentId === parentId,
  );
};

const fetchWebLimitationData = async () => {
  try {
    const response = await axios.get(form.value.improtsConfigLink, {
      withCredentials: true,
    });
    form.value.limitationData = response.data;
    // emit('limitationDataChanged', form.value.limitationData);
    console.log(response.data);
  } catch (error) {
    console.error('Error fetching data:', error);
    alert('Error fetching data:', error);
  }
};

const onImprotsConfigTypeChange = () => {
  form.value.limitationData = [];
};
const improtFileLimitation = () => {
  form.value.limitationData = form.value.improtsConfigJsonFile;
};
const importWebLimitation = async () => {
  // form.value.limitationData = [];
  await fetchWebLimitationData();
};
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
