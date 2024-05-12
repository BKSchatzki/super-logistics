<script>

import TextField from "@components/form-components/text-field.vue";
import DropdownField from "@components/form-components/dropdown-field.vue";

export default {
  props: {
    transaction: {
      type: Object,
      required: true
    }
  },
  components: {
    TextField,
    DropdownField
  },
  data() {
    return ({
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
    });
  }

}
</script>

<template>
  <div>
    <div class="sl-transaction-header mb-2">
      <h4>Transaction Details</h4>
    </div>
    <div class="sl-transaction-body">
      <div class="row">
        <DropdownField field="client" :choices="clientChoices" :add-new="true" v-model="transaction.client"/>
        <TextField field="city" v-model="transaction.city"/>
        <TextField field="shipment"  v-model="transaction.shipment"/>
        <DropdownField field="freight" :choices="freightTypeChoices" v-model="transaction.freightType"/>
      </div>
      <div class="row">
        <DropdownField field="carrier" :choices="carrierChoices" :add-new="true"  v-model="transaction.carrier"/>
        <TextField field="state"  v-model="transaction.state"/>
        <TextField field="tracking"  v-model="transaction.tracking"/>
        <TextField field="pallet" v-model="transaction.pallet"/>
      </div>
      <div class="row">
        <DropdownField field="shipper" :choices="shipperChoices" :add-new="true"  v-model="transaction.shipper"/>
        <TextField field="zip"  v-model="transaction.zip"/>
        <TextField field="zone"  v-model="transaction.zone"/>
        <TextField field="receiver" v-model="transaction.receiver"/>
      </div>
      <div class="row">
        <DropdownField field="exhibitor" :choices="exhibitorChoices" :add-new="true"  v-model="transaction.exhibitor"/>
        <div class="col"></div>
        <TextField field="color"  v-model="transaction.color"/>
        <TextField field="trailer" v-model="transaction.trailer"/>
      </div>
    </div>
  </div>
</template>

<style lang="less">

</style>
