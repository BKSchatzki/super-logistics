<script setup>
import {computed, ref, watchEffect} from "vue";
import {useStore} from "vuex";
import {useFormAssist} from "@utils/composables/useFormAssist.js";
import {useStatusFilters} from "@utils/composables/useStatusFilters";
import {useDetailsModal} from "@utils/composables/useDetailsModal";
import {DatePicker} from "primevue";
import Toast from "primevue/toast";
import FormModal from "../form/FormModal.vue";
import ReceiverForm from "@/components/receiver-management/ReceiverForm.vue";
import ReceiverDetails from "@/components/receiver-management/ReceiverDetails.vue";
import StatusFilters from "@/components/data/StatusFilters.vue";
import SearchBar from "@/components/data/SearchBar.vue";
import {FilterMatchMode} from "@primevue/core/api";
import {frhtFilters} from "@utils/dropdowns";
import PageTitle from "@/components/general-ui/PageTitle.vue";

// <editor-fold desc="Setup">--------------------------------------------

const {data, statusBoxes, statusStyles} = useStatusFilters('transactions');
const table = ref();
const dateRange = ref();
const tableData = computed(() => {

  // Date Range Filtering
  const [startRange, endRange] = dateRange.value || [];
  const dateFilteredData = data.value.filter(row => {
    const created_at = new Date(row['created_at']);
    return (!startRange || created_at >= startRange) && (!endRange || created_at <= endRange);
  });

  return dateFilteredData.map(row => ({
    ...row,
    'show': row.show?.name,
    'zone': row.zone?.name,
    'client': row.client?.name,
    'created_at' : row['created_at'] ? new Date(row['created_at']) : null,
    'updated_at' : row['updated_at'] ? new Date(row['updated_at']) : null,
    'created_by': row['created_by_user']?.user_login,
    'updated_by': row['updated_by_user']?.user_login,
  }))
});
const {selected, unselect, openDetails} = useDetailsModal();

// </editor-fold>-------------------------------------------------------

// <editor-fold desc="Data Viewing">------------------------------------

// Reports (CSV)
const exportCSV = () => {
  table.value.exportCSV();
};

// Filters
const filters = ref({
  global: {value: null, matchMode: FilterMatchMode.CONTAINS},
  id: {value: null, matchMode: FilterMatchMode.CONTAINS},
  shipper: {value: null, matchMode: FilterMatchMode.CONTAINS},
  client: {value: null, matchMode: FilterMatchMode.IN},
  show: {value: null, matchMode: FilterMatchMode.IN},
  zone: {value: null, matchMode: FilterMatchMode.IN},
  booth: {value: null, matchMode: FilterMatchMode.IN},
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
  created_by: {value: null, matchMode: FilterMatchMode.CONTAINS},
  updated_by: {value: null, matchMode: FilterMatchMode.CONTAINS},
  freight_type: {value: null, matchMode: FilterMatchMode.CONTAINS},
  nice_freight_type: {value: null, matchMode: FilterMatchMode.CONTAINS},
  special_handling: {value: null, matchMode: FilterMatchMode.CONTAINS},
  total_weight: {value: null, matchMode: FilterMatchMode.CONTAINS},
});

// </editor-fold>-------------------------------------------------------

// <editor-fold desc="Options">-------------------------------------------------------

const {getDroptions} = useFormAssist();

// Client Options
const clientOptions = getDroptions('clients', {active: 1, trashed: 0});

// Show Options
const showOptions = getDroptions('shows', {active: 1, trashed: 0})

// Restricting show places to the selected show
const store = useStore();
const shows = computed(() => store.state.shows); // getDroptions populated the store in the line before
const selectedShows = ref([]);

// Zones
const zoneOptions = computed(() => {
  return selectedShows.value ? selectedShows.value.reduce((acc, show) => {
    acc.push(show.zones.map(zone => ({label: zone.name, value: zone.id})));
  }, []) : [];
});

// </editor-fold>---------------------------------------------------------------------

