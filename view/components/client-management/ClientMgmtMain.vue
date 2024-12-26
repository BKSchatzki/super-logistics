<script setup>
import {computed, onMounted, watch} from 'vue';
import {useStore} from 'vuex';
import D from '@utils/DataUtility';
import NewClientForm from '@/components/client-management/NewClientForm.vue';

const store = useStore();
const user = computed(() => store.state.user);
// add new shows
// edit shows
// delete shows
// get shows
onMounted(() => {
  D.getData('clients')
});
// get show specific users
</script>

<template>
  <div class="flex flex-row justify-between mb-4">
    <h1>Client Management</h1>
    <NewClientForm/>
  </div>
  <DataTable>
    <template #empty>
      No clients in the system. Add one if you're an admin.
    </template>
  </DataTable>
</template>
