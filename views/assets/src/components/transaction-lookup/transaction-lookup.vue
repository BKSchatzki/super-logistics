<script>
import LookupMixin from "@components/transaction-lookup/mixin";
import ItemsSection from "@components/form-components/items-section.vue";
import TransactionSection from "@components/form-components/transaction-section.vue";
import TextField from "@components/form-components/text-field.vue";
import DropdownField from "@components/form-components/dropdown-field.vue";
import ImageField from "@components/form-components/image-field.vue";

export default {
  components: {
    TransactionSection,
    ItemsSection,
    DropdownField,
    TextField,
    ImageField
  },
  data() {
    return ({
      transaction: {
        client: "",
        exhibitor: "",
        city: "",
        state: "",
        zip: "",
        zone: "",
        color: "",
        carrier: "",
        shipper: "",
        shipment: "",
        tracking: "",
        freightType: "",
        pallet: "",
        remarks: "",
        receiver: "",
        trailer: "",
        image: "",
        clientChoices: [
          [1, "Kurt Russell"],
          [2, "Russell Brand"],
          [3, "Russell Crowe"]
        ],
        carrierChoices: [
          [1, "FedEx"],
          [2, "Old Dominion"],
          [3, "UPS"]
        ],
        shipperChoices: [
          [1, "ThinkSTG"],
          [2, "ITS Logistics"],
          [3, "The Spacing Guild"],
          [4, "Echo"],
        ],
        exhibitorChoices: [
          [1, "Echo"],
          [2, "Napster"],
          [3, "Nvidia"],
          [4, "The Smashing Pumpkins"],
        ],
        freightTypeChoices: [
          [1, "LTL"],
          [2, "FTL"],
          [3, "Small Pack"]
        ],
      },
      weight: 0,
      items: {
        crates: {
          label: "Crates",
          type: "crates",
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        cartons: {
          label: "Cartons",
          type: "cartons",
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        skids: {
          label: "Skids",
          type: "skids",
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        fiberCases: {
          label: "Fiber Cases",
          type: "fiberCases",
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        carpets: {
          label: "Carpets",
          type: "carpets",
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        },
        misc: {
          label: "Misc",
          type: "misc",
          pieces: 0,
          bols: 0,
          weight: 0,
          notes: "",
          tracking: ""
        }
      }
    })
  },
  mixins: [LookupMixin],
}
</script>

<template>
  <div>
    <form class="sl">
    <h3 class="mb-2">Transaction Lookup</h3>
    <hr>
    <div class="sl-body">
      <div class="sl-transaction-central">
        <TransactionSection :transaction="transaction"/>
        <ItemsSection :items="items" :weight="weight"/>
      </div>
      <div class="sl-transaction-adjacent ms-4">
        <div class="sl-image-notes">
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
    <button class="btn btn-primary btn-large mt-2">Submit</button>
  </form>
    <div class="sl-results">
      Results to populate here
    </div>
  </div>
</template>

<style lang="less">
.sl {
  width: 100%;
}
h3, h4 {
  font-family: "Roboto", sans-serif;
  color: #82878d;
}
.sl-body {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.sl-results {
  margin-top: 20px;
  height: 400px;
  width: 100%;
  border: 1px solid #ccc;
}
.sl-transaction-central {
  flex: 3;
}
.sl-transaction-adjacent {
  flex: 1;
}
.sl-field {
  display: flex;
  width: 200px;
  justify-content: space-between;
  align-items: center;
}

label {
  margin-right: 10px;
}

input {
  width: 100px;
}

select {
  width: 100px;
}
</style>
