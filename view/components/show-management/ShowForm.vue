<script setup>
import {ref, watch} from "vue";
import {useStore} from "vuex";
import {useForm} from "@utils/composables/useForm";
import SelectInput from "@/components/form/SelectInput.vue";
import Row from "@/components/form/Row.vue";
import DateInput from "@/components/form/DateInput.vue";
import TextareaInput from "@/components/form/TextareaInput.vue";
import Col from "@/components/form/Col.vue";
import TextInput from "@/components/form/TextInput.vue";
import NumberInput from "@/components/form/NumberInput.vue";
import {formatDateForJS} from "@utils/helpers";

const props = defineProps({
  formData: {
    type: Object,
    default: {}
  },
  close: {
    type: Function,
    required: true
  },
  method: {
    type: String,
    default: 'post'
  }
})

const errorText = ref('');
const user = ref(useStore().state.user);

// <editor-fold desc="Form">-----------------------------------------

// instantiating form data / methods
const getListString = (places) => {
  return places.map(place => place['name']).join(',\n');
}
const {form, submit, getDroptions} = useForm({
  name: '',
  min_carat_weight: 150,
  carat_weight_inc: 100,
  zones: [],
  booths: [],
  ...props.formData,
  client_id: props.formData['client'] ? props.formData['client']['id'] : null,
  date_start: props.formData['date_start'] ? formatDateForJS(props.formData['date_start']) : new Date(),
  date_end: props.formData['date_end'] ? formatDateForJS(props.formData['date_end']) : new Date()
});
// Special Fields
watch(user, (newVal) => {
  if (newVal['isClient']) {
    form.client_id = newVal['client']['id'];
  }
}, {immediate: true});
const zones = ref(props.formData['zones'] ? getListString(props.formData['zones']) : '');
watch(zones, (newVal) => {
  try {
    form.zones = newVal.split(',').map(zone => zone.trim());
  } catch (e) {
    form.zones = [];
    errorText.value = 'Error parsing zones. Please adjust format.';
  }
}, {immediate: true}); // if immediate is not true, it will send an empty array and delete the zones upon submission.
const booths = ref(props.formData['booths'] ? getListString(props.formData['booths']) : '');
watch(booths, (newVal) => {
  try {
    form.booths = newVal.split(',').map(booth => booth.trim());
  } catch (e) {
    form.booths = [];
    errorText.value = 'Error parsing booths. Please adjust format.';
  }
}, {immediate: true});// if immediate is not true, it will send an empty array and delete the booths upon submission.
const dates = ref([form.date_start, form.date_end]);
watch(dates, (newVal) => {
  form.date_start = newVal[0];
  form.date_end = newVal[1];
});

// Submit
const submitForm = async () => {
  await submit('shows', props.method);
  props.close();
}

//</editor-fold>--------------------------------------------------------

// <editor-fold desc="Options">-----------------------------------------

// Client Options
const clientOptions = getDroptions('clients', {active: 1, trashed: 0});

// </editor-fold>-------------------------------------------------------

</script>

<template>
  <span v-if="errorText !== ''" class="text-red-400">
      {{ errorText }}
  </span>
  <Col>
    <Row>
      <TextInput label="Name" v-model="form['name']" placeholder="Trade show name"/>
    </Row>
    <Row v-if="user['isInternal']">
      <SelectInput label="Client" v-model="form['client_id']" :options="clientOptions" placeholder="Select a Client"/>
    </Row>
    <Row>
      <DateInput label="Advance Warehouse Dates" v-model="dates" mode="range" inline :disabled="(method === 'update') && user['isClient']"/>
    </Row>
    <Row v-if="user['isInternalAdmin']">
      <NumberInput label="Min. Carat Weight" v-model="form['min_carat_weight']"/>
      <NumberInput label="Carat Weight Incr." v-model="form['carat_weight_inc']"/>
    </Row>
    <Row>
      <TextareaInput v-model="zones" label="Zones" placeholder="Enter zones here, separated by commas, no spaces."/>
    </Row>
    <Row>
      <TextareaInput v-model="booths" label="Booths"
                     placeholder="Enter booths here, separated by commas, no spaces."/>
    </Row>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="props.close"></Button>
      <Button type="submit" label="Add New" severity="primary" @click="submitForm"></Button>
    </div>
  </Col>
</template>