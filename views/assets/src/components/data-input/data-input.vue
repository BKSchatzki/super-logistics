<script>
import LookupSection from "@components/lookup/lookup-section.vue";
import TransactionForm from "@components/data-input/transaction-form.vue";
import DataMixin from "@components/data-input/mixin.js";

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
    this.getCurrentUserRoles();
  },
  watch: {
    user() {
      if (this.user.roles.includes("subscriber")) {
        console.log("Redirecting to client home");
        this.$router.push({ name: "client" });
      }
    }
  }
}
</script>

<template>
  <div>
    <transaction-form :add-entry="addEntry" :clear-transaction="clearTransaction" @trigger-lookup="triggerLookup"
                  :update-billable-weight="updateBillableWeight" :update-items="updateItems"/>
    <lookup-section :lookup="lookup" :form="transaction"></lookup-section>
  </div>
</template>

<style lang="less">
h3, h4 {
  font-family: "Roboto", sans-serif;
  color: #82878d;
}

.btb-field {
  display: flex;
  width: 200px;
  justify-content: space-between;
  align-items: center;
}

</style>
