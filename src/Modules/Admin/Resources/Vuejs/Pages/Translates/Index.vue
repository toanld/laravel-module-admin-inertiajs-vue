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
                      Nội dung dịch <select @change="handleChange($event)" class="pl-4 pr-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      <option v-for="(item,id) in langs" :value="id" :selected="id === lang">{{item}}</option>
                  </select>
                  </th>
                </tr>
                <tr v-for="(item,id) in new_attr" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                  <td class="border-t p-2">
                      <div class="p-3">
                          <button type="button" @click="addTo(item.id)" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-2 border border-secondary-500 hover:border-transparent rounded">⬇</button>&nbsp;<span :id="item.id">{{ item.label }}</span>
                      </div>
                      <text-input v-model="item.value" :id="'input_'+item.id"  class="w-full pl-3" />
                  </td>
                </tr>
               <tr>
                   <td class="pb-4 pl-2">
                       <table>
                           <tr>
                               <td class="p-2"><loading-button :loading="form.processing" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Cập nhật</loading-button></td>
                               <td class="p-2"><loading-button :loading="form.processing" @click="updateGoogleTranslate()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="button">Nhập từ label sau khi dung google translate</loading-button></td>
                           </tr>
                       </table>


                   </td>
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
      addTo(id){
          const divElement = document.getElementById(id);
          if (divElement) {
              document.getElementById('input_'+id).value = divElement.innerText;
          }
      },
      updateGoogleTranslate(){
          Object.keys(this.new_attr).forEach(key => {
              const id = this.new_attr[key].id;
              const divElement = document.getElementById(id);
              if (divElement) {
                  this.new_attr[key].value = divElement.innerText;
              }
          });
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
