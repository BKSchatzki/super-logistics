<script>
import TransactionSection from "@components/data-input/transaction-section.vue";
import ItemsSection from "@components/data-input/items-section.vue";
import DropdownField from "@components/form-components/dropdown-field.vue";
import ImageField from "@components/form-components/image-field.vue";
import LookupSection from "@components/lookup/lookup-section.vue";
import TransactionMixin from "@components/data-input/mixin.js";

export default {
  components: {
    TransactionSection,
    ItemsSection,
    DropdownField,
    ImageField,
    LookupSection
  },
  mixins: [TransactionMixin],
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

      },
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
      },
      lookup: false
    })
  },
  methods: {
    addEntry() {
      this.addTransaction(this.transaction, this.items)
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
      this.lookup = true;
      setTimeout(() => {
        this.lookup = false;
      }, 10)
    },
    updateItems(items) {
      this.items = items;
    },
    updateBillableWeight(weight) {
      this.transaction.billable_weight = weight;
    }
  }
}
</script>

<template>
  <div>
    <form class="btb" @submit.prevent>
      <h3 class="mb-2">New Transaction</h3>
      <hr>
      <div class="row g-3 mx-1">
        <div class="col-9">
          <transaction-section
              :trans="transaction"
              :clients="clients"
              :carriers="carriers"
              :shippers="shippers"
              :exhibitors="exhibitors"/>
          <items-section
              :items="items"
              @update-items="updateItems"
              @update-billable-weight="updateBillableWeight"
              :special-handling="true"/>
        </div>
        <div class="col-3">
          <div class="btb-image-notes">
            <div class="mb-2">
              <h4>Image</h4>
            </div>
            <ImageField v-model="transaction.image_path"/>
            <div class="my-2">
              <h4>Remarks</h4>
            </div>
            <textarea v-model="transaction.note" class="form-control" rows="5"></textarea>
          </div>
        </div>
      </div>
      <div class="mt-2 g-2">
        <button @click="clearTransaction" class="btn btn-secondary btn-large mt-2">Cancel</button>
        <button @click="addEntry" class="btn btn-primary btn-large mt-2">Submit</button>
        <button @click="triggerLookup" class="btn btn-primary btn-large mt-2">Lookup</button>
      </div>
    </form>
    <lookup-section :lookup="lookup" :form="transaction"></lookup-section>
  </div>
</template>

<style lang="less">
.btb {
  width: 100%;
}
h3, h4 {
  font-family: "Roboto", sans-serif;
  color: #82878d;
}

.btb-field {
  display: flex;
  width: 200px;
  justify-content: space-between;
  align-items: center;
}

</style>