// Columns
const columns = ref([
  {label: 'Shipper', value: 'shipper', filter: true},
  {label: 'Exhibitor', value: 'exhibitor', filter: true},
  {label: 'Arrival Status', value: 'arrival_status', filter: false},
  {label: 'Client', value: 'client', filter: true, filterChoices: clientOptions ?? []},
  {label: 'Show', value: 'show', filter: true, filterChoices: showOptions ?? []},
  {label: 'Zone', value: 'zone', filter: true, filterChoices: zoneOptions ?? []},
  {label: 'Booth', value: 'booth', filter: true},
  {label: 'Received by', value: 'created_by', filter: true},
  {label: 'Received Date/Time', value: 'nice_created_at', filter: false},
  {label: 'Last Updated by', value: 'updated_by', filter: true},
  {label: 'Last Updated', value: 'nice_updated_at', filter: false},
  {label: 'Trailer', value: 'trailer', filter: true},
  {label: 'Pallet', value: 'pallet', filter: true},
  {label: 'Carrier', value: 'carrier', filter: true},
  {label: 'Crates', value: 'crate_pcs', filter: false},
  {label: 'Cartons', value: 'carton_pcs', filter: false},
  {label: 'Skids', value: 'skid_pcs', filter: false},
  {label: 'Fiber Cases', value: 'fiber_case_pcs', filter: false},
  {label: 'Carpets', value: 'carpet_pcs', filter: false},
  {label: 'Misc', value: 'misc_pcs', filter: false},
  {label: 'Total Pcs.', value: 'total_pcs', filter: false},
  {label: 'Freight Type', value: 'nice_freight_type', filter: true, filterChoices: frhtFilters},
  {label: 'Special Handling', value: 'special_handling', filter: false},
  {label: 'Total Weight', value: 'total_weight', filter: false, units: ' lbs.'},
  {label: 'Billable Weight', value: 'billable_weight', filter: false, units: ' lbs.'},
])
const selectedColumns = ref([
  {label: 'Shipper', value: 'shipper', filter: true},
  {label: 'Exhibitor', value: 'exhibitor', filter: true},
  {label: 'Received Date/Time', value: 'nice_created_at', filter: false}
]);

</script>

<template>
  <div>
    <div class="flex flex-row justify-between mb-4">
      <PageTitle title="Receivers"/>
      <div class="flex flex-row gap-4">
        <Button label="Export CSV" icon="pi pi-external-link" severity="primary" @click="exportCSV" class="mb-4"/>
        <FormModal buttonLabel="New Receiver Form" header="New Receiver Form" width="40">
          <template #form="{close}">
            <ReceiverForm :close
                          description="Create a new receiver form from scratch, this is the same as receiving packages without a label!"/>
          </template>
        </FormModal>
      </div>
    </div>
    <ReceiverDetails v-if="selected" :subject="selected" @close="unselect"/>
    <Toast/>
    <Panel>
      <DataTable ref="table" :value="tableData" paginator :rows="10" showGridlines @row-click="openDetails"
                 :rowStyle="statusStyles" v-model:filters="filters" :rowHover="true" removable-sort filterDisplay="menu" size="small"
                 :globalFilterFields="['id', 'shipper', 'exhibitor', 'nice_freight_type', 'show', 'zone', 'arrival_status', 'trailer', 'pallet', 'booth', 'received_by_user', 'updated_by_user', 'client']">
        <template #header>
          <div class="flex justify-start gap-4">
            <SearchBar v-model="filters.global.value" placeholder="Search all fields"/>
            <DatePicker v-model="dateRange" selectionMode="range" :manualInput="false" placeholder="Date Range Filter"/>
            <MultiSelect v-model="selectedColumns" :options="columns" optionLabel="label" display="chip"
                         placeholder="Select Columns" :maxSelectedLabels="1" filter/>
            <StatusFilters :checkBoxes="statusBoxes"/>
          </div>
        </template>
        <template #empty>Empty</template>
        <Column field="id" :sortable :showFilterMatchModes="false">
          <template #header>
            <span class="text-nowrap">Rec. No.</span>
          </template>
          <template #body="{data, field}">
            <span class="font-extralight">{{ data[field] }}</span>
          </template>
          <template #filter="{filterModel, filterCallback}">
            <InputText fluid v-model="filterModel.value" type="text" @input="filterCallback()" placeholder="Rec. No."/>
          </template>
        </Column>
        <Column v-for="col of selectedColumns" :field="col.value" :sortable :showFilterMatchModes="false">
          <template #header>
            <span class="text-nowrap">{{ col.label }}</span>
          </template>
          <template #body="{data, field}">
            <span class="text-nowrap">{{ data[field] }}{{ col['units'] ?? '' }}</span>
          </template>
          <template #filter="{ filterModel, filterCallback }" v-if="col.filter">
            <MultiSelect v-if="col['filterChoices'] && col['filterChoices'].length" fluid v-model="filterModel.value"
                         :options="col['filterChoices']" optionLabel="label" optionValue="label" filter :maxSelectedLabels="2"/>
            <InputText v-else v-model="filterModel.value" fluid type="text" @input="filterCallback()"
                       :placeholder="`Search by ${col.label}`" class="min-w-2"/>
          </template>
        </Column>
      </DataTable>
    </Panel>
  </div>
</template>