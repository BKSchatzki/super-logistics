<script setup>
import EditClientForm from "@/components/client-management/EditClientForm.vue";
import LabeledDetail from "@/components/data-management/LabeledDetail.vue";
import ManageDetails from "@/components/data-management/ManageDetails.vue";

const props = defineProps({
  subject: Object
})
</script>

<template>
  <ManageDetails :subject="props.subject" topic="clients"
                 :allowEdit="true" :allowTrash="true" :allowArchive="true">
    <template #detail-body="{subject}">
      <LabeledDetail :subject label="Name" property="name"/>
      <LabeledDetail :subject label="Status" property="active"/>
    </template>
    <template #edit-form="{subject, stop}">
      <EditClientForm :subject :stop/>
    </template>
  </ManageDetails>
</template>