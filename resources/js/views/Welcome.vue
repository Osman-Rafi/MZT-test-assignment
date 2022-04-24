<template>
  <div>
    <div
      class="flex h-screen flex-wrap justify-center h-full align-items-center bg-purple-100"
    >
      <div class="md:w-1/2 pr-4 pl-4 w-1/62 m-auto">
        <h1 class="font-bold text-4xl text-center">
          Welcome to the hiring platform
        </h1>
        <div class="flex justify-center">
          <router-link
            to="/companies-listing"
            tag="button"
            class="mt-14 bg-gray-900 border border-transparent duration-200 focus:ring-2 hover:bg-gray-700 px-6 py-3 rounded-xl sm:w-auto text-lg text-white"
            >Go to company listing</router-link
          >
        </div>
      </div>
    </div>
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
