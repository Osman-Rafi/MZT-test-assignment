<template>
  <div class="">
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
            <div class="flex">
              <vue-simple-spinner size="small" class="mt-1" />
              <span class="ml-3">Contact</span>
            </div>
          </button>
          <button
            @click="hireCandidate(candidate)"
            class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
          >
            <div class="flex">
              <vue-simple-spinner size="small" class="mt-1" />
              <span class="ml-3">
                <template v-if="candidate.hired_by">Hired</template>
                <template v-else>Hire</template>
              </span>
            </div>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Spinner from "vue-simple-spinner";
export default {
  name: "Candidates",

  components: {
    Spinner,
  },
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
      if (this.coins < 5) {
        alert("You don't have enough coins to contact this candidate");
        return;
      } else {
        const formData = new FormData();
        formData.append("candidate_id", candidate.id);
        formData.append("candidate_name", candidate.name);
        formData.append("company_id", this.$route.params.id);
        formData.append("candidate_email", candidate.email);
        try {
          alert("Sending email to the candidate....");
          const res = await axios.post(`/api/contact-with-candidate`, formData);
          if (res.data.success) {
            this.coins = this.coins -= 5;
            alert("Email sent successfully");
          }
        } catch (e) {
          alert(e);
        }
      }
    },
    async hireCandidate(candidate) {
      const formData = new FormData();
      formData.append("candidate_id", candidate.id);
      formData.append("company_id", this.$route.params.id);
      try {
        const res = await axios.post(`/api/hire-candidate`, formData);
        if (res.data.success) {
          const data = res.data.data;
          this.candidates.find((candidate) => {
            if (candidate.id == data.candidate_id) {
              const index = this.candidates.indexOf(candidate);
              const newCandidate = {
                ...candidate,
                hired_by: data.hired_by,
              };
              this.candidates.splice(index, 1, newCandidate);
            }
          });
        }
      } catch (e) {
        alert(e);
      }
    },
  },
};
</script>
