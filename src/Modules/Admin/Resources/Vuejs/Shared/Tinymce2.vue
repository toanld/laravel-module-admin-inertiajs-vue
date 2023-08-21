<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import editor from '@tinymce/tinymce-vue'

const emit = defineEmits(['update:modelValue', 'setRef'])

const props = defineProps({
    modelValue: {
    type: [String, Number, Boolean, Array, Object],
    default: ''
  },
  error: {
    type: String,
    default: null
  }
})

const computedValue = computed({
  get: () => props.modelValue,
  set: value => {
    emit('update:modelValue', value)
  }
})
const cssUrls = ref([]); // Lưu trữ các URL của file CSS

onMounted(() => {
    // Lấy URL của các file CSS từ các thẻ <link>
    getCssUrls();
});
const getCssUrls = () => {
    const linkElements = document.querySelectorAll('link[rel="stylesheet"]');
    cssUrls.value = Array.from(linkElements).map(link => link.href);
};

const post_image_upload_handler = (blobInfo, progress) => new Promise((resolve, reject) => {
  const xhr = new XMLHttpRequest();
  xhr.withCredentials = false;
  xhr.open('POST', route('upload'));
   var token = document.head.querySelector("[name=csrf-token]").content;
   console.log(token)
    xhr.setRequestHeader("X-CSRF-Token", token);
  xhr.upload.onprogress = (e) => {
    progress(e.loaded / e.total * 100);
  };

  xhr.onload = () => {
    if (xhr.status === 403) {
      reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
      return;
    }

    if (xhr.status < 200 || xhr.status >= 300) {
      reject('HTTP Error: ' + xhr.status);
      return;
    }

    const json = JSON.parse(xhr.responseText);

    if (!json || typeof json.data.fullsize != 'string') {
      reject('Invalid JSON: ' + xhr.responseText);
      return;
    }

    resolve(json.data.fullsize);
  };

  xhr.onerror = () => {
    reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
  };

  const formData = new FormData();
  formData.append('file', blobInfo.blob(), blobInfo.filename());

  xhr.send(formData);
});

</script>
<template>
    <editor
        api-key="b82c46fpu6v40ajjpr5r5q1foi5jvxjd1fnj76cexqc9udbg"
        :init="{
          height: 400,
          menubar: false,
          plugins: 'importcss anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
          toolbar:
            'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
          image_title: true,
          statusbar: false,
          content_css: `${cssUrls.join(',')}`,
          file_picker_types: 'image',
          // images_upload_url: route('upload2'),
            // automatic_uploads: false
          images_upload_handler: post_image_upload_handler,
          // convert_urls: false,
          images_upload_base_path: '/tesst'
          // file_picker_callback: callBack


        }"
        v-model="computedValue"
      />
</template>
<style type="text/css">
    .tox-notifications-container{
        display: none;
    }
</style>
