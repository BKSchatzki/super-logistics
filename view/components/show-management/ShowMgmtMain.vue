<script setup>
import {computed, ref} from 'vue';
import {useStore} from "vuex";
import {useFormAssist} from '@utils/composables/useFormAssist.js';
import {useStatusFilters} from "@utils/composables/useStatusFilters.js";
import {useDetailsModal} from "@utils/composables/useDetailsModal.js";
import {FilterMatchMode} from '@primevue/core/api';
import Toast from 'primevue/toast';
import ShowDetails from '@/components/show-management/ShowDetails.vue';
import SearchBar from "@/components/data/SearchBar.vue";
import StatusFilters from "@/components/data/StatusFilters.vue";
import FormModal from "@/components/form/FormModal.vue";
import ShowForm from "@/components/show-management/ShowForm.vue";
import PageTitle from "@/components/general-ui/PageTitle.vue";

// <editor-fold desc="Setup">------------------------------------------

const store = useStore();
const user = computed(() => store.state.user);
const {data, statusBoxes, statusStyles} = useStatusFilters('shows');
const {selected, unselect, openDetails} = useDetailsModal();

// </editor-fold>-------------------------------------------------------

// <editor-fold desc="Filters">------------------------------------------

const filters = ref({
  global: {value: null, matchMode: FilterMatchMode.CONTAINS},
  name: {value: null, matchMode: FilterMatchMode.CONTAINS},
  'client.name': {value: null, matchMode: FilterMatchMode.IN}
});

// </editor-fold>-------------------------------------------------------

// <editor-fold desc="Options">------------------------------------------

const {getDroptions} = useFormAssist();
const clientOptions = getDroptions('clients');

// </editor-fold>-------------------------------------------------------

</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <PageTitle title="Show Management"/>
    <FormModal v-if="user['isAdmin']" buttonLabel="Add New Show" header="New Show"
               description="Shows are events for which the advance warehouse manages the logistics. There is a penalty for shipper sending items early or late.">
      <template #form="{close}">
        <ShowForm :close/>
      </template>
    </FormModal>
  </div>
  <ShowDetails v-if="selected" :subject="selected" @close="unselect"/>
  <Toast/>
  <Panel>
    <DataTable :value="data" paginator :rows="10" @row-click="openDetails" :rowHover="true"
               v-model:filters="filters" removableSort filterDisplay="menu" :rowStyle="statusStyles"
               :globalFilterFields="['name', 'client.name']">
      <template #header>
        <div class="flex justify-start gap-4">
          <SearchBar v-model="filters.global.value" placeholder="Search all fields"/>
          <StatusFilters v-if="user['isAdmin']" :checkBoxes="statusBoxes"/>
        </div>
      </template>
      <template #empty>
        No shows in the system. Add one if you're an admin.
      </template>
      <Column field="id" header="ID" :sortable="true">
        <template #body="{data}">
          <span class="font-extralight text-gray-500">{{ data.id }}</span>
        </template>
      </Column>
      <Column field="name" header="Name" :sortable="true">
        <template #filter="{ filterModel, filterCallback }">
          <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                     placeholder="Search by name"/>
        </template>
      </Column>
      <Column v-if="user['isInternal']" field="client" header="Client" filterField="client.name">
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
  </Panel>
</template>
