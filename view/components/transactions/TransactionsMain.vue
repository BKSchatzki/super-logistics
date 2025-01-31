<script setup>
import {computed, ref, watchEffect} from "vue";
import {useForm} from "@utils/composables/useForm.js";
import {useStatusFilters} from "@utils/composables/useStatusFilters";
import {useDetailsModal} from "@utils/composables/useDetailsModal";
import Toast from "primevue/toast";
import FormModal from "../form/FormModal.vue";
import TransactionForm from "@/components/transactions/TransactionForm.vue";
import TxnDetails from "@/components/transactions/TxnDetails.vue";
import StatusFilters from "@/components/data/StatusFilters.vue";
import SearchBar from "@/components/data/SearchBar.vue";
import {FilterMatchMode} from "@primevue/core/api";
import {frhtOptions} from "@utils/dropdowns";

const {data, statusBoxes, statusStyles} = useStatusFilters('transactions');
watchEffect(() => {
  console.log('Data:', data.value)
})
const {selected, unselect, openDetails} = useDetailsModal();

// Reports (CSV)
const table = ref();
const exportCSV = () => {
  table.value.exportCSV();
};

// <editor-fold desc="Data View">

const filters = ref({
  global: {value: null, matchMode: FilterMatchMode.CONTAINS},
  id: {value: null, matchMode: FilterMatchMode.CONTAINS},
  shipper: {value: null, matchMode: FilterMatchMode.IN},
  'show.name': {value: null, matchMode: FilterMatchMode.EQUALS},
  'zone.name': {value: null, matchMode: FilterMatchMode.CONTAINS},
  'booth.name': {value: null, matchMode: FilterMatchMode.CONTAINS},
  exhibitor: {value: null, matchMode: FilterMatchMode.CONTAINS},
  arrival_status: {value: null, matchMode: FilterMatchMode.CONTAINS},
  carrier: {value: null, matchMode: FilterMatchMode.CONTAINS},
  crate_pcs: {value: null, matchMode: FilterMatchMode.CONTAINS},
  carton_pcs: {value: null, matchMode: FilterMatchMode.CONTAINS},
  skid_pcs: {value: null, matchMode: FilterMatchMode.CONTAINS},
  fiber_case_pcs: {value: null, matchMode: FilterMatchMode.CONTAINS},
  carpet_pcs: {value: null, matchMode: FilterMatchMode.CONTAINS},
  misc_pcs: {value: null, matchMode: FilterMatchMode.CONTAINS},
  total_pcs: {value: null, matchMode: FilterMatchMode.CONTAINS},
  trailer: {value: null, matchMode: FilterMatchMode.CONTAINS},
  pallet: {value: null, matchMode: FilterMatchMode.CONTAINS},
  booth: {value: null, matchMode: FilterMatchMode.CONTAINS},
  'created_by_user.name': {value: null, matchMode: FilterMatchMode.CONTAINS},
  'updated_by_user.name': {value: null, matchMode: FilterMatchMode.CONTAINS},
  freight_type: {value: null, matchMode: FilterMatchMode.CONTAINS},
  special_handling: {value: null, matchMode: FilterMatchMode.CONTAINS},
  total_weight: {value: null, matchMode: FilterMatchMode.CONTAINS},
  created_at: {value: null, matchMode: FilterMatchMode.CONTAINS},
  updated_at: {value: null, matchMode: FilterMatchMode.CONTAINS}
});

