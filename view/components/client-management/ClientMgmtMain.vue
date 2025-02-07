<script setup>
import {computed, ref} from 'vue';
import {useStore} from 'vuex';
import {useStatusFilters} from "@utils/composables/useStatusFilters.js";
import {useDetailsModal} from "@utils/composables/useDetailsModal.js";
import ClientDetails from '@/components/client-management/ClientDetails.vue';
import SearchBar from "@/components/data/SearchBar.vue";
import StatusFilters from "@/components/data/StatusFilters.vue";
import FormModal from "@/components/form/FormModal.vue";
import ClientForm from "@/components/client-management/ClientForm.vue";
import PageTitle from "@/components/general-ui/PageTitle.vue";

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
    <PageTitle title="Client Management"/>
    <FormModal buttonLabel="New Client" header="New Client">
      <template #form="{close}">
        <ClientForm :close
                    description="Add a new client, under which shows are to be created, and accounts are to be made."/>
      </template>
    </FormModal>
  </div>
  <ClientDetails v-if="selected" :subject="selected" @close="unselect"/>
  <Panel>
    <DataTable :value="data" @row-click="openDetails" :rowHover="true"
               v-model:filters="filters" :rowStyle="statusStyles"
               :globalFilterFields="['name']">
      <template #header>
        <div class="flex justify-start gap-4">
          <SearchBar v-model="filters.global.value" placeholder="Search all fields"/>
          <StatusFilters :checkBoxes="statusBoxes"/>
        </div>
      </template>
      <Column field="id" header="ID">
        <template #body="{data}">
          <span class="font-extralight text-gray-500">{{ data.id }}</span>
        </template>
      </Column>
      <Column field="name" header="Name" sortable/>
      <template #empty>
        No clients in the system. Add one if you're an admin.
      </template>
    </DataTable>
  </Panel>
</template>
