<script>
import ItemsSection from "@components/transaction-form/items-section.vue";
import TransactionSection from "@components/transaction-form/transaction-section.vue";
import DropdownField from "@components/form-components/dropdown-field.vue";
import ImageField from "@components/form-components/image-field.vue";
import TransactionMixin from "@components/transaction-form/mixin.js";

export default {
  components: {
    TransactionSection,
    ItemsSection,
    DropdownField,
    ImageField
  },
  mixins: [TransactionMixin],
  data() {
    return ({
      transaction: {
        show: "",
        client: "",
        carrier: "",
        shipper: "",
        exhibitor: "",
        shipment: "",
        tracking: "",
        zone: "",
        color: "",
        freightType: "",
        pallet: "",
        receiver: "",
        trailer: "",
        image: "",
        remarks: ""
      },
      items: {
        crates: {
          label: "Crates",
          type: 3,
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        cartons: {
          label: "Cartons",
          type: 2,
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        skids: {
          label: "Skids",
          type: 4,
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        fiberCases: {
          label: "Fiber Cases",
          type: 5,
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        carpets: {
          label: "Carpets",
          type: 6,
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        misc: {
          label: "Misc",
          type: 7,
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        weight: 0
      },
    })
  },
  methods: {
    addEntry() {
      this.addTransaction(this.transaction, this.items)
    }
  }
}
</script>

<template>
  <form class="btb" @submit.prevent>
    <h3 class="mb-2">New Transaction</h3>
    <hr>
    <div class="row g-3 mx-1">
      <div class="col-9">
        <TransactionSection
            :trans="transaction"
            :clients="clients"
            :carriers="carriers"
            :shippers="shippers"
            :exhibitors="exhibitors"/>
        <ItemsSection :items="items" :weight="weight" :special-handling="true"/>
      </div>
      <div class="col-3">
        <div class="btb-image-notes">
          <div class="mb-2">
            <h4>Image</h4>
          </div>
          <ImageField v-model="transaction.image"/>
          <div class="my-2">
            <h4>Remarks</h4>
          </div>
          <textarea v-model="transaction.remarks" class="form-control" rows="5"></textarea>
        </div>
      </div>
    </div>
    <button @click="addEntry" class="btn btn-primary btn-large mt-2">Submit</button>
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

.btb-field {
  display: flex;
  width: 200px;
  justify-content: space-between;
  align-items: center;
}

</style>
