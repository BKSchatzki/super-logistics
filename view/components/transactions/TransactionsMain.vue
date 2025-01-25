<script setup>
import {useAPI} from "@utils/composables/useAPI.js";
import NewTxnForm from "./NewTxnForm.vue";
import Toast from "primevue/toast";
import TxnDetails from "@/components/transactions/TxnDetails.vue";
import {ref, watch} from "vue";

const {get} = useAPI('transactions');
const tableData = get();
const selected = ref(null);
const unselect = () => {
  selected.value = null;
}

watch(tableData, () => {
  console.log("tableData: ", tableData);
}, {immediate: true})

// Open details
const openDetails = (evt) => {
  selected.value = evt.data
}

</script>

<template>
  <div>
    <div class="flex flex-row justify-between mb-4">
      <h1 class="font-sans text-3xl">Transactions</h1>
      <NewTxnForm/>
    </div>
    <TxnDetails v-if="selected" :subject="selected" @close="unselect"/>
    <Toast/>
    <DataTable :value="tableData" paginator :rows="10" @row-click="openDetails" :rowHover="true">
      <template #empty>No transactions to see here. Go ahead and make one!</template>
      <Column field="id">
        <template #body="{data, field}">
          <span class="font-extralight text-gray-500">{{ data[field] }}</span>
        </template>
      </Column>
      <Column field="name" header="Name" />
      <Column field="show" header="Show" >
        <template #body="{data, field}">
          <span>{{ data[field].name }}</span>
        </template>
      </Column>
      <Column field="zone" header="Zone" />
    </DataTable>
  </div>
</template>