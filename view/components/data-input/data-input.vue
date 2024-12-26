<script>
import LookupSection from "@/components/lookup/lookup-section.vue";
import TransactionForm from "@/components/data-input/transaction-form.vue";
import DataMixin from "@/components/data-input/mixin.js";
import U from "@utils/UserUtility.js";

export default {
  components: {
    TransactionForm,
    LookupSection
  },
  data() {
    return {
      searchQueries: {},
      lookup: false
    }
  },
  computed: {
    user() {
      return this.$store.state.user;
    }
  },
  mixins: [DataMixin],
  methods: {
    triggerLookup(searchQueries) {
      this.searchQueries = searchQueries;
      this.lookup = true;
      setTimeout(() => {
        this.lookup = false;
      }, 10)
    },
    updateItems(items) {
      this.items = items;
    }
  },
  created() {
    U.getCurrentUser();
  },
  watch: {
    user() {
      if (this.user.roles.includes("subscriber")) {
        this.$router.push({ name: "client" });
      }
    }
  }
}
</script>

<template>
  <div>
    <transaction-form @trigger-lookup="triggerLookup" />
    <lookup-section :form="searchQueries" :lookup="lookup"></lookup-section>
  </div>
</template>

<style lang="less">
h3, h4 {
  font-family: "Roboto", sans-serif;
  color: #82878d;
}

.btb-field {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

</style>
