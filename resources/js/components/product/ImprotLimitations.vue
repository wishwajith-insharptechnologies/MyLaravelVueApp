<template>
    <div>
      <form>
        <!-- project_type -->
        <div class="mb-3">
          <label
            for="improtsConfigType"
            class="block text-sm font-medium text-gray-700"
            >Feature improt config</label
          >
          <select
            id="improtsConfigType"
            v-model="form.improtsConfigType"
            name="improtsConfigType"
            autocomplete="on"
            class="w-full rounded border px-3 py-2 text-sm shadow outline-none focus:outline-none
                   {{ errors && errors.project_type ? 'border-red-500 text-red-600 placeholder-red-500 dark:text-red-600 dark:placeholder-red-600' : 'border-gray-300 text-slate-600 placeholder-slate-300 dark:placeholder-slate-400' }}"
            @change="onImprotsConfigTypeChange"
          >
            <option
              v-for="configType in improtsConfigTypes"
              :key="configType.id"
              :value="configType.id"
            >
              {{ configType.name }}
            </option>
          </select>
        </div>

        <!-- project config json -->
        <div
          v-if="form.improtsConfigType == improtsConfigTypes[0].id"
          class="mb-3"
        >
          <label
            for="ImprotJsonConfigFile"
            class="block text-sm font-medium text-gray-700"
            >Upload JSON Config File</label
          >
          <div class="flex">
            <input
              id="ImprotJsonConfigFile"
              name="Image"
              type="file"
              accept="json/*"
              class="flex-1 rounded border px-3 py-2 text-sm shadow outline-none focus:outline-none
                     {{ errors && errors.project_image ? 'border-red-500 text-red-600 placeholder-red-500 dark:text-red-600 dark:placeholder-red-600' : 'border-gray-300 text-slate-600 placeholder-slate-300 dark:placeholder-slate-400' }}"
              @change="onJsonConfigFileChange"
            />
            <button
              type="button"
              class="ml-2 px-4 py-2 bg-blue-500 text-white rounded"
              @click="improtFileLimitation"
            >
              Import
            </button>
          </div>
        </div>

        <!-- project config link -->
        <div
          v-if="form.improtsConfigType == improtsConfigTypes[1].id"
          class="mb-3"
        >
          <label
            for="improtsConfigTypeLink"
            class="block text-sm font-medium text-gray-700"
            >Feature Import Config Link</label
          >
          <div class="flex">
            <input
              id="improtsConfigTypeLink"
              v-model="form.improtsConfigLink"
              type="text"
              placeholder="Feature Import Config Link"
              name="feature improt config link"
              class="flex-1 rounded border px-3 py-2 text-sm shadow outline-none focus:outline-none
                     {{ errors && errors.link ? 'border-red-500 text-red-600 placeholder-red-500 dark:text-red-600 dark:placeholder-red-600' : 'border-gray-300 text-slate-600 placeholder-slate-300 dark:placeholder-slate-400' }}"
            />
            <button
              type="button"
              class="ml-2 px-4 py-2 bg-blue-500 text-white rounded"
              @click="importWebLimitation"
            >
              Improt
            </button>
          </div>
        </div>
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
      </form>
    </div>
  </template>
  <script setup lang="ts">
  import { ref, onMounted, computed, watch } from 'vue';
  import Http from '@/services/Http.js';

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
      const response = await Http.get(form.value.improtsConfigLink, {
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
