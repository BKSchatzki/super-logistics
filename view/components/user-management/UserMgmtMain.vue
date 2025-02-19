<script setup>
import {computed, ref} from 'vue';
import {useStore} from "vuex";
import {useFormAssist} from '@utils/composables/useFormAssist.js';
import {useStatusFilters} from '@utils/composables/useStatusFilters.js';
import {useDetailsModal} from '@utils/composables/useDetailsModal.js';
import {FilterMatchMode} from '@primevue/core/api';
import Toast from 'primevue/toast';
import UserDetails from '@/components/user-management/UserDetails.vue';
import SearchBar from '@/components/data/SearchBar.vue';
import StatusFilters from '@/components/data/StatusFilters.vue';
import FormModal from "@/components/form/FormModal.vue";
import UserForm from "@/components/user-management/UserForm.vue";
import PageTitle from "@/components/general-ui/PageTitle.vue";

const {data, statusBoxes, statusStyles} = useStatusFilters('users');
const {selected, unselect, openDetails} = useDetailsModal();

const store = useStore();
const user = computed(() => store.state.user)

// <editor-fold desc="New Form">----------------------------
const newFormText = {
  client_admin: {
    button: "Add New Employee",
    title: "New Employee",
    description: "Add a new user to handle logistics for your shows"
  },
  internal_admin: {
    button: "Add New User",
    title: "New User",
    description: "Add any type of user to the system for use by your office or for the client."
  }
}

// <editor-fold desc="Filters">---------------------------------

const filters = ref({
  global: {value: null, matchMode: FilterMatchMode.CONTAINS},
  name: {value: null, matchMode: FilterMatchMode.CONTAINS},
  user_email: {value: null, matchMode: FilterMatchMode.CONTAINS},
  role: {value: null, matchMode: FilterMatchMode.IN},
  'client.name': {value: null, matchMode: FilterMatchMode.IN}
});

// </editor-fold>-----------------------------------------------

// <editor-fold desc="Select Options">--------------------------

const {getDroptions, getRoleDroptions} = useFormAssist();
const roleOptions = getRoleDroptions();
const clientOptions = getDroptions('clients');

// </editor-fold>-----------------------------------------------

</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <PageTitle title="User Management"/>
    <FormModal v-if="user['isAdmin']"
               :buttonLabel="newFormText['client_admin']['button']"
               :header="newFormText['client_admin']['button']">
      <template #form="{close}">
        <UserForm :close :description="newFormText['client_admin']['description']"/>
      </template>
    </FormModal>
  </div>
  <UserDetails v-if="selected" :subject="selected" @close="unselect"/>
  <Toast/>
  <Panel>
    <DataTable :value="data" paginator :rows="7" @row-click="openDetails" :rowHover="true"
               v-model:filters="filters" removableSort filterDisplay="menu" :rowStyle="statusStyles"
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
      <Column v-if="user['isInternal']" field="client" header="Client" filterField="client.name" sortable>
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
