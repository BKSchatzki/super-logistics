<script setup>
import LabeledDetail from "@/components/data/LabeledDetail.vue";
import ManageDetails from "@/components/data/ManageDetails.vue";
import TransactionForm from "@/components/transactions/TransactionForm.vue";

const props = defineProps({
  subject: Object
})

</script>

<template>
  <ManageDetails topic="transactions" :subject="props.subject">
    <template #detail-body>
      <LabeledDetail :subject label="ID" property="id"/>
      <LabeledDetail :subject label="Shipper" property="shipper"/>
      <LabeledDetail :subject label="Exhibitor" property="exhibitor"/>
      <LabeledDetail :subject label="Show" property="show.name"/>
      <LabeledDetail :subject label="Zone" property="zone.name"/>
      <LabeledDetail :subject label="Booth" property="booth.name"/>
      <LabeledDetail :subject label="Pallet" property="pallet"/>
      <LabeledDetail :subject label="Trailer" property="trailer"/>
      <LabeledDetail :subject label="Carrier" property="carrier"/>
      <LabeledDetail :subject label="Tracking" property="tracking"/>
      <LabeledDetail :subject label="Freight Type" property="freight_type"/>
      <LabeledDetail :subject label="Crates" property="crate_pcs"/>
      <LabeledDetail :subject label="Cartons" property="carton_pcs"/>
      <LabeledDetail :subject label="Skids" property="skid_pcs"/>
      <LabeledDetail :subject label="Fiber Cases" property="fiber_case_pcs"/>
      <LabeledDetail :subject label="Carpets" property="carpet_pcs"/>
      <LabeledDetail :subject label="Misc" property="misc_pcs"/>
      <LabeledDetail :subject label="Total Packages" property="total_pcs"/>
      <LabeledDetail :subject label="Total Weight" property="total_weight"/>
      <LabeledDetail :subject label="Special Handling" property="special_handling"/>
      <LabeledDetail :subject label="Remarks" property="remarks"/>
      <LabeledDetail :subject label="Received" property="created_at"/>
      <LabeledDetail :subject label="Received by" property="created_by_user.name"/>
      <LabeledDetail :subject label="Last Updated" property="updated_at"/>
      <LabeledDetail :subject label="Last Updated by" property="updated_by_user.name"/>
      <LabeledDetail :subject label="Status" property="active"/>
      <LabeledDetail :subject label="Trashed" property="trashed"/>
    </template>
    <template #edit-form="{subject, stop}">
      <TransactionForm :labelData="subject" :close="stop" method="update"/>
    </template>
  </ManageDetails>
</template>