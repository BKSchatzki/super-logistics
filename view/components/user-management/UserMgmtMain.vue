<script setup>
import {ref} from 'vue';
import {useAPI} from '@utils/useAPI.js';
import Toast from 'primevue/toast';
import NewUserForm from '@/components/user-management/NewUserForm.vue';
import UserDetails from '@/components/user-management/UserDetails.vue';

const {get} = useAPI();

const users = get('users');
const selected = ref(null);
const unselect = () => {
  selected.value = null;
}

// Open user details
const openDetails = (evt) => {
  selected.value = evt.data
}

// add new users
// edit users
// delete users
// get users

// get show specific users
</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <h1 class="font-sans text-3xl">User Management</h1>
    <NewUserForm/>
  </div>
  <UserDetails v-if="selected" :subject="selected" @close="unselect"/>
  <Toast/>
  <DataTable :value="users" paginator :rows="10" @row-click="openDetails" :rowHover="true">
    <template #empty>
      No users in the system. Add one if you're an admin.
    </template>
    <Column field="name" header="Name"/>
    <Column field="user_email" header="Email"/>
    <Column field="nice_role" header="Type"/>
    <Column field="client" header="Client">
      <template #body="slotProps">
        {{ slotProps.data.client ? slotProps.data.client.name : '' }}
      </template>
    </Column>

  </DataTable>
</template>
