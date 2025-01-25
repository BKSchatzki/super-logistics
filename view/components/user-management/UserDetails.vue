<script setup>
import EditUserForm from "@/components/user-management/EditUserForm.vue";
import LabeledDetail from "@/components/data-management/LabeledDetail.vue";
import ManageDetails from "@/components/data-management/ManageDetails.vue";

const props = defineProps({
  subject: Object
})
</script>

<template>
  <ManageDetails :subject="props.subject" topic="users"
                 :allowEdit="true" :allowTrash="true" :allowArchive="true"
                 nameIDkey="user_login">
    <template #detail-body="{subject}">
      <LabeledDetail :subject="subject" label="name" property="name"/>
      <LabeledDetail :subject="subject" label="Username" property="user_login"/>
      <LabeledDetail :subject="subject" label="Role" property="nice_role"/>
      <LabeledDetail v-if="props.subject.client" :subject="subject" label="Client" property="client.name"/>
      <LabeledDetail :subject="subject" label="Email" property="user_email"/>
    </template>
    <template #edit-form="{subject, stop}">
      <EditUserForm :subject :stop/>
    </template>
  </ManageDetails>
</template>