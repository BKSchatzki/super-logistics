<script>
import MaterialsMixin from "@components/global-materials/mixin.js";

export default {
  name: "new-order",
  mixins: [MaterialsMixin],
  methods: {
    recordOrder(event) {
      event.preventDefault();
      console.log("associated projects: ", this.associated_projects);
      const newOrder = {
        title: this.title,
        vendor_id: this.vendor,
        description: this.description,
        cost: this.cost,
        date: this.date,
        ordered_by: this.ordered_by,
        associated_projects: this.associated_projects
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
      this.associated_projects = [];
      this.isFormVisible = false; // hide the form after submission
    },
    showForm() {
      this.isFormVisible = true; // show the form when the button is clicked
    }
  },
  computed: {
    vendors() { return this.$store.state.materialVendors },
    users() {
      return this.$store.state.users;
    },
    currentUser() { return this.$store.state.currentUser },
    projects() { return this.$store.state.projects }
  },
  watch: {
    currentUser() {
      this.ordered_by = this.currentUser.id;
    }
  },
  data () {
    return {
      title: "",
      vendor: "",
      description: "",
      cost: "",
      date: new Intl.DateTimeFormat('en-CA', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date()),
      ordered_by: null,
      associated_projects: [],
      isFormVisible: false
    }
  },
  created() {
    this.getCurrentUser();
  }
}
</script>

<template>
  <div id="add-new-mo">
    <button v-if="!isFormVisible" @click="showForm">Add Order</button>
    <form v-if="isFormVisible" id="new-order-form" @submit="recordOrder">
      <h2>Add a new Order</h2>
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
      <!----------------------- Who made the order ------------------>
      <label for="assocProjects">Associated Projects</label>
      <select class="materials-input" name="assocProjects" v-model="associated_projects" multiple>
        <option v-for="p in projects" :value="p.id">{{ p.title }}</option>
      </select>
      <!------------------------------ Submit --------------------------->
      <input type="submit" value="Submit">
      <button type="button" @click="clearForm">Cancel</button>
    </form>
  </div>
</template>

<style scoped lang="less">

  #add-new-mo {
    margin: 5px;
  }
  #new-order-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

</style>
