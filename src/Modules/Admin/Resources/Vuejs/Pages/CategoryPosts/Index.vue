<template>
  <div>
    <Head title="Contacts" />
    <h1 class="mb-8 text-3xl font-bold">Danh mục bài viết</h1>
      <alert-message :type="myform.type">{{myform.message}}</alert-message>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" :href="route('categoryposts.create')">
        <span class="hidden md:inline">Thêm mới</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow">
      <table class="w-full bg-white">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">ID</th>
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6 text-left" v-for="(sname,sid) in type_show">{{sname}}</th>
          <th class="pb-4 pt-6 px-6">Updated</th>
          <th class="pb-4 pt-6 px-6">Delete</th>
        </tr>
        <tr v-for="(item,id) in listings" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t text-center">
                {{id+1}}
          </td>
          <td class="border-t ">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="route(routeName+'.edit',item.id)">
              <span v-html="item.name"></span>
              <icon v-if="item.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
            <td class="border-t px-6 py-4"  v-for="(sname,sid) in type_show">
                <input type="checkbox" :name="'check_'+item.id+'[]'" :class="'check_'+item.id" :checked="item.show & sid"
                       @change="onCheckboxChange(item.id)" :value="sid">
            </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route(routeName+'.edit',item.id)" tabindex="-1">
              <div>
                {{ item.updated_at }}
              </div>
            </Link>
          </td>
            <td class="border-t text-center">
                <a class="flex items-center px-6 py-4" @click="destroy(item.id)" tabindex="-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
                </a>
            </td>
        </tr>
        <tr v-if="listings.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No posts found.</td>
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
import AlertMessage from '@admin/Shared/AlertMessage.vue'
import mapValues from 'lodash/mapValues'
import { MyForm } from '@admin/form/MyForm';
import Pagination from '@admin/Shared/Pagination.vue'
import SearchFilter from '@admin/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
      AlertMessage
  },
  layout: Layout,
  props: {
    filters: Object,
    listings: Object,
    type_show: Object,
    routeName:'categoryposts'

  },
  data() {
    return {
      type_message:null,
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
      myform:new MyForm({
          id:0,
          selected:[]
      })
    }
  },
  methods: {
      onCheckboxChange(itemId){
          let checkboxes = document.getElementsByClassName('check_'+itemId);
          // Lọc ra checkbox có giá trị bằng sid
          let selectedCheckbox = Array.from(checkboxes);
          this.myform.id = itemId;
          let selected = [];
          for (let i in selectedCheckbox){
             if(selectedCheckbox[i].checked){
                 selected.push(selectedCheckbox[i].value);
             }
          }
          this.myform.selected = selected;
          console.log(this.myform.selected);
          this.myform.post(route('categoryposts.checked')).then(response => {
              console.log(response);
          })
      },
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
