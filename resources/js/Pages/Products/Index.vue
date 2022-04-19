<template>
  <Head title="Products" />
  <div>
    <div v-for="product in products" :key="product.id">
      <h5>{{ product.title }}</h5>
    </div>
  </div>
</template>
<script>
import { Head } from "@inertiajs/inertia-vue3";
export default {
  props: ["pagination"],
  computed: {
    products() {
      return this.pagination.data;
    },
  },
  mounted() {
    console.log(this.products);
  },
  components: {
    Head,
  },
};
</script>
<style>
</style>