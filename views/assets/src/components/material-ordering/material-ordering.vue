<script>
import Orders from "@components/material-ordering/orders.vue";
import AddNew from "@components/material-ordering/add-new.vue";
import MaterialsMixin from "@components/material-ordering/mixin.js";

export default {
  components: { Orders, AddNew },
  mixins: [MaterialsMixin],
  computed: {
    project() {
      return this.$store.state.project;
    }
  },
  watch: {
    project() {
      console.log("Project changed (material-ordering.vue): ", this.project);
    }
  },
  created() {
    this.getMaterialVendors();
    this.getUsers();
    this.getProjects();
  }
}
</script>

<template>
  <div class="mo-main">
    <h1>Material Orders</h1>
    <p>Keep track of your material ordering by vendor, or make new orders.</p>
    <div class="content">
      <orders class="orders"></orders>
      <add-new class="add-new"></add-new>
    </div>
  </div>
</template>

<style lang="less" scoped>
.mo-main {
  display: flex;
  flex-direction: column;
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
