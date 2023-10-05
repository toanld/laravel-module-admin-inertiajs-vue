<template>
  <div>
    <Head title="Create Contact" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" :href="route('posts')">Bài viết</Link>
      <span class="text-indigo-400 font-medium">/</span> tạo mới
    </h1>
    <div class="p-3 bg-white border-b border-gray-200">
      <form @submit.prevent="store">
          <file-input v-model:data="form.pictures"    />
          <text-input v-model="form.name" :error="form.errors.name" class="mb-4" label="Tiêu đề" />
          <text-input label="Chọn danh mục" :options="categories" v-model="form.category" :error="form.errors.category" class="pb-4 pr-6 lg:w-1/2"  />
          <textarea-input v-model="form.teaser" :error="form.errors.teaser" class="pb-8 pr-6 w-full" label="Tóm tắt" />

          <editor v-model="form.description" :error="form.errors.description" class="mb-4"></editor>
          <checkbox v-model="form.status" :error="form.errors.status" class="" label="Trạng thái"/>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
            <loading-button :loading="form.processing" class="btn-indigo" type="submit">Lưu bài viết</loading-button>
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
import LoadingButton from '@admin/Shared/LoadingButton.vue'
import editor from '@admin/Shared/Tinymce.vue'
import FileInput from '@admin/Shared/ImageFile.vue'
import Checkbox from '@admin/Shared/Checkbox.vue'
export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
      editor,
    TextareaInput,
    TextInput,
      FileInput,
      Checkbox,
  },
  layout: Layout,
  props: {
      routeName:'posts',
      categories:{}
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
        form: this.$inertia.form({
        name: '',
        teaser: '',
        category:[
            {id:0,name:'Chọn danh mục'},
        ],
        pictures:[],
        description: null,
        status: true
      }),
    }
  },
  methods: {
    store() {
      this.form.post(route('posts.store'))
    },
  },
}
</script>
