<script setup>
import {reactive} from "vue";
import {useStore} from "vuex";
import {useAPI} from '@utils/useAPI.js';
import {useForm} from '@utils/useForm.js';
import TextInput from "@/components/form/TextInput.vue";
import FormRow from "@/components/form/FormRow.vue";

const store = useStore();
const {get, post} = useAPI();

// Form
const text = {
  internal: {
    button: "Add New client",
    title: "New Client",
    description: "Clients organize trade shows and conventions, they are the primary billed party for shows."
  }
}
const {form, visible, submit} = useForm({name: ''});

// Add New
const toastConfig = reactive({
  success: {
    summary: 'New Client',
    detail: `Client has been added successfully.`,
  },
  fail: {
    summary: 'New Client',
    detail: `There was an error adding the new client. Please try again.`,
  }
});
const submitNewForm = async () => {
  await submit('clients', 'post', toastConfig);
}
</script>

<template>
  <Button class="mb-4 right-0" :label="text.internal.button" @click="visible = true"/>
  <Toast/>
  <Dialog v-model:visible="visible" modal :header="text.internal.title" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      {{ text.internal.description }}
    </span>
    <FormRow>
      <TextInput label="Name" v-model="form.name"/>
    </FormRow>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
      <Button type="submit" label="Add New" severity="primary" @click="submitNewForm"></Button>
    </div>
  </Dialog>
</template>