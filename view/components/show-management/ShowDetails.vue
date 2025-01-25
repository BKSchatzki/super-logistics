<script setup>
import EditShowForm from "@/components/show-management/EditShowForm.vue";
import LabeledDetail from "@/components/data-management/LabeledDetail.vue";
import ManageDetails from "@/components/data-management/ManageDetails.vue";

const props = defineProps({
  subject: Object
})
</script>

<template>
  <ManageDetails :subject="props.subject" topic="shows"
                 :allowEdit="true" :allowTrash="true" :allowArchive="true">
    <template #detail-body="{subject}">
      <LabeledDetail :subject="subject" label="Name" property="name"/>
      <LabeledDetail :subject="subject" label="Client" property="client.name"/>
      <LabeledDetail :subject="subject" label="Adv. Warehouse Start" property="date_start"/>
      <LabeledDetail :subject="subject" label="Items Late By" property="date_end"/>
      <LabeledDetail :subject="subject" label="Status" property="active"/>
      <LabeledDetail :subject="subject" label="Min. Carat Weight" property="min_carat_weight" unit="lbs."/>
      <LabeledDetail :subject="subject" label="Carat Weight Increment" property="carat_weight_inc" unit="lbs."/>
    </template>
    <template #edit-form="{subject, stop}">
      <EditShowForm :subject :stop/>
    </template>
  </ManageDetails>
</template>