<script setup>
import {reactive, ref} from "vue";
import {useStore} from "vuex";
import {useAPI} from '@utils/useAPI.js';
import {useForm} from '@utils/useForm.js';
import TextInput from "@/components/form/TextInput.vue";
import NumberInput from "@/components/form/NumberInput.vue";
import DateInput from "@/components/form/DateInput.vue";
import FormRow from "@/components/form/FormRow.vue";
import SelectInput from "@/components/form/SelectInput.vue";

const store = useStore();
const {get, post} = useAPI();

// Form
const text = {
  internal: {
    button: "Add New show",
    title: "New Show",
    description: "Shows are events for which the advance warehouse manages the logistics. There is a penalty for shipper sending items early or late."
  }
}
const {form, visible, submit, getDroptions} = useForm({
  name: '',
  client: null,
  min_carat_weight: 150,
  carat_weight_inc: 100,
  date_start: new Date(),
  date_end: new Date(),
});
const dates = ref([form.date_start, form.date_end]);

// Add New
const toastConfig = reactive({
  success: {
    summary: 'New Show',
    detail: `Show has been added successfully.`,
  },
  fail: {
    summary: 'New Show',
    detail: `There was an error adding the new show. Please try again.`,
  }
});
const submitNewForm = async () => {
  await submit('shows', 'post', toastConfig);
}

// Options

// Client Options
const clientOptions = getDroptions('clients');
</script>

<template>
  <Button class="mb-4 right-0" :label="text.internal.button" @click="visible = true"/>
  <Toast/>
  <Dialog v-model:visible="visible" modal :header="text.internal.title" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      {{ text.internal.description }}
    </span>
    <FormRow>
      <TextInput label="Name" v-model="form.name" placeholder="Trade show name"/>
    </FormRow>
    <FormRow>
      <SelectInput label="Client" v-model="form.client" :options="clientOptions" placeholder="Select a Client"/>
    </FormRow>
    <FormRow>
      <DateInput label="Advance Warehouse Dates" v-model="dates" mode="range" inline/>
    </FormRow>
    <FormRow>
      <NumberInput label="Min. Carat Weight" v-model="form.min_carat_weight"/>
      <NumberInput label="Carat Weight Increment" v-model="form.carat_weight_inc"/>
    </FormRow>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
      <Button type="submit" label="Add New" severity="primary" @click="submitNewForm"></Button>
    </div>
  </Dialog>
</template>