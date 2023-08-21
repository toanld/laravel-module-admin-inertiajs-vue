<template>
  <div >
    <div v-for="(item, index) in menus">
      <Link class="group flex items-center py-3" :href="item.link">
        <icon name="dashboard" class="mr-2 w-4 h-4 fill-indigo-400 group-hover:fill-white" />
        <div class="text-indigo-300 group-hover:text-white">{{item.name}}</div>
      </Link>
    </div>
  </div>
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
