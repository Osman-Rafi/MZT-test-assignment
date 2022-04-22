<template>
  <div>
    <div class="p-10">
      <h1 class="text-4xl font-bold">Candidates</h1>
      <h2>Coins: {{ coins }}</h2>
    </div>
    <div
      class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5"
    >
      <div
        v-for="(candidate, index) in candidates"
        :key="index"
        class="rounded overflow-hidden shadow-lg"
      >
        <img class="w-full" src="/avatar.png" alt="" />
        <div class="px-6 py-4">
          <div class="font-bold text-xl mb-2">{{ candidate.name }}</div>
          <p class="text-gray-700 text-base">{{ candidate.description }}</p>
        </div>
        <div class="px-6 pt-4 pb-2">
          <span
            v-for="(strength, index) in JSON.parse(candidate.strengths)"
            :key="index"
            class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
            >{{ strength }}</span
          >
        </div>
        <div class="p-6 float-right">
          <button
            @click="contactCandidate(candidate)"
            class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
          >
            Contact
          </button>
          <button
            class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow"
          >
            Hire
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Candidates",

  data() {
    return {
      candidates: [],
      coins: 0,
    };
  },

  created() {
    this.fetchCadidatesListAndCoins();
  },

  methods: {
    async fetchCadidatesListAndCoins() {
      try {
        const res = await axios.get(
          `/api/get-candidates-list-and-coins/${this.$route.params.id}`
        );
        this.candidates = res.data.candidates;
        this.coins = res.data.coins;
      } catch (e) {
        console.error(e);
      }
    },

    async contactCandidate(candidate) {
      const formData = new FormData();
      formData.append("candidate_id", candidate.id);
      formData.append("company_id", this.$route.params.id);
      try {
        const res = await axios.post(`/api/contact-with-candidate`, formData);
        if (res) {
          this.coins = this.coins -= 5;
        }
      } catch (e) {
        console.error(e);
      }
    },
  },
};
</script>
