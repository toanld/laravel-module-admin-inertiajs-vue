<template>
  <div>
    <Head title="Create Category" />
    <h1 class="mb-8 text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" :href="route(routeName)">Danh mục</Link>
      <span class="text-indigo-400 font-medium"> /</span> tạo mới
    </h1>
    <div class="rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mr-6 p-4">
          <text-input label="Tiêu đề" v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2"  />
          <textarea-input label="Mô tả" v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full"  />
            <text-input label="Kiểu Input textselectbox request options suggest" :options="listings" v-model="form.cat_id" :error="form.errors.cat_id" class="pb-1 pr-6 w-full"  />
            <div>{{form.cat_id}}</div>
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
import TextareaInput from '@admin/Shared/TextareaInput.vue'
import SelectInput from '@admin/Shared/SelectInput.vue'
import LoadingButton from '@admin/Shared/LoadingButton.vue'
import InputDropdown from "@admin/Shared/InputDropdown.vue";
export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextareaInput,
    TextInput,
      InputDropdown,
  },
  layout: Layout,
  props: {
      routeName:'categoryposts',
      listings:[]
  },
  remember: 'form',
  data() {
    return {
        form: this.$inertia.form({
            name: '',
            description:null,
            cat_id:{
                id:0,
                name:null
            }
          }),
    }
  },
  methods: {
      validateSelection(selection) {
          this.form.cat_id = selection;
          console.log(selection.name + " has been selected");
      },
      getDropdownValues(keyword) {
          console.log("You could refresh options by querying the API with " + keyword);
      },
    store() {
      this.form.post(route(this.routeName+'.store'))
    },
  },
}
</script>
