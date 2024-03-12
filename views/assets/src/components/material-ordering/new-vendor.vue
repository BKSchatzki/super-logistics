<script>
import MaterialsMixin from "@components/material-ordering/mixin.js";
export default {
  name: "new-vendor",
  mixins: [MaterialsMixin],
  methods: {
    addVendor(event) {
      event.preventDefault();
      const newVendor = {
        name: this.name,
        description: this.description,
        phone: this.phone,
        email: this.email,
        address: this.address
      }
      this.addMaterialVendor(newVendor);
      this.clearFields();
    },
    clearFields() {
      this.name = '';
      this.description = '';
      this.phone = '';
      this.email = '';
      this.address = '';
    }
  },
  data () {
    return {
      name: "",
      description: "",
      phone: "",
      email: "",
      address: ""
    }
  }
}
</script>

<template>
  <div>
  <h2>Add a New Vendor</h2>
  <form id="new-vendor-form" @submit="addVendor">
    <!---------------------------------- Name ------------------------------>
    <label for="name">Name</label>
    <input class="materials-input" type="text" name="name" v-model="name" placeholder="Vendor Name Here" required>
    <!------------------------------- Description ---------------------------->
    <label for="description">Description</label>
    <textarea class="materials-input" type="text" name="description" v-model="description" placeholder="A simple description of the vendor and their wares"></textarea>
    <!--------------------------------- Phone ------------------------------->
    <label for="phone">Phone</label>
    <input class="materials-input" type="number" name="phone" v-model="phone" placeholder="000-000-0000" required>
    <!---------------------------------- Email ------------------------------->
    <label for="email">Email</label>
    <input class="materials-input" type="email" name="email" v-model="email" placeholder="contact@vendor.com">
    <!-------------------------------- Address ------------------------------>
    <label for="address">Address</label>
    <input class="materials-input" type="text" name="address" v-model="address" placeholder="Physical Address" required>
    <!--------------------------------- Submit ------------------------------>
    <input class="materials-input" type="submit" value="Submit">
  </form>
</div>
</template>

<style scoped lang="less">

  #new-vendor-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

</style>
