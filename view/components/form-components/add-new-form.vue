<script>
import SimpleField from "@/components/form-components/simple-field.vue";
import MultiCreateField from "@/components/form-components/multi-create-field.vue";
import DropdownField from "@/components/form-components/dropdown-field.vue";

export default {
  components: {DropdownField, MultiCreateField, SimpleField},
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
    },
    clients: {
      type: Array,
      required: false,
      validator: function(array) {
        return array.every(item =>
            typeof item === 'object' &&
            item !== null &&
            'id' in item &&
            'name' in item
        );
      }
    }
  },
  computed: {
    places() {
      return this.places.filter(p => p.type === 1);
    },
  },
  data() {
    return ({
      open: false,
      name: "",
      dateStart: null,
      dateEnd: null,
      dateExpiry: null,
      minCaratWeight: null,
      caratWeightInc: null,
      clientID: "",
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
      this.name = "",
      this.dateStart = null,
      this.dateEnd = null,
      this.dateExpiry = null,
      this.minCaratWeight = null,
      this.caratWeightInc = null,
      this.clientID = "",
      this.places = [],
      this.phone = "",
      this.email = "",
      this.address = "",
      this.city = "",
      this.state = "",
      this.zip = "",
      this.floorPlanFile = null,
      this.logoFile = null
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
    },
    submit() {
      this.addNewFn({
        name: this.name,
        dateStart: this.dateStart,
        dateEnd: this.dateEnd,
        dateExpiry: this.dateExpiry,
        minCaratWeight: this.minCaratWeight ?? 100,
        caratWeightInc: this.caratWeightInc ?? 100,
        clientID: this.clientID,
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
        if (e.target === '#Zone-Color-field') {
          this.addPlace(e.target.value, 1);
          e.target.value = '';
        }
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
            <dropdown-field :choices="clients" v-model="clientID" field="client" required="true"/>
            <multi-create-field
                field="Zone-Color"
                :add-place="(name) => addPlace(name, 1)"
                :remove-place="(name) => removePlace(name, 1)"
                :places="places"/>
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
          <div class="mb-3 row">
            <div class="col">
              <div v-if="isShow" class="mb-3 row">
                <div class="col btb-field">
                  <label for="formFile" class="form-label">Floor Plan</label>
                  <input v-on:change="getFloorPlanFile" class="form-control" type="file" id="formFile">
                </div>
              </div>
              <div class="mb-3 row">
                <div class="col btb-field">
                  <label for="formFile" class="form-label me-2">Logo</label>
                  <input v-on:change="getLogoFile" class="form-control" type="file" id="formFile">
                </div>
              </div>
            </div>
            <div v-if="isShow" class="col">
              <div class="row mb-3">
                <div class="col">
                  <simple-field v-model="minCaratWeight" field="Minimum Billable Weight" type="number"></simple-field>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <simple-field v-model="caratWeightInc" field="Billable Weight Increments" type="number"></simple-field>
                </div>
              </div>
            </div>
          </div>
          <div>
            <button type="button" class="btn btn-secondary me-2" @click="cancel">Cancel</button>
            <button type="submit" class="btn btn-primary" @click="submit">Submit</button>
          </div>
        </div>
      </div>
    </div>
    <button type="button" class="btn btn-sm" @click="toggleForm">
      <span class="fa-solid fa-circle-plus "></span>
    </button>
  </div>
</template>

<style>
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
