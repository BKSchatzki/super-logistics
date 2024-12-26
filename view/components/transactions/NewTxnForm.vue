<script setup>
import {computed, ref} from 'vue';
import {useStore} from 'vuex';
import {checkOverlap} from '@utils/helpers';

const store = useStore();
const visible = ref(false);
const roles = computed(() => store.state.user.roles);
const isClient = computed(() => checkOverlap(roles, ['client_admin', 'client_employee']));
</script>

<template>
  <Button class="mb-4 right-0" label="Start New Transaction" @click="visible = true"/>

  <Dialog v-model:visible="visible" modal header="New Transaction" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      Start a new transaction with the advance warehouse. Each transaction corresponds to one zone. For delivery to multiple zones, utilize multiple transactions.
    </span>
    <div class="flex items-center gap-4 mb-4">
      <label for="name" class="font-semibold w-24">Name</label>
      <InputText id="name" class="flex-auto" autocomplete="off"/>
    </div>
    <div class="flex items-center gap-4 mb-4">
      <label for="show" class="font-semibold w-24">Show</label>
      <InputText id="show" class="flex-auto" autocomplete="off"/>
    </div>
    <div class="flex items-center gap-4 mb-4">
      <label for="zone" class="font-semibold w-24">Zone</label>
      <InputText id="zone" class="flex-auto" autocomplete="off"/>
    </div>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
      <Button type="button" label="Add New" @click="visible = false"></Button>
    </div>
  </Dialog>
</template>

<style scoped>

</style>