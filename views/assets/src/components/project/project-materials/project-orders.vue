<script>
import MaterialsMixin from "@components/global-materials/mixin.js";
import Order from "@components/project/project-materials/project-order.vue";
import Vendor from "@components/global-materials/vendor.vue";

export default {
  name: "orders",
  components: {Order, Vendor},
  mixins: [MaterialsMixin],
  props: ["project"],
  computed: {
    orders() { return this.getProjSpecificOrders() },
    vendors() { return this.$store.state.materialVendors },
    users() { return this.$store.state.users }
  },
  methods: {
    getProjSpecificOrders() {
      const orders = this.$store.state.materialOrders;
      console.log("orders: ", orders);
      console.log("project: ", this.project);
      return orders.filter(order => order.projects.some(project => project.id == this.project.id));
    }
  },
  watch: {
    vendors() {
      console.log("vendors: ", this.vendors);
      console.log("orders: ", this.orders);
    }
  }
}
</script>

<template>
  <div id="material-orders">
    <ul>
      <order v-for="o in orders" :key="o.id" :order="o" :vendor="vendors.find(v => v.id == o.vendor_id) || {name: 'loading...'}"></order>
    </ul>
    <div v-if="vendors.length === 0">
      <p class="mo-lame-msg">Record and view all of the orders made for materials here. No materials ordered to date.</p>
    </div>
  </div>
</template>

<style scoped lang="less">
.mo-container {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.mo-lame-msg {
  margin-left: 10px;
}
</style>
