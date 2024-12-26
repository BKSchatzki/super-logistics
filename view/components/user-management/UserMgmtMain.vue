<script setup>
import {computed, onMounted, watch} from 'vue';
import {useStore} from 'vuex';
import U from '@utils/UserUtility';
import NewUserForm from '@/components/user-management/NewUserForm.vue';

const store = useStore();
const user = computed(() => store.state.user);
// add new users
// edit users
// delete users
// get users
const getUsers = async (user) => {
  if (U.isInternal(user)) {
    U.getAllAppUsers();
  } else if (U.isClientAdmin(user)) {
    U.getClientAppUsers(user.client_id);
  } else if (U.isClient(user)) {
    U.getClientAppUsers(user.client_id, [user.shows]);
  }
}
onMounted(() => {
  getUsers(user.value)
  U.getCurrentUser()
});
watch(user.value, getUsers);
// get show specific users
</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <h1>User Management</h1>
    <NewUserForm/>
  </div>
  <DataTable>
    <template #empty>
      No users in the system. Add one if you're an admin.
    </template>
  </DataTable>
</template>
