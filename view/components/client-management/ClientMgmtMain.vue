<script setup>
import {computed, ref, watch} from 'vue';
import {useStore} from 'vuex';
import {useAPI} from '@utils/composables/useAPI.js';
import {useStatusFilters} from "@utils/composables/useStatusFilters.js";
import {useDetailsModal} from "@utils/composables/useDetailsModal.js";
import NewClientForm from '@/components/client-management/NewClientForm.vue';
import ClientDetails from '@/components/client-management/ClientDetails.vue';
import SearchBar from "@/components/data-management/SearchBar.vue";
import StatusFilters from "@/components/data-management/StatusFilters.vue";

const store = useStore();
const user = computed(() => store.state.user);

const {data, statusBoxes, statusStyles} = useStatusFilters('clients');
const {selected, unselect, openDetails} = useDetailsModal();

const filters = ref({
  global: {value: null, matchMode: 'contains'},
  name: {value: null, matchMode: 'contains'}
});

</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <h1>Client Management</h1>
    <NewClientForm/>
  </div>
  <ClientDetails v-if="selected" :subject="selected" @close="unselect"/>
  <DataTable :value="data" @row-click="openDetails" :rowHover="true"
             v-model:filters="filters" :rowStyle="statusStyles"
             :globalFilterFields="['name']">
    <template #header>
      <div class="flex justify-start gap-4">
        <SearchBar v-model="filters.global.value" placeholder="Search all fields"/>
        <StatusFilters :checkBoxes="statusBoxes"/>
      </div>
    </template>
    <Column field="id" header="ID" >
      <template #body="{data}">
        <span class="font-extralight text-gray-500">{{data.id}}</span>
      </template>
    </Column>
    <Column field="name" header="Name" sortable/>
    <template #empty>
      No clients in the system. Add one if you're an admin.
    </template>
  </DataTable>
</template>
