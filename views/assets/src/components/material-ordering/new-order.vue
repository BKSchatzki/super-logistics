<script>

export default {
  name: "new-order",
  methods: {
    recordOrder(event) {
      event.preventDefault();
      console.log("Order recorded");
    }
  },
  props: {
    vendors: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      vendor: "",
      description: "",
      cost: "",
      date: new Intl.DateTimeFormat('en-CA', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date())
    }
  },
  created() {
    console.log(this.vendors);
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
      <!---------------------------- Description ------------------------>
      <label for="description">Description</label>
      <textarea class="materials-input" type="text" name="description" placeholder="Describe your order here" :value="description"></textarea>
      <!------------------------------- Cost ---------------------------->
      <label for="cost">Cost</label>
      <input class="materials-input" type="text" name="cost" :value="cost" required>
      <!------------------------------- Date ---------------------------->
      <label for="date">Date</label>
      <input class="materials-input" type="date" name="date" :value="date" required>
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
