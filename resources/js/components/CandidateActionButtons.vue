<template>
  <div>
    <button
      @click="contactCandidate(candidate)"
      class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
    >
      <div class="flex">
        <vue-simple-spinner
          v-if="contactReqOnProgress"
          size="small"
          class="mt-1"
        />
        <span>
          <span v-if="!contactReqOnProgress">Contact</span>
          <span v-else class="ml-3">Contacting...</span>
        </span>
      </div>
    </button>
    <button
      @click="hireCandidate(candidate)"
      class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
    >
      <div class="flex">
        <vue-simple-spinner
          v-if="hireReqOnProgress"
          size="small"
          class="mt-1"
        />
        <span :class="hireReqOnProgress ? 'ml - 3' : ''">
          <template v-if="candidate.hired_by">Hired</template>
          <template v-else>
            <span v-if="!hireReqOnProgress">Hire</span>
            <span v-else class="ml-3">Hiring...</span>
          </template>
        </span>
      </div>
    </button>
  </div>
</template>

<script>
import Spinner from "vue-simple-spinner";
export default {
  name: "MztTestAssignmentMainCandidateactionbuttons",

  components: {
    Spinner,
  },

  props: {
    candidate: {
      type: Object,
      required: true,
    },
    contactReqOnProgress: {
      type: Boolean,
      required: true,
    },
    hireReqOnProgress: {
      type: Boolean,
      required: true,
    },
  },

  data() {
    return {
      contactStatusAvailable: true,
      hireStatusAvailable: true,
    };
  },

  methods: {
    contactCandidate() {
      this.$emit("contact-candidate");
    },
    hireCandidate() {
      this.$emit("hire-candidate");
    },
  },
};
</script>

<style lang="scss" scoped></style>
