<script>
import MaterialsMixin from "@components/material-ordering/mixin.js";

export default {
  name: "new-order",
  mixins: [MaterialsMixin],
  methods: {
    recordOrder(event) {
      event.preventDefault();
      const newOrder = {
        title: this.title,
        vendor_id: this.vendor,
        description: this.description,
        cost: this.cost,
        date: this.date,
        ordered_by: this.ordered_by
      }
      this.addMaterialOrder(newOrder);
      this.clearForm();
    },
    clearForm() {
      this.title = "";
      this.vendor = "";
      this.description = "";
      this.cost = "";
      this.date = new Intl.DateTimeFormat('en-CA', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date());
      this.ordered_by = null;
    }
  },
  computed: {
    vendors() { return this.$store.state.materialVendors },
    users() { return this.$store.state.users },
    currentUser() { return this.$store.state.currentUser }
  },
  data () {
    return {
      title: "",
      vendor: "",
      description: "",
      cost: "",
      date: new Intl.DateTimeFormat('en-CA', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date()),
      ordered_by: null
    }
  },
  created() {
    this.getCurrentUser();
  },
  mounted() {
    console.log("current user: ", this.currentUser);
    this.ordered_by = this.currentUser.id;
  }
}

</script>

<template>
  <div id="add-new">
    <h2>Add a new Order</h2>
    <form id="new-order-form" @submit="recordOrder">
      <!-------------------------- Select a vendor ---------------------->
      <label for="vendor">Vendor</label>
      <select class="materials-input" name="vendor" v-model="vendor" required>
        <option disabled value="">Please select a vendor</option>
        <option v-for="v in vendors" :value="v.id">{{ v.name }}</option>
      </select>
      <!-------------------------------- Title ---------------------------->
      <label for="title">Title</label>
      <input class="materials-input" type="text" name="title" placeholder="Subject for the order" v-model="title"></input>
      <!---------------------------- Description ------------------------>
      <label for="description">Description</label>
      <textarea class="materials-input" type="text" name="description" placeholder="Describe your order here" v-model="description"></textarea>
      <!------------------------------- Cost ---------------------------->
      <label for="cost">Cost</label>
      <input class="materials-input" type="text" name="cost" v-model="cost" required>
      <!------------------------------- Date ---------------------------->
      <label for="date">Date</label>
      <input class="materials-input" type="date" name="date" v-model="date" required>
      <!----------------------- Who made the order ------------------>
      <label for="orderedBy">Ordered By</label>
      <select class="materials-input" name="orderedBy" v-model="ordered_by" required>
        <option v-for="u in users" :value="u.id">{{ u.display_name }}</option>
      </select>
      <!------------------------------ Submit --------------------------->
      <input type="submit" value="Submit">
    </form>
  </div>
</template>

<style scoped lang="less">

  #new-order-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

</style>
