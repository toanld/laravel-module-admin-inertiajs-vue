<template>
  <div>
    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <p class="mb-8 leading-normal" @click="chupanh">Hey there! Welcome to Ky Nguyen so.</p>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import Layout from '@admin/Shared/Layout.vue'

export default {
  components: {
    Head,
  },
  layout: Layout,
  methods:{
      async chupanh(){
          let img = await takephoto();
          console.log(img);
      }
  }
}
</script>
