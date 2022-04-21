<template>
  <div>
    <h1>Welcome to the hiring platform</h1>
    <router-link to="/companies-listing">Go to company listing</router-link>
  </div>
</template>

<script>
export default {
  name: "Welcome",
  data() {
    return {};
  },

  mounted() {
    console.log("Hello world");
  },

  methods: {},
};
</script>

<style lang="scss" scoped></style>
