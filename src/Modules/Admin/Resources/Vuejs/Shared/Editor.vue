<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="editorId">{{ label }}:</label>
        <textarea :id="editorId"></textarea>
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from 'uuid';

export default {
    inheritAttrs: false,
    props: {
        error: String,
        label: String,
        modelValue: String,
    },
    data() {
        return {
            editorId: `editor-tinymce-${uuid()}`,
            editor: null,
        };
    },
    beforeUnmount() {
        if (this.editor) {
            this.editor.destroy();
        }
    },
    mounted() {
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
                this.editor = tinymce.init({
                    selector: `#${this.editorId}`,
                    height: 600,
                    statusbar: false,
                    menubar: false,
                    plugins: 'autoresize anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar:
                        'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    image_title: true,
                    file_picker_types: 'image',
                });

                this.editor.on('input', () => {
                    this.$emit('update:modelValue', this.editor.getContent());
                });

                clearInterval(interval);
            }
        }, 100);
    },
};
</script>
