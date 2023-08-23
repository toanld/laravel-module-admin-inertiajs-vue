<script setup>
import { computed, ref, onMounted, onBeforeUnmount, defineEmits, defineProps } from 'vue'

const emit = defineEmits(['update:modelValue', 'setRef']);

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

const editorId = 'my-editor'; // Định danh của trình soạn thảo TinyMCE
const editorContent = ref(props.modelValue);

const computedValue = computed({
    get: () => props.modelValue,
    set: value => {
        console.log(value);
        emit('update:modelValue', value)
    }
})

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

        if (!json || typeof json.data[0].fullsize != 'string') {
            reject('Invalid JSON: ' + xhr.responseText);
            return;
        }

        resolve(json.data[0].fullsize);
    };

    xhr.onerror = () => {
        reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
    };

    const formData = new FormData();
    formData.append('file', blobInfo.blob(), blobInfo.filename());

    xhr.send(formData);
});

onMounted(() => {
    if (!window.tinymceLoaded) {
        let tinymceScript = document.createElement('script');
        tinymceScript.setAttribute('src', '/assets/libs/tinymce/tinymce.min.js');
        document.head.appendChild(tinymceScript);
        window.tinymceLoaded = true;
    } else {
        console.log('TinyMCE script is already loaded.');
    }

    const interval = setInterval(() => {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: `#${editorId}`,
                height: 400,
                menubar: false,
                plugins: "link,image",
                toolbar:
                    'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                image_title: true,
                statusbar: false,
                file_picker_types: 'image',
                // images_upload_url: route('upload2'),
                // automatic_uploads: false
                images_upload_handler: post_image_upload_handler,
                // convert_urls: false,
                images_upload_base_path: '/tesst',
                setup: editor => {
                    editor.on('input', () => {
                        emit('update:modelValue', editor.getContent()); // Emit the updated value
                    });
                }
            });
            clearInterval(interval);
        }
    }, 100);
})

onBeforeUnmount(() => {
    if (typeof tinymce !== 'undefined') {
        const editor = tinymce.get(editorId);
        if (editor) {
            editor.destroy();
        }
    }
})
</script>

<template>
        <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
        <textarea :id="editorId"  v-model="computedValue"></textarea>
        <div v-if="error" class="form-error">{{ error }}</div>
</template>
