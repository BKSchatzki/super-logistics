<script setup>
import {computed, onMounted, ref} from "vue";
import {useStore} from "vuex";
import U from '@utils/UserUtility';
import D from '@utils/DataUtility';
const store = useStore();

// Form
const visible = ref(false);
const text = {
  client: {
    button: "Add New Client User",
    title: "New Client User",
    description: "Add a new user to handle logistics for your shows"
  },
  internal: {
    button: "Add New User",
    title: "New User",
    description: "Add any type of user to the system for use by your office or for the client."
  },
  loading: {
    button: "loading...",
    title: "loading...",
    description: "loading..."
  }
}

// Users
const user = computed(() => store.state.user);
const client = computed(() => U.isClient(user.value));
const internal = computed(() => U.isInternal(user.value));
const userType = computed(() => internal.value ? 'internal' : client.value ? 'client' : 'loading');

// Data
const clients = computed(() => store.state.clients);
const shows = computed(() => store.state.shows);
onMounted(() => {
  D.getData('clients');
  D.getData('shows');
})
</script>

<template>
  <Button class="mb-4 right-0" :label="text[userType].button" @click="visible = true"/>

  <Dialog v-model:visible="visible" modal :header="text[userType].title" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      {{ text[userType].description }}
    </span>
    <div class="flex items-center gap-4 mb-4">
      <label for="name" class="font-semibold w-24">Name</label>
      <InputText id="name" class="flex-auto" autocomplete="off"/>
    </div>
    <div class="flex items-center gap-4 mb-4">
      <label for="username" class="font-semibold w-24">Username</label>
      <InputText id="username" class="flex-auto" autocomplete="off"/>
    </div>
    <div class="flex items-center gap-4 mb-4">
      <label for="client" class="font-semibold w-24">Client</label>
      <Select id="client" class="flex-auto" autocomplete="off"/>
    </div>
    <div class="flex items-center gap-4 mb-4">
      <label for="show" class="font-semibold w-24">Show</label>
      <Select id="show" class="flex-auto" autocomplete="off" multiple/>
    </div>
    <div class="flex items-center gap-4 mb-4">
      <label for="zone" class="font-semibold w-24">Zone</label>
      <InputText id="zone" class="flex-auto" autocomplete="off"/>
    </div>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
      <Button type="button" label="Add New" severity="primary" @click="visible = false"></Button>
    </div>
  </Dialog>
</template>

<style scoped>

</style>