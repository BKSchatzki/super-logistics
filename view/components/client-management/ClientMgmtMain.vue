<script setup>
import {computed, ref, watch} from 'vue';
import {useStore} from 'vuex';
import {useAPI} from '@utils/useAPI.js';
import NewClientForm from '@/components/client-management/NewClientForm.vue';
import ClientDetails from '@/components/client-management/ClientDetails.vue';

const store = useStore();
const {get} = useAPI();
const user = computed(() => store.state.user);

const clients = get('clients');
const selected = ref(null);
const unselect = () => {
  selected.value = null;
}

const openDetails = (evt) => {
  selected.value = evt.data
}
</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <h1>Client Management</h1>
    <NewClientForm/>
  </div>
  <ClientDetails v-if="selected" :subject="selected" @close="unselect"/>
  <DataTable :value="clients" @row-click="openDetails" :rowHover="true">
    <Column field="id" >
      <template #body="{data}">
        <span class="font-extralight text-slate-500">{{data.id}}</span>
      </template>
    </Column>
    <Column field="name" />
    <template #empty>
      No clients in the system. Add one if you're an admin.
    </template>
  </DataTable>
</template>
