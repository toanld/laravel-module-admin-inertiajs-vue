<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 font-bold">
      <span class="text-indigo-400 font-medium">Sửa /</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="model.deleted_at" class="mb-6" @restore="restore"> This category has been deleted. </trashed-message>
    <div class="rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
          <div class="flex flex-wrap -mr-6 p-4">
              <text-input label="Tiêu đề" v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2"  />
              <textarea-input label="Mô tả" v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full"  />
          </div>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
              <button v-if="!model.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Xóa bài viết</button>
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
import TextareaInput from '@admin/Shared/TextareaInput.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextareaInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    routeName:'categoryposts',
    model: Object
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
          description:this.model.description,
        name: this.model.name
      }),
    }
  },
  methods: {
    update() {
      this.form.put(route(this.routeName+'.update',this.model.id))
    },
    destroy() {
      if (confirm(myTrans('Are you sure you want to delete this model?'))) {
        this.$inertia.delete(route(this.routeName+'.destroy',this.model.id))
      }
    },
    restore() {
      if (confirm(myTrans('Are you sure you want to restore this model?'))) {
        this.$inertia.put(route(this.routeName+'.restore',this.model.id))
      }
    },
  },
}
</script>
