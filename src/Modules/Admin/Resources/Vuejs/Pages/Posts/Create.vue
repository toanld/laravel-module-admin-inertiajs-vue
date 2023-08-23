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
          <textarea-input v-model="form.teaser" :error="form.errors.teaser" class="pb-8 pr-6 w-full" label="Tóm tắt" />
          <editor v-model="form.description" :error="form.errors.description"></editor>
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
  },
  layout: Layout,
  props: {
      routeName:'posts',
  },
  remember: 'form',
  data() {
    return {
        form: this.$inertia.form({
        name: '',
        teaser: '',
        pictures:[],
        description: null
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
