<script setup>
import {ref} from "vue";
import {useForm} from "@utils/composables/useForm.js";
import Toast from "primevue/toast";
import FormModal from "../form/FormModal.vue";
import TransactionForm from "@/components/transactions/TransactionForm.vue";
import TxnDetails from "@/components/transactions/TxnDetails.vue";
import StatusFilters from "@/components/data/StatusFilters.vue";
import SearchBar from "@/components/data/SearchBar.vue";
import {useStatusFilters} from "@utils/composables/useStatusFilters";
import {useDetailsModal} from "@utils/composables/useDetailsModal";
import {FilterMatchMode} from "@primevue/core/api";

const {data, statusBoxes, statusStyles} = useStatusFilters('transactions');
const {selected, unselect, openDetails} = useDetailsModal();

const filters = ref({
  global: {value: null, matchMode: FilterMatchMode.CONTAINS},
  id: {value: null, matchMode: FilterMatchMode.CONTAINS},
  shipper: {value: null, matchMode: FilterMatchMode.IN},
  'show.name': {value: null, matchMode: FilterMatchMode.EQUALS},
  'zone.name': {value: null, matchMode: FilterMatchMode.CONTAINS}
});

// Options
const {getDroptions} = useForm();
const showOptions = getDroptions('shows', {active: 1, trashed: 0})

</script>

<template>
  <div>
    <div class="flex flex-row justify-between mb-4">
      <h1 class="font-sans text-3xl">Transactions</h1>
      <FormModal buttonLabel="New Transaction" header="New Transaction" width="40"
                 description="Create a new transaction from scratch, this is the same as receiving packages without a label!">
        <template #form="{close}">
          <TransactionForm :close/>
        </template>
      </FormModal>
    </div>
    <TxnDetails v-if="selected" :subject="selected" @close="unselect"/>
    <Toast/>
    <DataTable :value="data" paginator :rows="10" @row-click="openDetails" :rowStyle="statusStyles"
               v-model:filters="filters" :rowHover="true" removable-sort filterDisplay="row"
               :globalFilterFields="['id', 'shipper', 'show', 'zone']">
      <template #header>
        <div class="flex justify-start gap-4">
          <SearchBar v-model="filters.global.value" placeholder="Search all fields"/>
          <StatusFilters :checkBoxes="statusBoxes"/>
        </div>
      </template>
      <template #empty>No transactions have been received yet.</template>
      <Column field="id" header="ID">
        <template #body="{data, field}">
          <span class="font-extralight">{{ data[field] }}</span>
        </template>
      </Column>
      <Column field="shipper" header="Shipper">
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                     placeholder="Search by shipper"/>
        </template>
      </Column>
      <Column field="show" header="Show" filterField="show.name">
        <template #body="{data, field}">
          <span>{{ data[field].name }}</span>
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <MultiSelect v-model="filterModel.value" @change="filterCallback()" style="min-width: 12rem"
                       :options="showOptions" optionLabel="label" optionValue="label" placeholder="Any"
                       :maxSelectedLabels="1"/>
        </template>
      </Column>
      <Column field="zone" header="Zone" filterField="zone.name">
        <template #body="{data, field}">
          <span>{{ data[field].name }}</span>
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                     placeholder="Search by zone"/>
        </template>
      </Column>
    </DataTable>
  </div>
</template>