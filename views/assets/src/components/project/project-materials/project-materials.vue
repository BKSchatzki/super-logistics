<script>
import pmMenu from "@components/common/menu.vue";
import pmHeader from "@components/common/header.vue";
import orders from "@components/material-ordering/orders.vue";
import addNew from "@components/material-ordering/add-new.vue";
import MaterialsMixin from "@components/material-ordering/mixin.js";

export default {
  components: { pmMenu, pmHeader, orders, addNew },
  computed: {
    project() { return this.$store.state.project }
  },
  mixins: [MaterialsMixin],
  created() {
    this.getMaterialOrders();
    this.getMaterialVendors();
    this.getUsers();
  }
}
</script>

<template>
  <div class="pm-wrap pm pm-front-end">
    <pm-header></pm-header>
    <pm-menu></pm-menu>
    <div class="pm-materials-container">
      <p>Orders relevant to this project.</p>
      <div class="content">
        <orders class="orders" :project="project"></orders>
        <add-new class="add-new"></add-new>
      </div>
    </div>
  </div>

</template>

<style lang="less">
  .pm-materials-container {
    display: flex;
    flex-direction: column;
    margin-top: 0;
    padding: 20px;
    border: 1px solid #E5E4E4;
    background: #FAFAFA;
  }

  .content {
    display: flex;
    flex-direction: row;
  }

  .orders {
    flex: 0.618;
  }

  .add-new {
    flex: 0.382;
  }

  .materials-input {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
  }
</style>
