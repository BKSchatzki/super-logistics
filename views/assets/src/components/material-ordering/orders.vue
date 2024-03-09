<script>
import MaterialsMixin from "@components/material-ordering/mixin.js";

export default {
  name: "orders",
  mixins: [MaterialsMixin],
  computed: {
    orders() {
      return this.$store.state.materialOrders;
    },
    activeVendors() {
      const allVendors = this.$store.state.materialVendors;
      const vendorIds = this.$store.state.materialOrders.map(order => order.vendor_id);

      const uniqueVendorIds = vendorIds.reduce((unique, id) => {
        return unique.includes(id) ? unique : [...unique, id];
      }, []);

      return uniqueVendorIds.map(id => allVendors.find(v => v.id === id));
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
    <template v-for="vendor in activeVendors">
      <div v-if="vendor.orders && vendor.orders.length" v-bind:key="vendor.id">
        <h3>Orders from {vendor.name}</h3>
        <ul>
          <li v-for="order in vendorOrders">{order.title}</li>
        </ul>
      </div>
    </template>
</template>

<style scoped lang="less">

</style>
