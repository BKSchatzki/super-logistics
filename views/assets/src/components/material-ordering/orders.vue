<script>
import MaterialsMixin from "@components/material-ordering/mixin.js";

export default {
  name: "orders",
  mixins: [MaterialsMixin],
  computed: {
    orders() {
      return this.$store.state.materialOrders;
    },
    vendors() {
      return this.$store.state.materialVendors;
    },
  },
  created() {
    this.getMaterialOrders();
    this.getMaterialVendors();
  }
}

</script>

<template>
  <template>
    <h2>Orders</h2>
    <p>View existing orders here.</p>
    <template v-for="vendor in vendors">
      <div v-if="vendor.orders && vendor.orders.length" v-bind:key="vendor.id">
        <h3>Orders from {vendor.name}</h3>
      </div>
      <div v-if="vendors.length === 0" v-bind:key="vendor.id">
        <h3>No materials ordered to date.</h3>
      </div>
    </template>
</template>

<style scoped lang="less">

</style>
