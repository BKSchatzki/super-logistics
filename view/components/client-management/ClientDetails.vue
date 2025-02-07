<script setup>
import ClientForm from "@/components/client-management/ClientForm.vue";
import LabeledDetail from "@/components/data/LabeledDetail.vue";
import ManageDetails from "@/components/data/ManageDetails.vue";

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
    <template #edit-form="{formData, close}">
      <ClientForm :formData :close/>
    </template>
  </ManageDetails>
</template>