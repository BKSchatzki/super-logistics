<script setup>
import {computed, ref, reactive} from 'vue';
import {useStore} from 'vuex';
import {useAPI} from '@utils/useAPI.js';
import {useForm} from '@utils/useForm.js';
import {useToast} from 'primevue/usetoast';
import FormRow from "@/components/form/FormRow.vue";
import FormInput from "@/components/form/SelectInput.vue";

const store = useStore();
const {get, post} = useAPI();
const {getDroptions} = useForm();
const toast = useToast();

const visible = ref(false);

// Permissions
const user = computed(store.state.user);

// Form
const form = reactive({
  name: '',
  show: null,
  zone: '',
});
const submit = () => {
  post('transactions', form).then(() => {
    visible.value = false;
  });
};

// Show Options
const showOptions = getDroptions('shows', {client_id: user.client});
</script>

<template>
  <Button class="mb-4 right-0" label="Start New Transaction" @click="visible = true"/>

  <Dialog v-model:visible="visible" modal header="New Transaction" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      Start a new transaction with the advance warehouse. Each transaction corresponds to one zone. For delivery to multiple zones, utilize multiple transactions.
    </span>
    <FormRow>
      <FormInput v-model="form.name" label="Name" placeholder="Name the transaction"/>
    </FormRow>
    <FormRow>
      <FormInput v-model="form.show" type="select" :options="showOptions" label="Show" placeholder="Which convention is this for?"/>
    </FormRow>
    <FormRow>
      <FormInput v-model="form.zone" label="Zone" placeholder="The location within the show"/>
    </FormRow>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
      <Button type="button" label="Add New" @click="submit"></Button>
    </div>
  </Dialog>
</template>

<style scoped>

</style>