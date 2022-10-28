<template>
  <div></div>
</template>
<script>
export default {
  created() {
    console.log(this.products);
  },
  computed: {
    products() {
      return this.pagination.data;
    },
  },
  props: ["pagination"],
};
</script>
<style lang="">
</style>