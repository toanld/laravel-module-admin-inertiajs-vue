<script setup>
import { v4 as uuid } from 'uuid'
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
    },
    label: {
        type: String,
        default: null
    },
    label_last_upload: {
        type: String,
        default: null
    },
    id: {
        type: String,
        default: null
    },
    url: {
        type : String,
        default : route('upload')
    },
})
let lastestImage = ref([]);
const editorId = `textarea-input-${uuid()}`; // Định danh của trình soạn thảo TinyMCE
const editorContent = ref(props.modelValue);

const computedValue = computed({
    get: () => props.modelValue,
    set: value => {
        value = value.replace("../../storage/","/storage/");
        emit('update:modelValue', value)
    }
})

const makeid = (length) => {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }
    return result;
}

const getLastestUpload = async() => {
    apiGet(route('upload.lastest',10))
        .then(res => {
            if(res.data.error){
                alert(res.data.error)
            }else{
                lastestImage.value = res.data;
            }
        })
        .catch(err => {

        })
}

const post_image_upload_handler = (blobInfo, progress) => new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.withCredentials = false;
    xhr.open('POST', props.url);
    var token = document.head.querySelector("[name=csrf-token]").content;
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
                min_height: 400,
                menubar: false,
                plugins: "link,image,autoresize,code",
                toolbar:
                    'undo redo | blocks fontfamily fontsize | bold italic underline | link image media table | align | gptButton',
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
                        let value = editor.getContent();
                        value = value.replace("../../storage/","/storage/");
                        emit('update:modelValue', value); // Emit the updated value
                    });
                    editor.ui.registry.addButton('gptButton', {
                        text: 'Viết lại bằng GPT',
                        tooltip: 'Bấm để viết lại nội dung bằng chatGPT',
                        onAction: function () {
                            if (editor) {
                                axios.post(route('stream'), {
                                    question: editor.getContent()
                                })
                                    .then(function (response) {
                                        editor.focus();
                                        editor.setContent("", {format : "raw"});
                                        const source = new EventSource(route('stream'));
                                        let valueStream = '';


                                        source.addEventListener("update", function (event) {
                                            if (event.data === "end") {
                                                source.close();
                                                return;
                                            }
                                            valueStream += event.data;
                                            let str = event.data;
                                            const lastChar = str[str.length - 1];
                                            if(lastChar == '>'){
                                                editor.setContent(valueStream, {format : "raw"}); // Đặt nội dung
                                                editor.selection.select(editor.getBody(), true);
                                                editor.selection.collapse(false);
                                                var objDiv = document.getElementById(editorId+"_error");
                                                objDiv.scrollTop = objDiv.scrollHeight;
                                            }else {
                                                editor.execCommand('mceInsertContent', false, event.data);
                                            }
                                            //console.log(valueStream);
                                            emit('update:modelValue', valueStream); // Emit the updated value
                                        });

                                    })
                                    .catch(function (error) {
                                        console.log(error);
                                    }).done(function (response){
                                        // Chèn nội dung "abc" vào trình soạn thảo
                                        let value = editor.getContent();
                                        emit('update:modelValue', value); // Emit the updated value
                                    })

                            }
                            /*

                            // */
                        },
                    });
                }
            });
            //getLastestUpload();
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
const insertImage = (imageUrl) => {
    if (typeof tinymce !== 'undefined') {
        const editor = tinymce.get(editorId);
        if (editor) {
            const imageHtml = `<img src="${imageUrl}">`;
            editor.execCommand('mceInsertContent', false, imageHtml);
        }
    }
}
</script>

<template>
        <div>
            <label v-if="label" class="form-label" style="font-weight: bold" :for="id">{{ label }}:</label>
            <a v-if="label_last_upload" class="cursor-pointer m-1 flex text-green-700" @click="getLastestUpload()">{{label_last_upload}}</a>
            <div v-if="lastestImage.length" class="mb-2" style="overflow: hidden; height: 100px; display: flex">
                <img  v-for="(item, index) in lastestImage" :src="item.thumb" @click="insertImage(item.thumb)" :alt="'Image ' + (index + 1)" width="100" height="100" class="cursor-pointer m-1 border-2 border-gray-300">
            </div>
            <div id="toan_chien"></div>
            <textarea :id="editorId"  v-model="computedValue"></textarea>
            <div v-if="error" :id="editorId+'_error'" class="form-error">{{ error }}</div>
        </div>
</template>
