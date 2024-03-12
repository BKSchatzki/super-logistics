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
      const orders = this.$store.state.materialOrders;
      const vendors = [...this.$store.state.materialVendors];

      vendors.forEach(vendor => {
        vendor.orders = orders.filter(order => order.vendor_id === vendor.id);
      });

      return vendors;
    },
    users() {
      return this.$store.state.users;
    }
  },
  mounted() {
    this.getMaterialOrders().then(() => {
      this.isLoading = false;
    })
    console.log("orders in component: ", this.orders);
    console.log("vendors in component: ", this.vendors);
  },
  data() {
    return {
      isLoading: true
    }
  }
}

</script>

<template>
  <h2>Orders</h2>
  <p>View existing orders here.</p>
  <div v-if="isLoading">
    Loading...
  </div>
  <div v-else v-for="vendor in vendors">
    <h3>{{ vendor.name }}</h3>
    <div v-if="vendor.orders && vendor.orders.length" v-bind:key="vendor.id">
      <ul>
        <li v-for="order in vendor.orders" v-bind:key="order.id">
          <div>
            <h4>{{ order.title }}</h4>
            <p>{{ order.description }}</p>
            <p>{{ order.cost }}</p>
            <p>{{ order.date }}</p>
          </div>
        </li>
      </ul>
      <p v-if="vendor.orders.length === 0">No orders placed yet with this vendor.</p>
    </div>
  </div>
  <div v-if="vendors.length === 0" v-bind:key="vendor.id">
    <h3>No materials ordered to date.</h3>
  </div>
</template>

<style scoped lang="less">

</style>
