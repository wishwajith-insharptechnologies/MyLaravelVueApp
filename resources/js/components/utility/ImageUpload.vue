<template>
    <a-upload
      name="file"
      :multiple="false"
      :custom-request="handleUpload"
      :file-list="fileList"
      @change="handleChange"
    >
      <a-button>
        <a-icon type="upload" /> Click to Upload
      </a-button>
    </a-upload>
  </template>

  <script setup>
  import { ref, defineEmits, defineExpose } from "vue";
  import { message } from "ant-design-vue";
  import { uploadFile }  from "@/services/Utils.js";

  const fileList = ref([ ]);
  const props = defineProps(['fileList'])
  const emit  = defineEmits(['uploadedImage']);

const handleUpload = async ({ file, onSuccess, onError }) => {
  try {
    const response = await uploadFile(file);
    console.log(response);
    onSuccess(response, file);
    emit('uploadedImage', response.data.data);
  } catch (error) {
    console.log(error);
    onError(error);
  }
};

const handleChange = (info) => {
    // fileList.value = info.fileList;
  if (info.file.status !== 'uploading') {
    console.log(info.file, info.fileList);
  }
  if (info.file.status === 'done') {
    // message.success(`${info.file.name} file uploaded successfully`);
  } else if (info.file.status === 'error') {
    // message.error(`${info.file.name} file upload failed.`);
  }
  fileList.value = info.fileList;
};

const beforeUpload = (file) => {
    const isJpegOrPng = file.type === "image/jpeg" || file.type === "image/png";
    if (!isJpegOrPng) {
        message.error("You can only upload JPEG/PNG files!");
        return false;
    }
    return true;
};

const clearImages = () =>{
    fileList.value = [];
}
defineExpose({
    clearImages
});
  </script>
