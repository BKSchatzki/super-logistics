<script setup>
import {reactive, watch} from "vue";
import {useStore} from "vuex";
import {useAPI} from "@utils/composables/useAPI.js";
import {useForm} from '@utils/composables/useForm.js';
import TextInput from "@/components/form/TextInput.vue";
import SelectInput from "@/components/form/SelectInput.vue";
import Row from "@/components/form/Row.vue";

// Form
const text = {
  internal: {
    button: "Start New Transaction",
    title: "New Transaction",
    description: "Transactions describe all of the billed activity that takes place during a show for ONE zone setup. To cover multiple zones, start a new transaction for each."
  }
}
const {form, visible, submit, getDroptions} = useForm({
  name: '',
  client_id: null,
  show_id: null,
  zone: ''
});

// Submit
const toastConfig = reactive({
  success: {
    summary: 'New Transaction',
    detail: `Transaction has been started.`,
  },
  fail: {
    summary: 'New Transaction',
    detail: `There was an error starting the transaction. Please try again.`,
  }
});
const submitNewForm = async () => {
  await submit('transactions', 'post', toastConfig);
}

// Shows Droptions
const showOptions = getDroptions('shows');

</script>

<template>
  <Button class="mb-4 right-0" :label="text.internal.button" @click="visible = true"/>
  <Toast/>
  <Dialog v-model:visible="visible" modal :header="text.internal.title" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      {{ text.internal.description }}
    </span>
    <Row>
      <TextInput label="Name" v-model="form.name"/>
    </Row>
    <Row>
      <SelectInput label="Show" v-model="form.show_id" :options="showOptions"/>
    </Row>
    <Row>
      <TextInput label="Zone" v-model="form.zone" />
    </Row>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
      <Button type="submit" label="Add New" severity="primary" @click="submitNewForm"></Button>
    </div>
  </Dialog>
</template>