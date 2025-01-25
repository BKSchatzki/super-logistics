<script setup>
import {ref, watch} from "vue";
import {useForm} from '@utils/composables/useForm.js';
import TextInput from "@/components/form/TextInput.vue";
import NumberInput from "@/components/form/NumberInput.vue";
import DateInput from "@/components/form/DateInput.vue";
import TextareaInput from "@/components/form/TextareaInput.vue";
import SelectInput from "@/components/form/SelectInput.vue";
import Col from "@/components/form/Col.vue";
import Row from "@/components/form/Row.vue";

// Form
const text = {
  internal: {
    button: "Add New show",
    title: "New Show",
    description: "Shows are events for which the advance warehouse manages the logistics. There is a penalty for shipper sending items early or late."
  }
}
const errorText = ref('');
const {form, visible, submit, getDroptions} = useForm({
  name: '',
  client_id: null,
  min_carat_weight: 150,
  carat_weight_inc: 100,
  date_start: new Date(),
  date_end: new Date(),
  zones: [],
  booths: []
});
const zones = ref('');
watch(zones, (newVal) => {
  try {
    form.zones = newVal.split(',').map(zone => zone.trim());
  } catch (e) {
    form.zones = [];
    errorText.value = 'Error parsing zones. Please adjust format.';
  }
});
const booths = ref('');
watch(booths, (newVal) => {
  try {
    form.booths = newVal.split(',').map(booth => booth.trim());
  } catch (e) {
    form.booths = [];
    errorText.value = 'Error parsing booths. Please adjust format.';
  }
});
const dates = ref([form.date_start, form.date_end]);
watch(dates, (newVal) => {
  form.date_start = newVal[0];
  form.date_end = newVal[1];
});

// Add New
const submitNewForm = async () => {
  await submit('shows', 'post');
}

// Client Options
const clientOptions = getDroptions('clients', {active: 1, trashed: 0});
</script>

<template>
  <Button class="mb-4 right-0" :label="text.internal.button" @click="visible = true"/>
  <Toast/>
  <Dialog v-model:visible="visible" modal :header="text.internal.title" :style="{ width: '25rem' }">
    <span v-if="errorText !== ''" class="text-red-400">
      {{ errorText }}
    </span>
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      {{ text.internal.description }}
    </span>
    <Col>
      <Row>
        <TextInput label="Name" v-model="form.name" placeholder="Trade show name"/>
      </Row>
      <Row>
        <SelectInput label="Client" v-model="form.client_id" :options="clientOptions" placeholder="Select a Client"/>
      </Row>
      <Row>
        <DateInput label="Advance Warehouse Dates" v-model="dates" mode="range" inline/>
      </Row>
      <Row>
        <NumberInput label="Min. Carat Weight" v-model="form.min_carat_weight"/>
        <NumberInput label="Carat Weight Increment" v-model="form.carat_weight_inc"/>
      </Row>
      <Row>
        <TextareaInput v-model="zones" label="Zones" placeholder="Enter zones here, separated by commas, no spaces."/>
      </Row>
      <Row>
        <TextareaInput v-model="booths" label="Booths"
                       placeholder="Enter booths here, separated by commas, no spaces."/>
      </Row>
      <div class="flex justify-end gap-2">
        <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
        <Button type="submit" label="Add New" severity="primary" @click="submitNewForm"></Button>
      </div>
    </Col>
  </Dialog>
</template>