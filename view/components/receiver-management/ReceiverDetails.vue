<script setup>
import LabeledDetail from "@/components/data/LabeledDetail.vue";
import ManageDetails from "@/components/data/ManageDetails.vue";
import ReceiverForm from "@/components/receiver-management/ReceiverForm.vue";
import Row from "@/components/form/Row.vue";
import {frhtOptions} from "@utils/dropdowns";

const props = defineProps({
  subject: Object
})

</script>

<template>
  <ManageDetails topic="transactions" :subject="props.subject" header="Receiver Details">
    <template #detail-body>
      <Panel>
        <template #header>
          <Row>
            <span class="text-xl font-extralight">Receiver No.</span>
            <span class="text-xl font-extralight">{{ subject['id'] }}</span>
          </Row>
        </template>
        <LabeledDetail :subject label="Shipper" property="shipper"/>
        <LabeledDetail :subject label="Exhibitor" property="exhibitor"/>
        <LabeledDetail :subject label="Show" property="show"/>
        <LabeledDetail :subject label="Zone" property="zone"/>
        <LabeledDetail :subject label="Booth" property="booth"/>
        <LabeledDetail :subject label="Pallet" property="pallet"/>
        <LabeledDetail :subject label="Trailer" property="trailer" falseLabel="Unassigned"/>
        <LabeledDetail :subject label="Carrier" property="carrier"/>
        <LabeledDetail :subject label="Tracking" property="tracking"/>
      </Panel>
      <Panel header="Shipment Information">
        <div class="flex flex-row justify-between mb-0">
          <span class="capitalize align_baseline font-bold">Freight Type:</span>
          <span class="capitalize align_baseline font-extralight">{{
              frhtOptions.find(opt => opt.value = props.subject.freight_type).label
            }}</span>
        </div>
        <LabeledDetail :subject label="Crates" property="crate_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Cartons" property="carton_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Skids" property="skid_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Fiber Cases" property="fiber_case_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Carpets" property="carpet_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Misc" property="misc_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Total Packages" property="total_pcs"/>
        <LabeledDetail :subject label="Total Weight" property="total_weight"/>
      </Panel>
      <Panel header="Handling">
        <LabeledDetail :subject label="Special Handling" property="special_handling"/>
        <LabeledDetail :subject label="Remarks" property="remarks"/>
        <LabeledDetail :subject label="Received" property="nice_created_at"/>
        <LabeledDetail :subject label="Received by" property="created_by_user.user_login"/>
        <LabeledDetail :subject label="Last Updated" property="nice_updated_at"/>
        <LabeledDetail :subject label="Last Updated by" property="updated_by_user.user_login"/>
      </Panel>
      <Panel header="Status">
        <LabeledDetail :subject label="Active" property="active"/>
        <LabeledDetail :subject label="Trashed" property="trashed"/>
      </Panel>
    </template>
    <template #edit-form="{formData, close}">
      <ReceiverForm :labelData="subject" :close method="update"/>
    </template>
  </ManageDetails>
</template>