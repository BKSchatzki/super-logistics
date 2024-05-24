<script>

import SimpleField from "@components/form-components/simple-field.vue";
import DropdownField from "@components/form-components/dropdown-field.vue";
import TransactionMixin from "@components/transaction-form/mixin.js";

export default {
  mixins: [TransactionMixin],
  components: {
    SimpleField,
    DropdownField
  },
  props: {
    trans: {
      type: Object,
      required: true
    }
  },
  computed: {
    shows(){
      return this.$store.state.shows
    },
    zones(){
      return this.getPlaces(1);
    },
    colors(){
      return this.getPlaces(2);
    },
    clients(){
      return this.$store.state.clients
    },
    carriers(){
      return this.$store.state.carriers
    },
    shippers(){
      return this.$store.state.shippers
    },
    exhibitors(){
      return this.$store.state.exhibitors
    },
  },
  data() {
    return {
      freightTypes: [
        {name: "LTL", id: 1},
        {name: "FTL", id: 2},
        {name: "SmallPkgs", id: 3},
      ]
    }
  },
  methods: {
    getPlaces(type){
      const showID = this.trans.show ?? false;
      if (!showID) return [];
      const places = this.shows.find(s => s.id === showID).places;
      return places.filter(p => parseInt(p.type) === type);
    }
  },
  created() {
    this.getRelevantShows();
    this.getClients();
    this.getCarriers();
    this.getShippers();
    this.getExhibitors();
  }
}
</script>

<template>
  <div>
    <div class="btb-transaction-header mb-2">
      <h4>Details</h4>
    </div>
    <div class="btb-transaction-body">
      <div class="row">
        <DropdownField :choices="shows" :add-new-fn="addShow" v-model="trans.show" field="show" :is-show="true" :required="true"/>
      </div>
      <div class="row">
        <DropdownField field="client" :choices="clients" :add-new-fn="addClient" v-model="trans.client" :required="true"/>
        <SimpleField type="text" field="shipment"  v-model="trans.shipment"/>
        <DropdownField field="freight" :choices="freightTypes" v-model="trans.freightType"/>
      </div>
      <div class="row">
        <DropdownField field="carrier" :choices="carriers" :add-new-fn="addCarrier"  v-model="trans.carrier"/>
        <SimpleField type="text" field="tracking"  v-model="trans.tracking"/>
        <SimpleField type="text" field="pallet" v-model="trans.pallet"/>
      </div>
      <div class="row">
        <DropdownField field="shipper" :choices="shippers" :add-new-fn="addExhibitor"  v-model="trans.shipper" :required="true"/>
        <DropdownField field="zone"  v-model="trans.zone" :choices="zones"/>
        <SimpleField type="text" field="receiver" v-model="trans.receiver"/>
      </div>
      <div class="row">
        <DropdownField field="exhibitor" :choices="exhibitors" :add-new-fn="addShipper"  v-model="trans.exhibitor"/>
        <DropdownField field="color"  v-model="trans.color" :choices="colors"/>
        <SimpleField type="text" field="trailer" v-model="trans.trailer"/>
      </div>
    </div>
  </div>
</template>

<style lang="less">

</style>
