<script setup>
import {ref, watch} from "vue";
import {useStore} from "vuex";
import {useFormAssist} from "@utils/composables/useFormAssist";
import {formatDateForJS} from "@utils/helpers";
import * as yup from 'yup';
import Row from "@/components/form/Row.vue";
import DateInput from "@/components/form/DateInput.vue";
import ShowTextarea from "@/components/show-management/ShowTextarea.vue";
import Col from "@/components/form/Col.vue";
import FormTextInput from "@/components/form/FormTextInput.vue";
import FormSelectInput from "@/components/form/FormSelectInput.vue";
import FormNumberInput from "@/components/form/FormNumberInput.vue";
import {useForm} from "vee-validate";

// <editor-fold desc="Setup">-----------------------------------------

const props = defineProps({
  formData: {
    type: Object,
    default: () => ({})
  },
  close: {
    type: Function,
    required: true
  },
  method: {
    type: String,
    default: 'post'
  },
  description: {
    type: String,
    default: null
  }
})
const user = ref(useStore().state.user);

// </editor-fold>-------------------------------------------------------

// <editor-fold desc="Form">-----------------------------------------

// Validation Schema
const validationSchema = yup.object().shape({
  name: yup.string().required().label('Name'),
  min_carat_weight: yup.number().optional().label('Min. Carat Weight'),
  carat_weight_inc: yup.number().optional().label('Carat Weight Incr.'),
  zones: yup.array().of(yup.string()).optional().label('Zones'),
  booths: yup.array().of(yup.string()).optional().label('Booths'),
  client_id: yup.number().required().label('Client'),
  date_start: yup.date().required().label('Start Date'),
  date_end: yup.date().required().label('End Date')
});

// Initial Values
const initialValues = ref({
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

const {values, handleSubmit, setFieldValue} = useForm({validationSchema, initialValues});
const {submitToAPI, getDroptions} = useFormAssist();

// <editor-fold desc="Special Fields">-----------------------------------------

// Client ID
watch(user, (newVal) => {
  if (newVal['isClient']) {
    values.client_id = newVal['client']['id'];
  }
}, {immediate: true});

// Dates
const dates = ref([values['date_start'], values['date_end']]);
watch(dates, (newVal) => {
  setFieldValue('date_start', newVal[0]);
  setFieldValue('date_end', newVal[1]);
});

//</editor-fold>--------------------------------------------------------

// Submit
const submitForm = handleSubmit(async () => {
  await submitToAPI('shows', values, props.method);
  props.close();
})

//</editor-fold>--------------------------------------------------------

// <editor-fold desc="Options">-----------------------------------------

// Client Options
const clientOptions = getDroptions('clients', {active: 1, trashed: 0});

// </editor-fold>-------------------------------------------------------

</script>

<template>
  <Toast/>
  <Col>
    <Row v-if="description">
      <span>{{ description }}</span>
    </Row>
    <Row>
      <FormTextInput label="Name" name="name" placeholder="Trade show name"/>
    </Row>
    <Row v-if="user['isInternal']">
      <FormSelectInput label="Client" name="client_id" :options="clientOptions" placeholder="Select a Client"/>
    </Row>
    <Row>
      <DateInput label="Advance Warehouse Dates" v-model="dates" mode="range" inline
                 :disabled="(method === 'update') && user['isClient']"/>
    </Row>
    <Row v-if="user['isInternalAdmin']">
      <FormNumberInput label="Min. Carat Weight" name="min_carat_weight"/>
      <FormNumberInput label="Carat Weight Incr." name="carat_weight_inc"/>
    </Row>
    <Row>
      <ShowTextarea name="zones" label="Zones" placeholder="Enter zones here, separated by commas, no spaces."/>
    </Row>
    <Row>
      <ShowTextarea name="booths" label="Booths" placeholder="Enter booths here, separated by commas, no spaces."/>
    </Row>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="props.close" data-test="cancel-button"/>
      <Button type="submit" :label="props.method === 'post' ? 'Add New' : 'Update'" severity="primary" @click="submitForm" data-test="submit-button"/>
    </div>
  </Col>
</template>