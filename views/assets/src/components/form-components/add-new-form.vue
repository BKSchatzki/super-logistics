<script>
import SimpleField from "@components/form-components/simple-field.vue";
import MultiCreateField from "@components/form-components/multi-create-field.vue";

export default {
  components: {MultiCreateField, SimpleField},
  props: {
    field: {
      type: String,
      required: true
    },
    addNewFn: {
      type: Function,
      required: true
    },
    isShow: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    zones() {
      return this.places.filter(p => p.type === 1);
    },
    colors() {
      return this.places.filter(p => p.type === 2);
    }
  },
  data() {
    return ({
      open: false,
      name: "",
      dateStart: null,
      dateEnd: null,
      dateExpiry: null,
      places: [],
      phone: "",
      email: "",
      address: "",
      city: "",
      state: "",
      zip: "",
      floorPlanFile: null,
      logoFile: null,
    });
  },
  methods: {
    clearForm() {
      this.name = "";
      this.dateStart = null;
      this.dateEnd = null;
      this.dateExpiry = null;
      this.places = [];
      this.phone = "";
      this.email = "";
      this.address = "";
      this.city = "";
      this.state = "";
      this.zip = "";
      this.floorPlanFile = null;
      this.logoFile = null;
    },
    toggleForm() {
      this.open = !this.open;
    },
    addPlace(name, type) {
      if (!name || name === '') return;
      this.places.push({ name, type });
    },
    removePlace(name, type) {
      const index = this.places.findIndex(p => p.type === type && p.name === name);
      if (index !== -1) this.places.splice(index, 1);
    },
    getLogoFile(e) {
      this.logoFile = e.target.files[0];
      console.log("logoFile: ", typeof(this.logoFile));
    },
    getFloorPlanFile(e) {
      this.floorPlanFile = e.target.files[0];
      console.log("floorPlanFile: ", typeof(this.floorPlanFile));
    },
    submit() {
      this.addNewFn({
        name: this.name,
        dateStart: this.dateStart,
        dateEnd: this.dateEnd,
        dateExpiry: this.dateExpiry,
        places: this.places,
        phone: this.phone,
        email: this.email,
        address: this.address,
        city: this.city,
        state: this.state,
        zip: this.zip,
        floorPlanFile: this.floorPlanFile,
        logoFile: this.logoFile,
      });
      this.clearForm();
      this.toggleForm();
    },
    cancel() {
      this.clearForm();
      this.toggleForm();
    },
    handleKeydown(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
      }
    }
  },
  mounted() {
    window.addEventListener('keydown', this.handleKeydown);
  },
  beforeDestroy() {
    window.removeEventListener('keydown', this.handleKeydown);
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
          <div v-if="isShow" class="mb-3 row">
            <simple-field type="date" v-model="dateStart" field="start" :required="true"/>
            <simple-field type="date" v-model="dateEnd" field="end"/>
            <simple-field type="date" v-model="dateExpiry" field="expires"/>
          </div>
          <div v-if="isShow" class="mb-3 row">
            <multi-create-field
                field="zone"
                :add-place="(name) => addPlace(name, 1)"
                :remove-place="(name) => removePlace(name, 1)"
                :places="zones"/>
            <multi-create-field
                field="color"
                :add-place="(name) => addPlace(name, 2)"
                :remove-place="(name) => removePlace(name, 2)"
                :places="colors"/>
          </div>
          <div class="mb-3 row">
            <simple-field type="text" v-model="name" field="name" :required="true"/>
          </div>
          <div class="mb-3 row">
            <simple-field type="text" v-model="phone" field="phone"/>
            <simple-field type="text" v-model="email" field="email"/>
          </div>
          <div class="mb-3 row">
            <simple-field type="text" v-model="address" field="address"/>
          </div>
          <div class="mb-3 row">
            <simple-field type="text" v-model="city" field="city"/>
            <simple-field type="text" v-model="state" field="state"/>
            <simple-field type="text" v-model="zip" field="zip"/>
          </div>
          <div v-if="isShow" class="mb-3 row">
            <div class="col btb-field">
              <label for="formFile" class="form-label">Floor Plan</label>
              <input v-on:change="getFloorPlanFile" class="form-control" type="file" id="formFile">
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col btb-field">
              <label for="formFile" class="form-label">Logo</label>
              <input v-on:change="getLogoFile" class="form-control" type="file" id="formFile">
            </div>
          </div>
          <div>
            <button type="button" class="btn btn-secondary me-2" @click="cancel">Cancel</button>
            <button type="submit" class="btn btn-primary" @click="submit">Submit</button>
          </div>
        </div>
      </div>
    </div>
    <button type="button" class="add-new fa-solid fa-circle-plus ms-2" @click="toggleForm"></button>
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
