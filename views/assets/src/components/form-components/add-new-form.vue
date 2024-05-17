<script>
import TextField from "@components/form-components/text-field.vue";

export default {
  components: {TextField},
  props: {
    field: {
      type: String,
      required: true
    },
    addNewFn: {
      type: Function,
      required: true
    }
  },
  data() {
    return ({
      open: false,
      name: "",
      phone: "",
      email: "",
      address: "",
      city: "",
      state: "",
      zip: "",
      logoFile: null
    });
  },
  methods: {
    clearForm() {
      this.name = "";
      this.phone = "";
      this.email = "";
      this.address = "";
      this.city = "";
      this.state = "";
      this.zip = "";
      this.logoFile = null;
    },
    toggleForm() {
      this.open = !this.open;
    },
    getLogoFile(e) {
      this.logoFile = e.target.files[0];
    },
    submit() {
      this.addNewFn({
        name: this.name,
        phone: this.phone,
        email: this.email,
        address: this.address,
        city: this.city,
        state: this.state,
        zip: this.zip,
        logoFile: this.logoFile
      });
      this.clearForm();
      this.toggleForm();
    }
  }
}
</script>

<template>
  <div>
    <div class="btb-modal container-fluid" v-if="open" @click="toggleForm" tabindex="-1" :aria-labelledby="`add new ${field} form`">
      <div class="btb-modal-content container" @click.stop>
        <div class="btb-modal-header d-flex justify-content-between align-items-center mb-2">
          <h5>Add New {{ field }}</h5>
          <button @click="toggleForm" type="button" class="btn btn-close" aria-label="Close"></button>
        </div>
        <div class="btb-modal-body">
          <div class="mb-3 row">
            <text-field v-model="name" field="name" :required="true"/>
          </div>
          <div class="mb-3 row">
            <text-field v-model="phone" field="phone"/>
            <text-field v-model="email" field="email"/>
          </div>
          <div class="mb-3 row">
            <text-field v-model="address" field="address"/>
          </div>
          <div class="mb-3 row">
            <text-field v-model="city" field="city"/>
            <text-field v-model="state" field="state"/>
            <text-field v-model="zip" field="zip"/>
          </div>
          <div class="mb-3 row">
            <div class="col btb-field">
              <label for="formFile" class="form-label">Logo</label>
              <input v-on:change="getLogoFile" class="form-control" type="file" id="formFile">
            </div>
          </div>
          <div>
            <button class="btn btn-secondary me-2" @click="toggleForm">Cancel</button>
            <button class="btn btn-primary" @click="submit">Submit</button>
          </div>
        </div>
      </div>
    </div>
    <button class="add-new fa-solid fa-circle-plus" @click="toggleForm"></button>
  </div>
</template>

<style scoped lang="less">
.btb-modal {
  position: fixed;
  z-index: 999999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.btb-modal-content {
  background-color: #fefefe;
  margin: 30% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 40rem;
}
</style>
