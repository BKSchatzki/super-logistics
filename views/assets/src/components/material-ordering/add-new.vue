<script>
import NewVendor from "@components/material-ordering/new-vendor.vue";
import NewOrder from "@components/material-ordering/new-order.vue";

export default {
  name: "add-new",
  components: {NewVendor, NewOrder},
  computed: {
    vendors() {
      return [
        { id: 1, name: "Vendor 1" },
        { id: 2, name: "Vendor 2" }
      ];
    }
  }
}


</script>

<template>
  <div id="add-new">
    <new-vendor></new-vendor>
    <new-order :vendors="vendors"></new-order>
  </div>
</template>

<style scoped lang="less">

</style>
