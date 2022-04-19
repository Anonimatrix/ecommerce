<template>
  <div>
    <div v-for="categorie in categories" :key="categorie.id">
      <h4 class="text-xl">{{ categorie.title }}</h4>
      <div class="grid grid-cols-4">
        <div
          v-for="subcategorie in categorie.subcategories"
          :key="subcategorie.id"
        >
          <h5 class="text-lg">{{ subcategorie.title }}</h5>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["categories"],
  created() {
    console.log(this.categories);
  },
};
</script>
<style lang="">
</style>