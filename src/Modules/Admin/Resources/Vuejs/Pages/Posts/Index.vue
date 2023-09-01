<template>
  <div>
    <Head title="Contacts" />
    <h1 class="mb-8 text-3xl font-bold">Bài viết</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset"></search-filter>
      <Link class="btn-indigo" :href="route('posts.create')">
        <span>Tạo </span>
        <span class="hidden md:inline">Bài Viết</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">ID</th>
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Picture</th>
          <th class="pb-4 pt-6 px-6">Updated</th>
        </tr>
        <tr v-for="(item,id) in posts.data" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t text-center">
                {{id+1}}
          </td>
          <td class="border-t ">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="route('posts.edit',item.id)">
              {{ item.name }}
              <icon v-if="item.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t text-center">
            <img :src="JSON.parse(item.pictures)[0] ? JSON.parse(item.pictures)[0]['thumb'] : '/logo/no-image.svg'" class="h-auto w-14 rounded-lg">
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('posts.edit',item.id)" tabindex="-1">
              <div>
                {{ item.updated_at }}
              </div>
            </Link>
          </td>
        </tr>
        <tr v-if="posts.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No posts found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="posts.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Icon from '@admin/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@admin/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@admin/Shared/Pagination.vue'
import SearchFilter from '@admin/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    posts: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route('posts'), pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
