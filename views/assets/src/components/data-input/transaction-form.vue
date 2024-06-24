<script>
import ImageField from "@components/form-components/image-field.vue"
import ItemsSection from "@components/data-input/items-section.vue"
import TransactionSection from "@components/data-input/transaction-section.vue"
import NotesField from "@components/form-components/notes-field.vue"
import dataMixin from "@components/data-input/mixin.js"

export default {
  components: {ImageField, ItemsSection, TransactionSection, NotesField},
  mixins: [dataMixin],
  props: {
    initTrans: {
      type: Object,
      required: false
    },
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return ({
      transaction: {
        show_id: "",
        client_id: "",
        carrier_id: "",
        shipper_id: "",
        exhibitor_id: "",
        shipment: "",
        tracking: "",
        place: "",
        freight_type: "",
        pallet_no: "",
        receiver: "",
        trailer: "",
        note: "",
        image_path: "",
        billable_weight: "",
        items: {
          crates: {
            label: "Crates",
            type: 3,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          cartons: {
            label: "Cartons",
            type: 2,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          skids: {
            label: "Skids",
            type: 4,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          fiberCases: {
            label: "Fiber Cases",
            type: 5,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          carpets: {
            label: "Carpets",
            type: 6,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          misc: {
            label: "Misc",
            type: 7,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
        }
      },
      lookup: false
    })
  },
  methods: {
    addEntry() {
      this.addTransaction(this.transaction)
      this.clearTransaction()
    },
    clearTransaction() {
      this.transaction = {
        show_id: "",
        client_id: "",
        carrier_id: "",
        shipper_id: "",
        exhibitor: "",
        shipment: "",
        tracking: "",
        place: "",
        freight_type: "",
        pallet_no: "",
        receiver: "",
        trailer: "",
        image_path: "",
        billable_weight: "",
        remarks: ""
      }
      this.items = {
        items: {
          crates: {
            label: "Crates",
            type: 3,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          cartons: {
            label: "Cartons",
            type: 2,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          skids: {
            label: "Skids",
            type: 4,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          fiberCases: {
            label: "Fiber Cases",
            type: 5,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          carpets: {
            label: "Carpets",
            type: 6,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          },
          misc: {
            label: "Misc",
            type: 7,
            pcs: 0,
            bols: 0,
            weight: 0,
            notes: "",
            tracking: ""
          }
        }
      }
    },
    triggerLookup() {
      const searchQueries = {};
      for (let key in this.transaction) {
        if (this.transaction[key] !== "" && key !== "items") {
          searchQueries[key] = this.transaction[key];
        }
      }
      this.$emit("trigger-lookup", searchQueries)
    },
    updateItems(items) {
      this.transaction.items = items;
    },
    updateBillableWeight(weight) {
      this.transaction.billable_weight = weight;
    },
    updateTrans() {
      this.updateTransaction(this.transaction);
    },
    getImage() {
      if (this.initTrans && this.initTrans.updates) {
        const sortedUpdates = this.initTrans.updates.sort((a, b) => new Date(b.datetime) - new Date(a.datetime));
        const mostRecentUpdate = sortedUpdates[0];
        return mostRecentUpdate ? this.filePathToUrl(mostRecentUpdate.image_path) : "";
      }
      return "";
    },
    getNote() {
      if (this.initTrans && this.initTrans.updates) {
        const sortedUpdates = this.initTrans.updates.sort((a, b) => new Date(b.datetime) - new Date(a.datetime));
        const mostRecentUpdate = sortedUpdates[0];
        return mostRecentUpdate ? mostRecentUpdate.note : "";
      }
      return "";
    },
    setInitData() {
      this.transaction.id = this.initTrans.id;
      this.transaction.show_id = this.initTrans.show_id;
      this.transaction.client_id = this.initTrans.client_id;
      this.transaction.carrier_id = this.initTrans.carrier_id;
      this.transaction.shipper_id = this.initTrans.shipper_id;
      this.transaction.exhibitor_id = this.initTrans.exhibitor_id;
      this.transaction.shipment = this.initTrans.shipment;
      this.transaction.tracking = this.initTrans.tracking;
      this.transaction.place = this.initTrans.place;
      this.transaction.freight_type = this.initTrans.freight_type;
      this.transaction.pallet_no = this.initTrans.pallet_no;
      this.transaction.receiver = this.initTrans.receiver;
      this.transaction.trailer = this.initTrans.trailer;
      this.transaction.billable_weight = this.initTrans.billable_weight;
      this.transaction.items.crates.label = this.initTrans.items.crates.label;
      this.transaction.items.crates.type = this.initTrans.items.crates.type;
      this.transaction.items.crates.pcs = this.initTrans.items.crates.pcs;
      this.transaction.items.crates.bols = this.initTrans.items.crates.bols;
      this.transaction.items.crates.weight = this.initTrans.items.crates.weight;
      this.transaction.items.crates.notes = this.initTrans.items.crates.notes;
      this.transaction.items.crates.tracking = this.initTrans.items.crates.tracking;
      this.transaction.items.cartons.label = this.initTrans.items.cartons.label;
      this.transaction.items.cartons.type = this.initTrans.items.cartons.type;
      this.transaction.items.cartons.pcs = this.initTrans.items.cartons.pcs;
      this.transaction.items.cartons.bols = this.initTrans.items.cartons.bols;
      this.transaction.items.cartons.weight = this.initTrans.items.cartons.weight;
      this.transaction.items.cartons.notes = this.initTrans.items.cartons.notes;
      this.transaction.items.cartons.tracking = this.initTrans.items.cartons.tracking;
      this.transaction.items.skids.label = this.initTrans.items.skids.label;
      this.transaction.items.skids.type = this.initTrans.items.skids.type;
      this.transaction.items.skids.pcs = this.initTrans.items.skids.pcs;
      this.transaction.items.skids.bols = this.initTrans.items.skids.bols;
      this.transaction.items.skids.weight = this.initTrans.items.skids.weight;
      this.transaction.items.skids.notes = this.initTrans.items.skids.notes;
      this.transaction.items.skids.tracking = this.initTrans.items.skids.tracking;
      this.transaction.items.fiberCases.label = this.initTrans.items.fiberCases.label;
      this.transaction.items.fiberCases.type = this.initTrans.items.fiberCases.type;
      this.transaction.items.fiberCases.pcs = this.initTrans.items.fiberCases.pcs;
      this.transaction.items.fiberCases.bols = this.initTrans.items.fiberCases.bols;
      this.transaction.items.fiberCases.weight = this.initTrans.items.fiberCases.weight;
      this.transaction.items.fiberCases.notes = this.initTrans.items.fiberCases.notes;
      this.transaction.items.fiberCases.tracking = this.initTrans.items.fiberCases.tracking;
      this.transaction.items.carpets.label = this.initTrans.items.carpets.label;
      this.transaction.items.carpets.type = this.initTrans.items.carpets.type;
      this.transaction.items.carpets.pcs = this.initTrans.items.carpets.pcs;
      this.transaction.items.carpets.bols = this.initTrans.items.carpets.bols;
      this.transaction.items.carpets.weight = this.initTrans.items.carpets.weight;
      this.transaction.items.carpets.notes = this.initTrans.items.carpets.notes;
      this.transaction.items.carpets.tracking = this.initTrans.items.carpets.tracking;
      this.transaction.items.misc.label = this.initTrans.items.misc.label;
      this.transaction.items.misc.type = this.initTrans.items.misc.type;
      this.transaction.items.misc.pcs = this.initTrans.items.misc.pcs;
      this.transaction.items.misc.bols = this.initTrans.items.misc.bols;
      this.transaction.items.misc.weight = this.initTrans.items.misc.weight;
      this.transaction.items.misc.notes = this.initTrans.items.misc.notes;
      this.transaction.items.misc.tracking = this.initTrans.items.misc.tracking;
    }
  },
  created() {
    this.transaction.note = this.getNote();
    this.transaction.image_path = this.getImage();
    if (this.initTrans) {
      this.setInitData();
    }
  },
  watch: {
    initTrans() {
      this.setInitData();
      this.transaction.note = this.getNote();
      this.transaction.image_path = this.getImage();
    }
  }
}
</script>

<template>
  <form class="btb" @submit.prevent>
    <div class="row">
      <div class="col">
        <ImageField :read-only="readOnly" v-model="transaction.image_path"/>
      </div>
      <div class="col">
        <notes-field :read-only="readOnly" v-model="transaction.note"/>
      </div>
    </div>
    <div class="row">
      <transaction-section :read-only="readOnly" :trans="transaction"/>
    </div>
    <div class="row">
      <items-section :read-only="readOnly"
                    :items="transaction.items"
                     @update-items="updateItems"
                     @update-billable-weight="updateBillableWeight"
      />
    </div>
    <div v-if="!readOnly" class="mt-2 g-2">
      <button v-if="!initTrans" @click="clearTransaction" class="btn btn-secondary btn-large mt-2">Cancel</button>
      <button v-if="!initTrans" @click="addEntry" class="btn btn-primary btn-large mt-2">Submit</button>
      <button v-if="!initTrans" @click="triggerLookup" class="btn btn-primary btn-large mt-2">Lookup</button>
      <button v-if="initTrans" @click="updateTrans" class="btn btn-primary btn-large mt-2">Update</button>
    </div>
  </form>
</template>

<style lang="less">
.btb {
  width: 100%;
}

h3, h4 {
  font-family: "Roboto", sans-serif;
  color: #82878d;
}

</style>
