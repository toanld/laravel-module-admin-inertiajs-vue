<template>
  <span class="text-white font-bold">{{config.name}}</span>
</template>
<script setup>
import { reactive, computed } from 'vue'
import { usePage,Link } from '@inertiajs/vue3'
defineProps({
    config: {
        type: Object,
        default: () => {
            name:'Kỷ nguyên số'
        }
    }
})
const page = usePage();
const config = computed(() => {
    return page.props.config;
})
</script>
