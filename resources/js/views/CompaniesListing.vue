<template>
  <div class="bg-purple-100 h-full">
    <template v-if="companies.length">
      <div class="flex h-screen flex-wrap flex justify-center">
        <div class="w-1/62 md:w-1/2 pr-4 pl-4 m-auto">
          <h2 class="text-center font-bold text-4xl mb-16">Listed Companies</h2>
          <div
            class="relative flex flex-col min-w-0 rounded break-words border bg-white border-1 border-grey-light"
          >
            <div class="flex-auto p-6">
              <div
                v-for="(company, index) in companies"
                :key="index"
                :class="index !== companies.length - 1 ? 'border-b-2 mb-2' : ''"
              >
                <div
                  class="flex justify-between"
                  :class="index !== companies.length - 1 ? 'mb-3' : ''"
                >
                  <h2 class="text-2xl font-serif">{{ company.name }}</h2>
                  <router-link
                    :to="'/company/' + company.id"
                    tag="button"
                    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
                  >
                    Enter
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else-if="!companies.length && !loading">
      <h2>No company added</h2>
    </template>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Companieslisting",

  data() {
    return {
      loading: false,
      companies: [],
    };
  },

  mounted() {
    this.fetchAllCompaniesList();
  },

  methods: {
    async fetchAllCompaniesList() {
      this.loading = true;

      try {
        const res = await axios.get("api/get-all-companies-list");
        this.companies = res.data;
        this.loading = false;
      } catch (e) {
        console.error(e);
      }
    },
  },
};
</script>
