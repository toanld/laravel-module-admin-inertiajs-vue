<template>
  <div>
    <Head title="Create Category" />
    <h1 class="mb-8 text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" :href="route(routeName)">Trang tĩnh</Link>
      <span class="text-indigo-400 font-medium"> /</span> tạo mới
    </h1>
    <div class="rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mr-6 p-4">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Tiêu đề" />
        </div>
          <div class="flex flex-wrap -mb-8 -mr-12 p-4">
              <Tinymce v-model="form.description" :error="form.errors.description"></Tinymce>
          </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create new</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@admin/Shared/Layout.vue'
import TextInput from '@admin/Shared/TextInput.vue'
import TextareaInput from '@admin/Shared/Tinymce.vue'
import SelectInput from '@admin/Shared/SelectInput.vue'
import LoadingButton from '@admin/Shared/LoadingButton.vue'
import Tinymce from '@admin/Shared/Tinymce.vue'
export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextareaInput,
    TextInput,
      Tinymce
  },
  layout: Layout,
  props: {
      routeName:'statics',
  },
  remember: 'form',
  data() {
    return {
        form: this.$inertia.form({
            name: '',
            description:''
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
