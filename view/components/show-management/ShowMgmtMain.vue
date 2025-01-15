<script setup>
import {ref} from 'vue';
import {useAPI} from '@utils/useAPI.js';
import Toast from 'primevue/toast';
import NewShowForm from '@/components/show-management/NewShowForm.vue';
import ShowDetails from '@/components/show-management/ShowDetails.vue';

const {get} = useAPI();

const tableData = get('shows');
const selected = ref(null);
const unselect = () => {
  selected.value = null;
}

// Open show details
const openDetails = (evt) => {
  selected.value = evt.data
}

// add new shows
// edit shows
// delete shows
// get shows

// get show specific shows
</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <h1 class="font-sans text-3xl">Show Management</h1>
    <NewShowForm/>
  </div>
  <ShowDetails v-if="selected" :subject="selected" @close="unselect"/>
  <Toast/>
  <DataTable :value="tableData" paginator :rows="10" @row-click="openDetails" :rowHover="true">
    <template #empty>
      No shows in the system. Add one if you're an admin.
    </template>
    <Column field="id" >
      <template #body="{data}">
        <span class="font-extralight text-slate-500">{{data.id}}</span>
      </template>
    </Column>
    <Column field="name" header="Name"/>
    <Column field="client" header="Client">
      <template #body="{field, data}">
        <span>{{ data[field].name }}</span>
      </template>
    </Column>
  </DataTable>
</template>
