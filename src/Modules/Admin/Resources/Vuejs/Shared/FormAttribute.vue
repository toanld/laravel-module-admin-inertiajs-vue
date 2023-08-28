<template>
    <template v-for="(attr,i) in new_attr">
        <editor class="pl-4 pr-4 pb-4 w-full" v-if="attr.type == 'editor'" :style="(attr.style_css != '') ? attr.style_css : ''" v-model="attr.value" :label="attr.name"  :error="form.errors['attributes.'+attr.key+'.value']"></editor>
        <text-input v-else-if="attr.type == 'textbox'" v-model="attr.value" :style="(attr.style_css != '') ? attr.style_css : ''" class="pl-4 pr-4 pb-4 w-full lg:w-1/3" :label="attr.name"  :error="form.errors['attributes.'+attr.key+'.value']" />
        <textarea-input v-else-if="attr.type == 'textarea'" v-model="attr.value" :style="(attr.style_css != '') ? attr.style_css : ''" class="pl-4 pr-4 pb-4 w-full lg:w-1/3" :label="attr.name"  :error="form.errors['attributes.'+attr.key+'.value']" />
        <select-input v-else-if="attr.type == 'select'" :label="attr.name" :style="(attr.style_css != '') ? attr.style_css : ''" v-model="attr.value" class="pl-4 pr-4 pb-4 w-full/2"  :error="form.errors['attributes.'+attr.key+'.value']" >
            <option value="0" selected>Chọn giá trị {{attr.name}}</option>
            <option v-for="(v,k) in attr.list_values" :value="v.id">
                {{v.name}}
            </option>
        </select-input>
        <checkbox-input v-else-if="attr.type == 'checkbox'" :style="(attr.style_css != '') ? attr.style_css : ''" v-model="attr.value"  class="pl-4 pr-4 pb-4 w-full" :label="attr.name"  :error="form.errors['attributes.'+attr.key+'.value']" />
        <div class="pl-4 pr-4 pb-4 w-full" v-else-if="attr.type == 'radio'" :style="(attr.style_css != '') ? attr.style_css : ''">
            <label class="form-label">{{ attr.name }}:</label>
            <div class="flex items-center mb-4" v-for="(v,k) in attr.list_values">
                <input :id="attr.key+v.id" type="radio" :value="v.id" v-model="attr.value" :name="attr.key" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label :for="attr.key+v.id" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ v.name }}</label>
            </div>
        </div>
    </template>
</template>

<script>
import TextInput from '@admin/Shared/TextInput.vue'
import TextareaInput from '@admin/Shared/TextareaInput.vue'
import SelectInput from '@admin/Shared/SelectInput.vue'
import CheckboxInput from '@admin/Shared/Checkbox.vue'
import RadioInput from '@admin/Shared/RadioInput.vue'
import LoadingButton from '@admin/Shared/LoadingButton.vue'
import FileInput from '@admin/Shared/ImageFile.vue'
import editor from '@admin/Shared/Tinymce.vue'
export default {
    components: {
        LoadingButton,
        SelectInput,
        TextareaInput,
        RadioInput,
        CheckboxInput,
        TextInput,
        FileInput,
        editor,
    },
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `text-input`
            },
        },
        type: {
            type: String,
            default: 'text',
        },
        error: String,
        label: String,
        value:String,
        modelValue: String,
        form:Object,
        attrs:[],
    },
    computed: {
        new_attr: {
            get() {
                return this.attrs;
            },
            set(newValue) {
                this.$emit('update:modelValue', newValue);
            },
        },
    },
    data(){
        return {
            new_attr: this.modelValue,
        }
    },
    mounted() {
        console.log(this.modelValue);
    },
    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
    },
}
</script>
