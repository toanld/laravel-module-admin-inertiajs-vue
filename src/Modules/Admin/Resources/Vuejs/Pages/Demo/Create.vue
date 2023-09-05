<template>
  <div>
    <Head title="Create Category" />
    <h1 class="mb-8 text-3xl font-bold">
        Demo các trường hợp của form
    </h1>
    <div class="rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mr-6 p-4">
            <div class="pb-8 pr-6 w-full">/Admin/Resources/Vuejs/demo/Create.vue</div>
          <text-input label="Kiểu Input textbox" v-model="form.inputext" :error="form.errors.inputext" class="pb-8 pr-6 w-full lg:w-1/2"  />
          <text-input label="Kiểu Input textselectbox request api suggest" :api="route('api.demo')" v-model="form.textselectbox" :error="form.errors.textselectbox" class="pb-1 pr-6 w-full"  />
           <div class="pb-8 pr-6 w-full">Giá trị trả về: {{form.textselectbox}}</div>
            <text-input label="Kiểu Input textselectbox request options suggest" :options="options" v-model="form.textselectbox1" :error="form.errors.textselectbox1" class="pb-1 pr-6 w-full"  />
            <div class="pb-8 pr-6 w-full">Giá trị trả về: {{form.textselectbox1}}</div>
            <file-input v-model:data="form.fileinput"  :error="form.errors.fileinput" class="pl-4 pr-4 pb-4 w-full" label="Upload ảnh"   />
            <select-input  label="Kiểu select box" v-model="form.selectbox" class="pl-4 pr-4 pb-4 w-full/2"  :error="form.errors.selectbox" >
                <option value="0" selected>Chọn giá trị </option>
                <option v-for="(v,k) in list_values" :value="v.id">
                    {{v.name}}
                </option>
            </select-input>
            <checkbox-input v-model="form.checkbox"  class="pl-4 pr-4 pb-4 w-full" label="kiểu checkbox"  :error="form.errors.checkbox" />
            <textarea-input v-model="form.textarea"  class="pl-4 pr-4 pb-4 w-full" label="Kiểu textarea"  :error="form.errors.textarea" />
            <editor class="pl-4 pr-4 pb-4 w-full" v-model="form.typeeditor" label="Kiểu văn bản"  :error="form.errors.typeeditor"></editor>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Submit test</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@admin/Shared/Layout.vue'
import TextInput from '@admin/Shared/TextInput.vue'
import TextareaInput from '@admin/Shared/TextareaInput.vue'
import SelectInput from '@admin/Shared/SelectInput.vue'
import CheckboxInput from '@admin/Shared/Checkbox.vue'
import RadioInput from '@admin/Shared/RadioInput.vue'
import FileInput from '@admin/Shared/ImageFile.vue'
import editor from '@admin/Shared/Tinymce.vue'
import LoadingButton from '@admin/Shared/LoadingButton.vue'
export default {
  components: {
    Head,
    Link,
      LoadingButton,
      SelectInput,
      TextareaInput,
      RadioInput,
      CheckboxInput,
      TextInput,
      FileInput,
      editor,
  },
  layout: Layout,
  props: {
      routeName:'demo',
  },
  remember: 'form',
  data() {
    return {
        options:[
            {id:1,name:'name 1'},
            {id:2,name:'name 2'},
            {id:3,name:'name 3'},
            {id:4,name:'name 4'},
            {id:5,name:'name 5'}
        ],
        list_values:[
            {id:1,name:'name'}
        ],
        form: this.$inertia.form({
        inputext: '',
        fileinput:'',
        checkbox:0,
        typeeditor:null,
            textarea:null,
        textselectbox:{
            id:4, //id
            name:'mặc định đây'
        },
        textselectbox1:{
            id:4, //id
            name:'mặc định đây'
        },
        selectbox:{
        }
      }),
    }
  },
  methods: {
    store() {
      this.form.post(route(this.routeName+'.store'))
    },
  },
}
</script>