// Columns
const simpleColumns = ref([
  {label: 'Shipper', value: 'shipper'},
  {label: 'Exhibitor', value: 'exhibitor'},
  {label: 'Arrival Status', value: 'arrival_status'},
  {label: 'Carrier', value: 'carrier'},
  {label: 'Crate Pcs', value: 'crate_pcs'},
  {label: 'Carton Pcs', value: 'carton_pcs'},
  {label: 'Skid Pcs', value: 'skid_pcs'},
  {label: 'Fiber Case Pcs', value: 'fiber_case_pcs'},
  {label: 'Carpet Pcs', value: 'carpet_pcs'},
  {label: 'Misc Pcs', value: 'misc_pcs'},
  {label: 'Total Pcs', value: 'total_pcs'},
  {label: 'Trailer', value: 'trailer'},
  {label: 'Pallet', value: 'pallet'},

]);
const objColumns = ref([
  {label: 'Show', value: 'show'},
  {label: 'Zone', value: 'zone'},
  {label: 'Booth', value: 'booth'},
  {label: 'Created By', value: 'created_by_user'},
  {label: 'Last Updated By', value: 'updated_by_user'},
])
const specialColumns = ref([
  {label: 'Freight Type', value: 'freight_type'},
  {label: 'Special Handling', value: 'special_handling'},
  {label: 'Total Weight', value: 'total_weight'},
  {label: 'Received Date/Time', value: 'created_at'},
  {label: 'Last Updated', value: 'updated_at'},
])
const allColumnChoices = computed(() => {
  return [...simpleColumns.value, ...objColumns.value, ...specialColumns.value];
});
const selectedColumns = ref(['shipper', 'exhibitor', 'arrival_status']);
const selectedSimpleColumns = computed(() => {
  return simpleColumns.value.filter(col => selectedColumns.value.includes(col.value));
});
const selectedObjColumns = computed(() => {
  return objColumns.value.filter(col => selectedColumns.value.includes(col.value));
});
const selectedSpecialColumns = computed(() => {
  return specialColumns.value.filter(col => selectedColumns.value.includes(col.value));
});


// </editor-fold>

// Options
const {getDroptions} = useForm();
const showOptions = getDroptions('shows', {active: 1, trashed: 0})

</script>

<template>
  <div>
    <div class="flex flex-row justify-between mb-4">
      <h1 class="font-sans text-3xl">Transactions</h1>
      <div class="flex flex-row gap-4">
        <Button label="Export CSV" icon="pi pi-external-link" severity="primary" @click="exportCSV" class="mb-4"/>
        <FormModal buttonLabel="New Transaction" header="New Transaction" width="40"
                   description="Create a new transaction from scratch, this is the same as receiving packages without a label!">
          <template #form="{close}">
            <TransactionForm :close/>
          </template>
        </FormModal>
      </div>
    </div>
    <TxnDetails v-if="selected" :subject="selected" @close="unselect"/>
    <Toast/>
    <DataTable ref="table" :value="data" paginator :rows="10" @row-click="openDetails" :rowStyle="statusStyles"
               v-model:filters="filters" :rowHover="true" removable-sort filterDisplay="row"
               :globalFilterFields="['id', 'shipper', 'show', 'zone']">
      <template #header>
        <div class="flex justify-start gap-4">
          <SearchBar v-model="filters.global.value" placeholder="Search all fields"/>
          <MultiSelect v-model="selectedColumns" :options="allColumnChoices" optionLabel="label"
                       optionValue="value" display="chip" placeholder="Select Columns"/>
          <StatusFilters :checkBoxes="statusBoxes"/>
        </div>
      </template>
      <template #empty>No transactions have been received yet.</template>
      <Column field="id" header="ID">
        <template #body="{data, field}">
          <span class="font-extralight">{{ data[field] }}</span>
        </template>
      </Column>
      <Column v-for="{label, value} of selectedObjColumns" :field="value" :filterField="`${value}.name`" :header="label"
              :key="value">
        <template #body="{data, field}">
          <span>{{ data[field].name }}</span>
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                     :placeholder="`Search by ${label}`"/>
        </template>
      </Column>
      <Column v-for="{label, value} of selectedSimpleColumns" :field="value" :header="label" sortable>
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                     :placeholder="`Search by ${label}`"/>
        </template>
      </Column>
      <Column field="created_at" header="Received" sortable>
        <template #body="{data, field}">
          <span>{{ new Date(data[field]).toLocaleString() }}</span>
        </template>
      </Column>
      <Column field="updated_at" header="Last Updated" sortable>
        <template #body="{data, field}">
          <span>{{ new Date(data[field]).toLocaleString() }}</span>
        </template>
      </Column>
      <Column field="freight_type" header="Freight Type">
        <template #body="{data, field}">
          <span>{{ frhtOptions.find((opt) => opt.value === data[field]).label }}</span>
        </template>
      </Column>
      <Column field="total_weight" header="Weight">
        <template #body="{data, field}">
          <span>{{ data[field] }} lbs.</span>
        </template>
      </Column>
    </DataTable>
  </div>
</template>