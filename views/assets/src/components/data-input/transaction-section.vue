<script>

import TransactionMixin from "@components/data-input/mixin.js";
import SimpleField from "@components/form-components/simple-field.vue";
import DropdownField from "@components/form-components/dropdown-field.vue";
import AddNewForm from "@components/form-components/add-new-form.vue";

export default {
  mixins: [TransactionMixin],
  components: {
    AddNewForm,
    SimpleField,
    DropdownField
  },
  props: {
    trans: {
      type: Object,
      required: false
    },
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    shows(){
      const clientID = this.trans.client_id ?? false;
      if (!clientID) return [];
      return this.getClientShows(clientID);
    },
    places(){
      return this.getPlaces(1);
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
      ],
    }
  },
  methods: {
    getPlaces(type) {
      const showID = this.trans.show_id ?? false;
      if (!showID) return [];
      if (this.shows.find(s => s.id === showID)) {
        const places = this.shows.find(s => s.id === showID).places;
        return places.filter(p => parseInt(p.type) === type);
      }
      return [];
    },
    getClientShows(clientID){
      const shows = this.$store.state.shows;
      console.log("All shows: ", shows);
      const clientShows = shows.filter(s => s.client_id === clientID);
      console.log("All client shows: ", clientShows);
      return clientShows;
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
  <div class="btb-transaction-body mt-2">
      <div class="row">
        <DropdownField :read-only="readOnly" field="client" :choices="clients" v-model="trans.client_id" :required="true">
          <add-new-form
              slot="add-new"
              :add-new-fn="addClient"
              field="client"></add-new-form>
        </DropdownField>
        <DropdownField :read-only="readOnly" field="carrier" :choices="carriers" :add-new-fn="addCarrier"  v-model="trans.carrier_id">
          <add-new-form
              slot="add-new"
              :add-new-fn="addCarrier"
              field="carrier"></add-new-form>
        </DropdownField>
        <DropdownField :read-only="readOnly" field="freight" :choices="freightTypes" v-model="trans.freight_type"/>
      </div>
      <div class="row">
        <DropdownField :read-only="readOnly" field="show" :choices="shows" v-model="trans.show_id" :is-show="true" :required="true">
          <add-new-form
              slot="add-new" :is-show="true"
              :add-new-fn="addShow" field="show"
              :clients="clients"></add-new-form>
        </DropdownField>
        <SimpleField :read-only="readOnly" type="text" field="shipment"  v-model="trans.shipment"/>
        <SimpleField :read-only="readOnly" type="text" field="pallet" v-model="trans.pallet_no"/>
      </div>
      <div class="row">
        <DropdownField :read-only="readOnly" field="shipper" :choices="shippers" :add-new-fn="addShipper"  v-model="trans.shipper_id" :required="true">
          <add-new-form
              slot="add-new"
              :add-new-fn="addShipper"
              field="shipper"></add-new-form>
        </DropdownField>
        <SimpleField :read-only="readOnly" type="text" field="tracking"  v-model="trans.tracking"/>
        <SimpleField :read-only="readOnly" type="text" field="receiver" v-model="trans.receiver"/>
      </div>
      <div class="row">
        <DropdownField :read-only="readOnly" field="exhibitor" :choices="exhibitors" :add-new-fn="addExhibitor"  v-model="trans.exhibitor_id">
          <add-new-form
              slot="add-new"
              :add-new-fn="addExhibitor"
              field="exhibitor"></add-new-form>
        </DropdownField>
        <DropdownField :read-only="readOnly" field="Zone/Color"  v-model="trans.place" :choices="places"/>
        <SimpleField :read-only="readOnly" type="text" field="trailer" v-model="trans.trailer"/>
      </div>
    </div>
</template>

<style lang="less">

</style>
