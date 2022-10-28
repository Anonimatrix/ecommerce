<template >
  <div v-for="product in products" :key="product.id">{{ product.title }}</div>
</template>
<script>
export default {
  computed: {
    products() {
      return this.pagination.data;
    },
  },
  props: ["pagination"],
  mounted() {
    console.log(this.products);
  },
};
</script>
<style>
</style>