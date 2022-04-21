<template>
  <div>
    <template v-if="companies.length">
      <div v-for="company in companies" :key="company.id">
        <div>{{ company.name }}</div>
        <router-link :to="'/company/' + company.id">
          Enter {{ company.name }}
        </router-link>
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
