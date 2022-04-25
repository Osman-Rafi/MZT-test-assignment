<template>
  <div class="">
    <div class="p-10 bg-indigo-200 flex justify-between">
      <h1 class="text-4xl font-bold">Candidates List</h1>
      <h2 class="text-3xl">Coins: {{ coins }}</h2>
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
          <button v-if="candidate.hired_by" class="mx-5">Hired</button>
          <template v-else>
            <button
              @click="contactCandidate(candidate, index)"
              class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
            >
              <div class="flex">
                <vue-simple-spinner
                  v-if="actionButtonsStatus[index].contact"
                  size="small"
                  class="mt-1"
                />
                <span class="ml-3">
                  <template v-if="actionButtonsStatus[index].contact"
                    >Contacting..</template
                  >
                  <template v-else>Contact</template>
                </span>
              </div>
            </button>
            <button
              @click="hireCandidate(candidate, index)"
              class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
            >
              <div class="flex">
                <vue-simple-spinner
                  v-if="actionButtonsStatus[index].hire"
                  size="small"
                  class="mt-1"
                />
                <span class="ml-3">
                  <template v-if="candidate.hired_by">Hired</template>
                  <template v-else>
                    <template v-if="actionButtonsStatus[index].hire"
                      >Hiring..</template
                    >
                    <template v-else>Hire</template>
                  </template>
                </span>
              </div>
            </button>
          </template>
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
      actionButtonsStatus: [],
    };
  },

  async created() {
    await this.fetchCadidatesListAndCoins();

    for (let i = 0; i < this.candidates.length; i++) {
      this.actionButtonsStatus.push({
        contact: false,
        hire: false,
      });
    }
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

    async contactCandidate(candidate, index) {
      if (this.coins < 5) {
        alert("You don't have enough coins to contact this candidate");
        return;
      } else {
        this.actionButtonsStatus[index].contact = true;
        const formData = new FormData();
        formData.append("candidate_id", candidate.id);
        formData.append("candidate_name", candidate.name);
        formData.append("company_id", this.$route.params.id);
        formData.append("candidate_email", candidate.email);
        try {
          const res = await axios.post(`/api/contact-with-candidate`, formData);
          if (res.data.success) {
            this.coins = this.coins -= 5;
            alert("Email sent successfully");
          }
        } catch (e) {
          alert(e);
        }
        this.actionButtonsStatus[index].contact = false;
      }
    },
    async hireCandidate(candidate, index) {
      this.actionButtonsStatus[index].hire = true;
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
              this.coins = this.coins += 5;
            }
          });
        }
        alert("Candidate hired successfully");
      } catch (e) {
        alert("You can't hire this candidate");
      }
      this.actionButtonsStatus[index].hire = false;
    },
  },
};
</script>
