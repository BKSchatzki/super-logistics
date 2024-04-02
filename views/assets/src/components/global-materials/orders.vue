<script>
import MaterialsMixin from "@components/global-materials/mixin.js";
import Order from "@components/global-materials/order.vue";
import Vendor from "@components/global-materials/vendor.vue";

export default {
  name: "orders",
  components: {Order, Vendor},
  mixins: [MaterialsMixin],
  computed: {
    orders() { return this.$store.state.materialOrders },
    vendors() { return this.$store.state.materialVendors },
    users() { return this.$store.state.users }
  },
  watch: {
    orders() {
      console.log("Orders updated: ", this.orders);
    }
  }
}
</script>

<template>
  <div id="material-orders">
    <div class="mo-container" v-for="v in vendors">
      <vendor :vendor="v"></vendor>
      <div v-if="v.orders && v.orders.length" v-bind:key="v.id">
        <ul>
          <order v-for="order in v.orders" :key="order.id" :order="order"></order>
        </ul>
      </div>
      <p class="mo-lame-msg"v-else>
        No orders placed yet with this vendor.
      </p>
    </div>
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
