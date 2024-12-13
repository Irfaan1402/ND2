<template>
  <div class="w3-display-middle">
    <h1 class="w3-jumbo w3-animate-top w3-center"><code>Access Denied</code></h1>
    <h3 class="w3-center w3-animate-right">You don't have permission to view this page.</h3>
    <h3 class="w3-center w3-animate-zoom">🚫🚫🚫🚫</h3>
    <h6 class="w3-center w3-animate-zoom"><strong>Error Code</strong>: 403 forbidden</h6>
  </div>
</template>
<style scoped>body{
  background-color: #100;
  color: white;
  height: 100%;
  margin: 0;
}



.w3-display-middle {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;
  text-align: center;
}


h1 {
  color: red;
}

h6{
  color: red;
  font-weight: 100;
  text-decoration: none; /*underline;*/
}


</style>
<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
  },
  layout: Layout,
}
</script>

