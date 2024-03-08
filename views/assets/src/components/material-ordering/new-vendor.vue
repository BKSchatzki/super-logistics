<script>
export default {
  name: "new-vendor",
  data() {
    return {
      name: "",
      description: "",
      phone: "",
      email: "",
      address: ""
    }
  },
  methods: {
    addVendor(event) {
      event.preventDefault();
      console.log("Vendor added");
    }
  }

}
</script>

<template>
  <div>
    <h2>Add a New Vendor</h2>
    <form id="new-vendor-form" @submit="addVendor">
      <!------------------------------- Name ---------------------------->
      <label for="name">Name</label>
      <input class="materials-input" type="text" name="name" :value="name" placeholder="Vendor Name Here" required>
      <!---------------------------- Description ------------------------>
      <label for="description">Description</label>
      <textarea class="materials-input" type="text" name="description" placeholder="A simple description of the vendor and their wares" :value="description"></textarea>
      <!------------------------------- Phone ---------------------------->
      <label for="phone">Phone</label>
      <input class="materials-input" type="number" name="phone" :value="phone" placeholder="000-000-0000" required>
      <!------------------------------- Email ---------------------------->
      <label for="email">Email</label>
      <input class="materials-input" type="number" name="email" :value="email" placeholder="contact@vendor.com" required>
      <!------------------------------ Address --------------------------->
      <label for="address">Address</label>
      <input class="materials-input" type="number" name="address" :value="address" placeholder="Physical Address" required>
      <!------------------------------ Submit --------------------------->
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
