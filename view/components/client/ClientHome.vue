<script>
import LookupMixin from "@/components/lookup/mixin";
import ClientMixin from "@/components/client/mixin";
import LookupTable from "@/components/lookup/lookup-table.vue";
import U from "@utils/UserUtility.js";

export default {
  components: {LookupTable},
  mixins: [LookupMixin, ClientMixin],
  computed: {
    lookupResults() {
      return this.$store.state.transactions;
    },
    user() {
      return this.$store.state.user;
    },
    client_id() {
      return this.$store.state.clientId;
    },
    update() {
      return this.$store.state.update;
    }
  },
  provide() {
    return {
      admin: false
    }
  },
  watch: {
    client_id() {
      this.getTransactions({client_id: this.client_id});
    },
    update() {
      this.$store.commit('setUpdate', false);
      this.getTransactions({client_id: this.client_id});
    },
    user() {
      if (!this.user.roles.includes("subscriber")) {
        console.log("Redirecting to admin home");
        this.$router.push({ name: "input" });
      }
    }
  },
  created() {
    this.getClientId(this.user);
    U.getCurrentUser();
  }
}
</script>

<template>
  <div class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h3>Client Transactions</h3>
    </div>
    <lookup-table v-if="client_id" :lookupResults="lookupResults"/>
    <div v-else>
      <b>It seems you're not registered.</b>
      <p>Go to the settings page to enter your client code and register to see your transactions.</p>
    </div>
  </div>
</template>

