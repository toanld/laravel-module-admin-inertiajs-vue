<template>
  <div>
    <Head title="Contacts" />
    <h1 class="mb-8 text-3xl font-bold">Dịch hệ thống website </h1>
    <div class="bg-white rounded-md shadow overflow-x-auto">
        <form @submit.prevent="store">
              <table class="w-full whitespace-nowrap">
                <tr>
                    <td></td>
                    <td class="p-2">

                    </td>
                </tr>
                <tr class="text-left font-bold">
                  <th class="pb-4 pt-6 px-6">
                      <select @change="handleChange($event)" class="pl-4 pr-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option v-for="(item,id) in langs" :value="id" :selected="id === lang">{{item}}</option>
                      </select>
                  </th>
                  <th class="pb-4 pt-6 px-6">
                      Nội dung dịch
                  </th>
                </tr>
                <tr v-for="(item,id) in new_attr" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                  <td class="border-t align-text-top p-4" width="100">
                      {{ item.label }}
                  </td>
                  <td class="border-t p-2">
                      <text-input v-model="item.value"  class="w-full" />
                  </td>
                </tr>
               <tr>
                   <td>&nbsp;</td>
                   <td colspan="2" class="pb-4 pl-2"><loading-button :loading="form.processing" class="btn-indigo" type="submit">Cập nhật</loading-button></td>
               </tr>
              </table>
        </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Icon from '@admin/Shared/Icon.vue'
import Layout from '@admin/Shared/Layout.vue'
import TextInput from '@admin/Shared/TextInput.vue'
import TextareaInput from '@admin/Shared/TextareaInput.vue'
import mapValues from 'lodash/mapValues'
import LoadingButton from '@admin/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
      TextInput,
      TextareaInput,
      LoadingButton
  },
  layout: Layout,
  props: {
    datas: Object,
    langs: Object,
      lang: {
          type: String, // hoặc Number, tùy thuộc vào kiểu dữ liệu của id
          required: true
      },
    routeName:null

  },
  data() {

    return {
      new_attr: {},
      name:null,
        form: this.$inertia.form({
            data_configs:[],
            lang:this.lang
        }),
    }
  },
    created() {
        this.new_attr = this.datas;
    },
  methods: {
      handleChange(event) {
          const selectedValue = event.target.value;
          // Giả định bạn có một hàm route để tạo URL
          const url = route('translates', {"lang":selectedValue});
          window.location.href = url;
      },
    reset() {
      this.form = mapValues(this.form, () => null)
    },
      store() {
          this.form.data_configs = this.new_attr;
          this.form.post(route(this.routeName+'.store'),{
              onSuccess: (data) => {
                  console.log(data);
              },
          })
      },
  },
}
</script>
