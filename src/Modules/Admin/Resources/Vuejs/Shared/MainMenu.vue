<template>
      <ul class="">
          <li v-for="(item, index) in menus">
              <Link :href="item.link" class="flex items-center p-2 text-base font-normal rounded-lg transition duration-75 hover:bg-gray-100 hover:text-black text-white group">
                  <template v-if="item.icon">
                      <svg v-html="item.icon"></svg>
                  </template>
                  <template v-else="item.icon">
                      <svg  xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-4 h-4 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                      </svg>
                  </template>
                  <span class="ml-3">{{item.name}}</span>
              </Link>
          </li>
      </ul>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { usePage,Link } from '@inertiajs/vue3'
import Icon from '@admin/Shared/Icon.vue'
defineProps({
    menus: {
        type: Object,
        default: () => {}
    }
})
const page = usePage();
const user = computed(() => {
    return page.props.auth.user;
})
const menus = computed(() => {
   // console.log(page.props.menu);
    return page.props.menu;
})
</script>
