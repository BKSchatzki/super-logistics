<script>
import LookupMixin from "@/components/lookup/mixin";
import LookupTable from "@/components/lookup/lookup-table.vue";

export default {
  components: {LookupTable},
  mixins: [LookupMixin],
  props: {
    lookup: {
      type: Boolean,
    },
    form: {
      type: Object,
      required: false
    }
  },
  data() {
    return ({
      open: false,
      results: []
    })
  },
  computed: {
    lookupResults() {
      return this.$store.state.transactions
    },
    update() {
      return this.$store.state.update;
    }
  },
  provide() {
    return {
      admin: true
    }
  },
  methods: {
    getResults() {
      this.open = true;
      this.getTransactions(this.form);
    },
    close() {
      this.open = false;
    },
    update() {
      this.$store.commit('setUpdate', false);
      this.getTransactions(this.form);
    }
  },
  watch: {
    lookup: function (newVal) {
      if (newVal === true) {
        console.log('searchQueries: ', this.form)
        this.getResults();
      }
    }
  }
}
</script>

<template>
  <div class="mt-5" v-if="open">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h3>Results</h3>
      <button @click="close" type="button" class="btn btn-close" aria-label="Close"></button>
    </div>
    <lookup-table :lookupResults="lookupResults"/>
  </div>
</template>

<style>

</style>
