<script setup>
import {ref, watchEffect} from 'vue';
import Toast from 'primevue/toast';
import NewShowForm from '@/components/show-management/NewShowForm.vue';
import ShowDetails from '@/components/show-management/ShowDetails.vue';
import {useForm} from '@utils/composables/useForm.js';
import {useStatusFilters} from "@utils/composables/useStatusFilters.js";
import {useDetailsModal} from "@utils/composables/useDetailsModal.js";
import {FilterMatchMode} from '@primevue/core/api';
import SearchBar from "@/components/data-management/SearchBar.vue";
import StatusFilters from "@/components/data-management/StatusFilters.vue";

const {data, statusBoxes, statusStyles} = useStatusFilters('shows');
const {selected, unselect, openDetails} = useDetailsModal();

watchEffect(() => {
  console.log('data:', data);
});

// Filters
const filters = ref({
  global: {value: null, matchMode: FilterMatchMode.CONTAINS},
  name: {value: null, matchMode: FilterMatchMode.CONTAINS},
  'client.name': {value: null, matchMode: FilterMatchMode.IN}
});
const {getDroptions} = useForm();
const clientOptions = getDroptions('clients');
</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <h1 class="font-sans text-3xl">Show Management</h1>
    <NewShowForm/>
  </div>
  <ShowDetails v-if="selected" :subject="selected" @close="unselect"/>
  <Toast/>
  <DataTable :value="data" paginator :rows="10" @row-click="openDetails" :rowHover="true"
             v-model:filters="filters" removableSort filterDisplay="row" :rowStyle="statusStyles"
             :globalFilterFields="['name', 'client.name']">
    <template #header>
      <div class="flex justify-start gap-4">
        <SearchBar v-model="filters.global.value" placeholder="Search all fields"/>
        <StatusFilters :checkBoxes="statusBoxes"/>
      </div>
    </template>
    <template #empty>
      No shows in the system. Add one if you're an admin.
    </template>
    <Column field="id">
      <template #body="{data}">
        <span class="font-extralight text-gray-500">{{ data.id }}</span>
      </template>
    </Column>
    <Column field="name" header="Name" sortable>
      <template #filter="{ filterModel, filterCallback }">
        <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                   placeholder="Search by name"/>
      </template>
    </Column>
    <Column field="client" header="Client" filterField="client.name" sortable>
      <template #body="{data}">
        {{ data.client ? data.client.name : '' }}
      </template>
      <template #filter="{ filterModel, filterCallback }">
        <MultiSelect v-model="filterModel.value" @change="filterCallback()" style="min-width: 12rem"
                     :options="clientOptions" optionLabel="label" optionValue="label"
                     placeholder="Any" :maxSelectedLabels="1"/>
      </template>
    </Column>
  </DataTable>
</template>
