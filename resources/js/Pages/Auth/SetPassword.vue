<template>
  <div>Establece una contraseña para poder iniciar sesion con esta</div>
  <form @submit.prevent="setPassword">
    <input type="password" v-model="passwordForm.password" />
    <input type="text" v-model="passwordForm.dniNumber" />
    <input type="text" v-model="passwordForm.dniType" />
    <input type="submit" value="Establecer" />
  </form>
</template>
<script>
export default {
  data() {
    return {
      passwordForm: this.$inertia.form({
        password: "",
        dniNumber: "",
        dniType: "DNI",
      }),
    };
  },
  methods: {
    setPassword() {
      this.passwordForm.post(this.route("auth.set-info"));
    },
  },
};
</script>
<style lang="">
</style>