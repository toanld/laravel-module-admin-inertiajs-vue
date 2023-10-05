<template>
  <div>
    <Head title="Contacts" />
    <h1 class="mb-8 text-3xl font-bold">Cấu hình hệ thống</h1>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">ID</th>
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Updated</th>
          <th class="pb-4 pt-6 px-6">Delete</th>
        </tr>
        <tr v-for="(item,id) in datas" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t text-center">
              {{ item.name }}
          </td>
          <td class="border-t ">
              <text-input label="Kiểu Input textbox" v-model="name"  class="pb-8 pr-6 w-full lg:w-1/2"  />
          </td>
          <td class="border-t">

          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Icon from '@admin/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@admin/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import TextInput from '@admin/Shared/TextInput.vue'
import TextareaInput from '@admin/Shared/TextareaInput.vue'
import editor from '@admin/Shared/Tinymce.vue'
import mapValues from 'lodash/mapValues'
import Pagination from '@admin/Shared/Pagination.vue'
import SearchFilter from '@admin/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
      TextInput,
      TextareaInput,
  },
  layout: Layout,
  props: {
    datas: Object,
    routeName:'configurations'

  },
  data() {
    return {
      name:null,
      form: {
      },
    }
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
      destroy(id) {
          if (confirm(myTrans('Are you sure you want to delete this model?'))) {
              this.$inertia.delete(route(this.routeName+'.destroy',id))
          }
      },
  },
}
</script>
