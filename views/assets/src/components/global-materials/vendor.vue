<script>
import "@assets/css/variables.css";
import MaterialsMixin from "@components/global-materials/mixin.js";
export default {
  name: "vendor.vue",
  mixins: [MaterialsMixin],
  props: {
    vendor: {
      type: Object,
      required: true,
      validator: function (vendor) {
        return vendor.hasOwnProperty('name') &&
                vendor.hasOwnProperty('id') &&
                vendor.hasOwnProperty('phone') &&
                vendor.hasOwnProperty('email') &&
                vendor.hasOwnProperty('address');
      }
    }
  },
  methods: {
    showCard() {
      this.isCardVisible = !this.isCardVisible;
    },
    deleteVendor() {
      this.deleteMaterialVendor(this.vendor.id);
      this.isCardVisible = false;
    }
  },
  data() {
    return {
      isCardVisible: false,
    }
  }
}
</script>

<template>
  <div class="mv-component">
    <button class="mv-btn" @click="showCard">
      <b class="mv-name">{{ vendor.name }}</b>
    </button>
    <div v-if="isCardVisible" class="mv-container">
      <b class="mv-name">Vendor Info for {{ vendor.name }}</b>
      <p class="mv-detail">{{ vendor.description }}</p>
      <div class="mv-line">
        <b class="mv-text">Phone: </b>
        <p class="mv-text">{{ vendor.phone }}</p>
      </div>
      <div class="mv-line">
        <b class="mv-text">Email: </b>
        <p class="mv-text">{{ vendor.email }}</p>
        </div>
      <div class="mv-line">
        <b class="mv-text">Address: </b>
        <p class="mv-text">{{ vendor.address }}</p>
      </div>
      <button @click="deleteVendor">Delete</button>
      <button @click="isCardVisible = false">Close</button>
    </div>
  </div>
</template>

<style scoped lang="less">
.mv-component {
  max-width: 600px;
  padding: 10px 10px 10px 0;
  margin: 0;
}
.mv-container {
  position: absolute;
  border: 1px solid;
  padding: 10px;
  background-color: whitesmoke;
  width: 50%;
  min-height: 300px;
  max-height: 800px;
  top: 40%; /* Position it in the middle of the screen vertically */
  left: 50%; /* Position it in the middle of the screen horizontally */
  transform: translate(-50%, -50%); /* Offset the container's position by half its width and height to truly center it */
  z-index: 1000; /* Ensure the card appears above other elements */
}
.mv-btn {
  border: none;
  background: none;
}
.mv-btn:hover {
  cursor: pointer;
  color: var(--blue-accent);
}
.mv-name {
  display: block;
  color: black;
  font-weight: bold;
  font-size: 1.3rem;
}
.mv-detail {
  display: block;
  margin: 10px 0;
}
.mv-line {
  display: block;
  margin-bottom: 1em;
}
.mv-text {
  display: inline-block;
  margin-right: 0.5em;
}
</style>
