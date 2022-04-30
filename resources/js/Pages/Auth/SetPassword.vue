<template>
  <div>Establece una contraseña para poder iniciar sesion con esta</div>
  <form @submit.prevent="setPassword">
    <input type="password" v-model="passwordForm.password" /><input
      type="submit"
      value="Establecer"
    />
  </form>
</template>
<script>
export default {
  data() {
    return {
      passwordForm: this.$inertia.form({
        password: "",
      }),
    };
  },
  methods: {
    setPassword() {
      this.passwordForm.post(this.route("oauth.set-password"));
    },
  },
};
</script>
<style lang="">
</style>