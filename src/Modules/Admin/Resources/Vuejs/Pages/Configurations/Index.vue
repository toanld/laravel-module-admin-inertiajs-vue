<template>
  <div>
    <Head title="Contacts" />
    <h1 class="mb-8 text-3xl font-bold">Cấu hình hệ thống</h1>
    <div class="bg-white rounded-md shadow overflow-x-auto">
        <form @submit.prevent="store">
              <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                  <th class="pb-4 pt-6 px-6">Tên</th>
                  <th class="pb-4 pt-6 px-6">Sử dụng trong code</th>
                  <th>Laravel</th>
                </tr>
                <tr v-for="(item,id) in new_attr" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                  <td class="border-t align-text-top p-4" width="100">
                      {{ item.label }}
                  </td>
                  <td class="border-t p-2">
                      <text-input v-if="item.type == 'string'" v-model="item.value"  class="w-full" />
                      <textarea v-if="item.type == 'json'" v-model="item.value" style="height: 200px; width: 100%;"   >{{item.value}}</textarea>
                  </td>
                  <td width="100">
                      <code>config('db.{{ item.field }}')</code>
                  </td>
                </tr>
               <tr>
                   <td>&nbsp;</td>
                   <td colspan="2"><loading-button :loading="form.processing" class="btn-indigo" type="submit">Cập nhật</loading-button></td>
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
    routeName:'configurations'

  },
  data() {

    return {
      new_attr: {},
      name:null,
        form: this.$inertia.form({
            data_configs:[]
        }),
    }
  },
    created() {
        this.new_attr = this.datas;
    },
  methods: {
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
