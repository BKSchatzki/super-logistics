<script setup>
import {ref} from 'vue';
import {useForm} from '@utils/composables/useForm.js';
import {useStatusFilters} from '@utils/composables/useStatusFilters.js';
import {useDetailsModal} from '@utils/composables/useDetailsModal.js';
import {FilterMatchMode} from '@primevue/core/api';
import Toast from 'primevue/toast';
import NewUserForm from '@/components/user-management/NewUserForm.vue';
import UserDetails from '@/components/user-management/UserDetails.vue';
import SearchBar from '@/components/data-management/SearchBar.vue';
import StatusFilters from '@/components/data-management/StatusFilters.vue';

const {data, statusBoxes, statusStyles} = useStatusFilters('users');
const {selected, unselect, openDetails} = useDetailsModal();

// Filters
const filters = ref({
  global: {value: null, matchMode: FilterMatchMode.CONTAINS},
  name: {value: null, matchMode: FilterMatchMode.CONTAINS},
  user_email: {value: null, matchMode: FilterMatchMode.CONTAINS},
  role: {value: null, matchMode: FilterMatchMode.IN},
  'client.name': {value: null, matchMode: FilterMatchMode.IN}
});
const {getDroptions, getRoleDroptions} = useForm();
const roleOptions = getRoleDroptions();
const clientOptions = getDroptions('clients');
</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <h1 class="font-sans text-3xl">User Management</h1>
    <NewUserForm/>
  </div>
  <UserDetails v-if="selected" :subject="selected" @close="unselect"/>
  <Toast/>
  <DataTable :value="data" paginator :rows="10" @row-click="openDetails" :rowHover="true"
             v-model:filters="filters" removableSort filterDisplay="row" :rowStyle="statusStyles"
             :globalFilterFields="['name', 'user_email', 'role', 'client.name']">
    <template #header>
      <div class="flex justify-start gap-4">
        <SearchBar v-model="filters.global.value" placeholder="Search all fields"/>
        <StatusFilters :checkBoxes="statusBoxes"/>
      </div>
    </template>
    <template #empty>
      No users in the system. Add one if you're an admin.
    </template>
    <Column field="name" header="Name" sortable>
      <template #filter="{ filterModel, filterCallback }">
        <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                   placeholder="Search by name"/>
      </template>
    </Column>
    <Column field="user_email" header="Email" sortable>
      <template #filter="{ filterModel, filterCallback }">
        <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                   placeholder="Search by email"/>
      </template>
    </Column>
    <Column field="role" header="Role" sortable>
      <template #body="{data}">
        {{ data.nice_role }}
      </template>
      <template #filter="{ filterModel, filterCallback }">
        <MultiSelect v-model="filterModel.value" @change="filterCallback()"
                     :options="roleOptions" optionLabel="label" optionValue="value"
                     placeholder="Any" :maxSelectedLabels="1" style="min-width: 12rem"/>
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
