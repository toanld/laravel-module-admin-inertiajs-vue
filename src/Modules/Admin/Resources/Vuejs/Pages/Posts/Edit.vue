<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" :href="route('posts')">Bài viết</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="post.deleted_at" class="mb-6" @restore="restore"> This data has been deleted. </trashed-message>
    <div class="p-3 bg-white border-b border-gray-200">
      <form @submit.prevent="update">
        <file-input v-model:data="form.pictures"    />
          <text-input v-model="form.name" :error="form.errors.name" class="mb-4" label="Tiêu đề" />
          <textarea-input v-model="form.teaser" :error="form.errors.teaser" class="pb-8 pr-6 w-full" label="Tóm tắt" />
          <editor v-model="form.description" :error="form.errors.description" class="mb-4"></editor>
          <checkbox v-model="form.status" :error="form.errors.status" class="" label="Trạng thái"/>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
              <button v-if="!post.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xóa bài viết</button>
              <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Lưu chỉnh sửa</loading-button>
          </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@admin/Shared/Layout.vue'
import TextInput from '@admin/Shared/TextInput.vue'
import SelectInput from '@admin/Shared/SelectInput.vue'
import LoadingButton from '@admin/Shared/LoadingButton.vue'
import TrashedMessage from '@admin/Shared/TrashedMessage.vue'
import editor from '@admin/Shared/Tinymce.vue'
import TextareaInput from '@admin/Shared/TextareaInput.vue'
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
    TrashedMessage,
    FileInput,
    Checkbox
  },
  layout: Layout,
  props: {
    post: Object,
    organizations: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.post.name,
        teaser: this.post.teaser,
        description: this.post.description,
        pictures: JSON.parse(this.post.pictures),
        status: this.post.status ? true : false,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(route('posts.update',this.post.id))
    },
    destroy() {
      if (confirm('Are you sure you want to delete this post?')) {
        this.$inertia.delete(route('posts.destroy',this.post.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this post?')) {
        this.$inertia.put(route('posts.restore',this.post.id))
      }
    },
  },
}
</script>
