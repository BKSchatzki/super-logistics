<script setup>
import {ref, watch} from "vue";
import {useForm} from "@utils/composables/useForm.js";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";
import TextInput from "@/components/form/TextInput.vue";
import DateInput from "@/components/form/DateInput.vue";
import NumberInput from "@/components/form/NumberInput.vue";
import SelectInput from "@/components/form/SelectInput.vue";
import {formatDateForJS} from "@utils/helpers.js";

const props = defineProps({
  subject: Object,
  stop: Function
});
const emit = defineEmits(['close']);

// Form Data
const initialData = {
  ...props.subject,
  date_start: formatDateForJS(props.subject.date_start),
  date_end: formatDateForJS(props.subject.date_end),
  client_id: props.subject.client ? props.subject.client.id : null,
};
const {form, clearForm, submit, getDroptions} = useForm(initialData);
const dates = ref([form.date_start, form.date_end]);
watch(dates, (newVal) => {
  form.date_start = newVal[0];
  form.date_end = newVal[1];
});

// Submission
const submitEditForm = async () => {
  await submit('shows', 'update')
  props.stop()
}
const cancel = () => {
  clearForm();
  props.stop()
}

// Options

// Client Options
const clientOptions = getDroptions('clients');
</script>

<template>
  <Toast/>
  <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
    Edit show name, the ID will always be static.
  </span>
  <Col>
    <Row>
      <TextInput label="Name" v-model="form['name']" placeholder="Trade show name"/>
    </Row>
    <Row>
      <SelectInput label="Client" v-model="form['client_id']" :options="clientOptions" placeholder="Select a Client"/>
    </Row>
    <Row>
      <DateInput label="Advance Warehouse Dates" v-model="dates" mode="range" inline/>
    </Row>
    <Row>
      <NumberInput label="Min. Carat Weight" v-model="form['min_carat_weight']"/>
      <NumberInput label="Carat Weight Increment" v-model="form['carat_weight_inc']"/>
    </Row>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="cancel"></Button>
      <Button type="button" label="Save" severity="primary" @click="submitEditForm"></Button>
    </div>
  </Col>
</template>