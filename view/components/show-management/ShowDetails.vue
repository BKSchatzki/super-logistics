<script setup>
import {computed} from 'vue';
import {useStore} from "vuex";
import LabeledDetail from "@/components/data/LabeledDetail.vue";
import ManageDetails from "@/components/data/ManageDetails.vue";
import ShowForm from "@/components/show-management/ShowForm.vue";

//------------------------------------
// Data
//------------------------------------
const props = defineProps({
  subject: Object
})

//------------------------------------
// Permissions
//------------------------------------
const store = useStore();
const user = computed(() => store.state.user);
console.log("ShowDetails User: ", user);
const allowEdit = computed(() => {
  return user.value['isAdmin'];
});
const allowTrash = computed(() => {
  return user.value['isInternalAdmin'];
});
const allowArchive = computed(() => {
  return user.value['isInternalAdmin'];
});
</script>

<template>
  <ManageDetails :subject topic="shows" :allowEdit :allowTrash :allowArchive>
    <template #detail-body="{subject}">
      <LabeledDetail :subject="subject" label="Name" property="name"/>
      <LabeledDetail :subject="subject" label="Client" property="client.name"/>
      <LabeledDetail :subject="subject" label="Adv. Warehouse Start" property="date_start"/>
      <LabeledDetail :subject="subject" label="Items Late By" property="date_end"/>
      <LabeledDetail :subject="subject" label="Status" property="active"/>
      <LabeledDetail :subject="subject" label="Min. Carat Weight" property="min_carat_weight" unit="lbs."/>
      <LabeledDetail :subject="subject" label="Carat Weight Increment" property="carat_weight_inc" unit="lbs."/>
    </template>
    <template #edit-form="{formData, close}">
      <ShowForm :formData :close method="update"/>
    </template>
  </ManageDetails>
</template>