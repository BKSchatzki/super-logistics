<script>
import Orders from "@components/material-ordering/orders.vue";
import AddNew from "@components/material-ordering/add-new.vue";
import MaterialsMixin from "@components/material-ordering/mixin.js";

export default {
  name: "material-ordering",
  mixins: [MaterialsMixin],
  components: {
    Orders,
    AddNew
  },
  created() {
    this.getMaterialVendors();
    this.getUsers();
  }
}
</script>

<template>
  <div>
    <h1>Material Orders</h1>
    <p>Keep track of your material ordering by vendor, or make new orders.</p>
    <orders></orders>
    <add-new></add-new>
  </div>
</template>

<style lang="less">

.materials-input {
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
}
</style>
