<script setup>
import {computed, ref, watchEffect} from "vue";
import {useStore} from "vuex";
import {useRoute} from "vue-router";
import {useAPI} from "@utils/composables/useAPI";
import {useFormAssist} from "@utils/composables/useFormAssist.js";
import {useToast} from "primevue/usetoast";
import {ErrorMessage, useForm} from "vee-validate";
import * as yup from "yup";
import Camera from "simple-vue-camera";
import {Step, StepItem, StepPanel, Stepper} from "primevue";
import Col from "@/components/form/Col.vue";
import FormTextInput from "@/components/form/FormTextInput.vue";
import FormVertNumberInput from "@/components/form/FormVertNumberInput.vue";
import FormSelectInput from "@/components/form/FormSelectInput.vue";
import Row from "@/components/form/Row.vue";
import FormTextarea from "@/components/form/FormTextarea.vue";
import FormCheckInput from "@/components/form/FormCheckInput.vue";
import {frhtOptions, stateOptions} from "@utils/dropdowns.js";
import FormNumberInput from "@/components/form/FormNumberInput.vue";
import FormDateInput from "@/components/form/FormDateInput.vue";

// <editor-fold desc="Setup">--------------------------------------

const props = defineProps({
  labelData: {type: Object, default: {}},
  close: {
    type: Function,
    required: true
  },
  method: {
    type: String,
    default: 'post'
  }
})
const startingPage = computed(() => Object.keys(props.labelData).length === 0 ? '1' : '4');
const route = useRoute();
const onReceiversPage = computed(() => route.path === '/transactions');

const submitButtonText = computed(() => {
  return props.method === 'post' ? 'Receive' : 'Update';
})

const submitted = ref(false);

// </editor-fold>--------------------------------------------------

// <editor-fold desc="Form">-------------------------------------

// Validation Schema
const validationSchema = yup.object().shape({
  shipper: yup.string().required().label('Shipper'),
  exhibitor: yup.string().required().label('Exhibitor'),
  show_id: yup.number().nullable().required().label('Show'),
  zone_id: yup.number().nullable().required().label('Zone'),
  booth: yup.string().nullable().optional().label('Booth'),
  carrier: yup.string().required().label('Carrier'),
  tracking: yup.string().optional().label('Tracking'),
  street_address: yup.string().nullable().label('Street Address'),
  shipper_city: yup.string().required().label('City'),
  shipper_state: yup.string().required().label('State'),
  shipper_zip: yup.string().required().label('Zip'),
  freight_type: yup.number().nullable().required().label('Type'),
  crate_pcs: yup.number().required().label('Crate Pieces'),
  carton_pcs: yup.number().required().label('Carton Pieces'),
  skid_pcs: yup.number().required().label('Skid Pieces'),
  fiber_case_pcs: yup.number().required().label('Fiber Case Pieces'),
  carpet_pcs: yup.number().required().label('Carpet Pieces'),
  misc_pcs: yup.number().required().label('Miscellaneous Pieces'),
  total_pcs: yup.number().moreThan(0).required().label('Total pieces'),
  total_weight: yup.number().moreThan(0).required().label('Total weight'),
  pallet: yup.string().optional().label('Pallet'),
  trailer: yup.string().optional().label('Trailer'),
  special_handling: yup.boolean().optional().label('Special Handling'),
  remarks: yup.string().when('special_handling', {
    is: true,
    then: (schema) => schema.required('Remarks are required when Special Handling is checked'),
    otherwise: (schema) => schema.optional(),
  }),
  image_path: yup.string().nullable().label('Image Path'),
  image: yup.mixed().when('image_path', {
    is: (value) => !value,
    then: schema => schema.required("Please take a picture of the shipment").label('Image'),
    otherwise: schema => schema.nullable().label('Image')
  }),
  created_at: yup.date().required().label('Received Date / Time'),
  id: yup.number().nullable().label('ID'),
});

// Initial Values
const initialValues = ref({
  shipper: props.labelData['shipper'] || '',
  exhibitor: props.labelData['exhibitor'] || '',
  show_id: props.labelData['show_id'] || null,
  zone_id: props.labelData['zone_id'] || null,
  booth: props.labelData['booth'] || '',
  carrier: props.labelData['carrier'] || '',
  tracking: props.labelData['tracking'] || '',
  street_address: props.labelData['street_address'] || '',
  shipper_city: props.labelData['shipper_city'] || '',
  shipper_state: props.labelData['shipper_state'] || '',
  shipper_zip: props.labelData['shipper_zip'] || '',
  freight_type: props.labelData['freight_type'] || null,
  crate_pcs: props.labelData['crate_pcs'] || 0,
  carton_pcs: props.labelData['carton_pcs'] || 0,
  skid_pcs: props.labelData['skid_pcs'] || 0,
  fiber_case_pcs: props.labelData['fiber_case_pcs'] || 0,
  carpet_pcs: props.labelData['carpet_pcs'] || 0,
  misc_pcs: props.labelData['misc_pcs'] || 0,
  total_pcs: props.labelData['total_pcs'] || 0,
  total_weight: props.labelData['total_weight'],
  pallet: props.labelData['pallet'] || '',
  trailer: props.labelData['trailer'] || '',
  special_handling: props.labelData['special_handling'] || false,
  remarks: props.labelData['remarks'] || '',
  image_path: props.labelData['image_path'] || null,
  image: props.labelData['image'] || null,
  created_at: props.labelData['created_at'] || new Date(),
  id: props.labelData['id'] || null,
});

const {values, errors, handleSubmit, setFieldValue} = useForm({validationSchema, initialValues});
const {submitToAPI, getDroptions} = useFormAssist();
watchEffect(() => {
  setFieldValue('total_pcs', values['crate_pcs'] + values['carton_pcs'] + values['skid_pcs'] + values['fiber_case_pcs'] + values['carpet_pcs'] + values['misc_pcs']);
})

// <editor-fold desc="Image Capture">-------------------------------

// Setup
const camera = ref();
const resolution = ref({width: 576, height: 720});

// Functions
const captureImage = async () => {
  setFieldValue('image', await camera.value?.snapshot())
}
const clearPicture = () => {
  setFieldValue('image', null);
  setFieldValue('image_path', null);
}

// UI
const preview = computed(() => {
  if (values['image_path']) {
    return values['image_path'];
  } else if (values['image']) {
    return URL.createObjectURL(values['image']);
  }
})
const fadeIn = (event) => {
  event.target.classList.remove('opacity-0');
  event.target.classList.add('opacity-100');
};
const picFrameCSS = computed(() => {
  return values['image'] === null ? 'border-red-800' : 'border-orange-300';
})

// </editor-fold>---------------------------------------------------

// </editor-fold>---------------------------------------------------

// <editor-fold desc="Submit">--------------------------------------

// Setup
const {get, post, print} = useAPI('transactions');
const toast = useToast();

// Accessory functions
const updateTxn = async () => {
  const data = await post(values, `transactions/update`, false);
  get({active: 1, trashed: 0});
  return data;
}
const displayErrors = () => {
  toast.add({
    severity: 'error',
    summary: 'Error',
    detail: 'Please correct the errors in the form',
    life: 5000
  });
  console.error(errors.value);
}

// Submit functions
const submitReceiver = async () => {
  if (props.method === 'post') {
    const res = await submitToAPI('transactions', values, props.method);
    submitted.value = true;
    return res;
  } else if (props.method === 'update') {
    const res = await updateTxn();
    return res.data
  }
}
const submitForm = async (printLabels, printReceivers) => {
  const res = await submitReceiver();
  setFieldValue('id', res.id);

  if (printLabels) {
    await print(values, 'transactions/receiving/labels', 'shipping labels');
  }
  if (printReceivers) {
    await print(values, 'transactions/receiving/docs', 'receiving forms');
  }
  if (props.method === 'update') {
    props.close();
  }
};

// Submit handlers

const submitOnly = handleSubmit(async () => {
  await submitForm(false, false);
}, displayErrors)

const submitPrintLabels = handleSubmit(async () => {
  await submitForm(true, false);
}, displayErrors)

const submitPrintReceivers = handleSubmit(async () => {
  await submitForm(false, true);
}, displayErrors)

const submitPrintBoth = handleSubmit(async () => {
  await submitForm(true, true);
}, displayErrors)

// </editor-fold>--------------------------------------------------

// <editor-fold desc="Options">------------------------------------

// Shows
const store = useStore();
const showOptions = getDroptions('shows');
const shows = computed(() => store.state.shows); // getDroptions populated the store in the line before
const selectedShow = computed(() => {
  return shows.value.find(show => show.id === values['show_id']);
});

// Show Places
const zoneOptions = computed(() => {
  const zones = selectedShow.value ? selectedShow.value.zones : [];
  return zones.map(zone => {
    return {label: zone.name, value: zone.id};
  });
});
const boothOptions = computed(() => {
  const booths = selectedShow.value ? selectedShow.value.booths : [];
  return booths.map(booth => {
    return {label: booth.name, value: booth.id};
  });
});

// </editor-fold>--------------------------------------------------

</script>

<template>
  <Col v-if="!submitted">
    <h1 class="font-sans text-3xl">New Receiver</h1>
    <Toast/>
    <Stepper :value="startingPage">
      <StepItem value="1">
        <Step>Shipper Information</Step>
        <StepPanel v-slot="{ activateCallback }">
          <Col>
            <Row>
              <FormTextInput name="shipper" label="From" placeholder="Shipper Name"/>
            </Row>
            <Row>
              <FormTextInput name="shipper_city" label="City" placeholder="City"/>
              <FormSelectInput name="shipper_state" label="State" placeholder="State" :options="stateOptions" editable/>
              <FormTextInput name="shipper_zip" label="Zip Code" placeholder="Zip Code"/>
            </Row>
            <Row>
              <Button severity="contrast" @click="() => activateCallback('2')" label="Next" icon="pi pi-arrow-down"/>
            </Row>
          </Col>
        </StepPanel>
      </StepItem>
      <StepItem value="2">
        <Step>Show Information</Step>
        <StepPanel v-slot="{ activateCallback }">
          <Col>
            <Row>
              <FormTextInput name="exhibitor" label="Exhibitor" placeholder="Exhibitor Name"/>
            </Row>
            <Row>
              <FormSelectInput name="show_id" label="Show" :options="showOptions" placeholder="Select Show"/>
            </Row>
            <Row>
              <FormSelectInput name="zone_id" label="Zone" :options="zoneOptions" filter
                               placeholder="Select Zone"/>
              <FormSelectInput name="booth" label="Booth" :options="boothOptions" optionValue="label" editable
                               placeholder="Select Booth"/>
            </Row>
            <Row>
              <Button severity="secondary" @click="() => activateCallback('1')" label="Back" icon="pi pi-arrow-up"/>
              <Button severity="contrast" @click="() => activateCallback('3')" label="Next" icon="pi pi-arrow-down"/>
            </Row>
          </Col>
        </StepPanel>
      </StepItem>
      <StepItem value="3">
        <Step>Freight Information</Step>
        <StepPanel v-slot="{ activateCallback }">
          <Col>
            <Row>
              <FormTextInput name="carrier" label="Carrier" placeholder="Carrier Name"/>
              <FormSelectInput name="freight_type" :options="frhtOptions" label="Freight Type"
                               placeholder="Select"/>
            </Row>
            <Row>
              <FormTextarea name="tracking" label="Tracking Number or Pro Number"
                            placeholder="Type all of your Tracking or Pro numbers here, separated by commas. One each will appear on your labels."/>
            </Row>
            <Row>
              <Button severity="secondary" @click="() => activateCallback('2')" label="Back" icon="pi pi-arrow-up"/>
              <Button severity="contrast" @click="() => activateCallback('4')" label="Next" icon="pi pi-arrow-down"/>
            </Row>
          </Col>
        </StepPanel>
      </StepItem>
      <StepItem value="4">
        <Step>Picture</Step>
        <StepPanel v-slot="{ activateCallback }">
          <div class="flex flex-col items-center">
            <Col>
              <Row v-if="!preview">
                <Col>
                  <div
                      :class="`flex items-center border-4 border-solid rounded-lg p-0 w-80 h-[398px] ${picFrameCSS}`">
                    <Camera ref="camera" :resolution autoplay/>
                  </div>
                  <Button severity="primary" label="Take Picture" @click="captureImage"
                          icon="pi pi-camera"/>
                </Col>
              </Row>
              <Row v-else>
                <Col>
                  <div
                      class="flex items-center border-4 border-solid border-orange-300 rounded-lg p-0 w-80 md:w-96 lg:w-96">
                    <img :src="preview" alt="Preview of the Transaction"
                         class="transition-opacity duration-1000 ease-in-out opacity-0" @load="fadeIn"/>
                  </div>
                  <Button severity="contrast" label="Retake Picture" @click="clearPicture"
                          icon="pi pi-refresh"/>
                </Col>
              </Row>
              <Row>
                <ErrorMessage class="text-xs text-red-500" name="image"/>
              </Row>
              <Row>
                <Button severity="secondary" @click="() => activateCallback('3')" label="Back" icon="pi pi-arrow-up"/>
                <Button severity="contrast" @click="() => activateCallback('5')" label="Next" icon="pi pi-arrow-down"/>
              </Row>
            </Col>
          </div>
        </StepPanel>
      </StepItem>
      <StepItem value="5">
        <Step>Shipment Information</Step>
        <StepPanel v-slot="{ activateCallback }">
          <Col>
            <Row>
              <FormVertNumberInput name="crate_pcs" label="Crates" :invalid="!!errors['total_pcs']"/>
              <FormVertNumberInput name="carton_pcs" label="Cartons" :invalid="!!errors['total_pcs']"/>
              <FormVertNumberInput name="carpet_pcs" label="Carpets" :invalid="!!errors['total_pcs']"/>
            </Row>
            <Row>
              <FormVertNumberInput name="fiber_case_pcs" label="Fiber Cases" :invalid="!!errors['total_pcs']"/>
              <FormVertNumberInput name="misc_pcs" label="Misc." :invalid="!!errors['total_pcs']"/>
              <FormVertNumberInput name="skid_pcs" label="Skids" :invalid="!!errors['total_pcs']"/>
            </Row>
            <ErrorMessage class="text-red-500 text-xs" name="total_pcs"/>
            <Row>
              <FormNumberInput name="total_weight" label="Total Weight"/>
            </Row>
            <Row>
              <Button severity="secondary" @click="() => activateCallback('4')" label="Back" icon="pi pi-arrow-up"/>
              <Button severity="contrast" @click="() => activateCallback('6')" label="Next" icon="pi pi-arrow-down"/>
            </Row>
          </Col>
        </StepPanel>
      </StepItem>
      <StepItem value="6">
        <Step>Advance Warehouse Processing</Step>
        <StepPanel v-slot="{ activateCallback }">
          <Col>
            <Row>
              <FormTextInput label="Pallet No." name="pallet" placeholder="Incomplete"/>
              <FormTextInput label="Trailer No." name="trailer" placeholder="Incomplete"/>
              <FormCheckInput label="Special Handling" name="special_handling"/>
            </Row>
            <Row>
              <FormTextarea label="Remarks" name="remarks" placeholder="Required if Special Handling is checked."/>
            </Row>
            <Row>
              <FormDateInput label="Received Date / Time" name="created_at" showTime hourFormat="24" dateFormat="M/d/y"/>
            </Row>
            <Row>
              <Button severity="secondary" @click="() => activateCallback('5')" label="Back" icon="pi pi-arrow-up"/>
            </Row>
          </Col>
        </StepPanel>
      </StepItem>
    </Stepper>
    <div class="flex items-end gap-4">
      <Col>
        <Row>
          <Button class="w-full" @click="submitPrintBoth" severity="primary"
                  :label="`${submitButtonText} and Print All`"/>
        </Row>
        <Row>
          <Button class="w-full" @click="submitPrintLabels" severity="primary"
                  :label="`${submitButtonText} and Print Labels`"/>
          <Button class="w-full" @click="submitPrintReceivers" severity="primary"
                  :label="`${submitButtonText} and Print Receivers`"/>
        </Row>
        <Row>
          <Button class="w-full" @click="close" severity="secondary" label="Cancel"/>
          <Button class="w-full" @click="submitOnly" severity="primary" :label="`${submitButtonText}`"/>
        </Row>
      </Col>
    </div>
  </Col>
  <Col v-else>
    <div class="flex flex-col items-center gap-4">
      <i class="pi pi-check-circle text-primary-500 text-3xl"/>
      <span class="text-3xl font-light">Receiver {{ values['id'] }} Submitted</span>
      <router-link v-if="!onReceiversPage" to="/transactions">
        <Button severity="contrast" label="View All Transactions" icon="pi pi-list"/>
      </router-link>
      <Button @click="close" severity="secondary" :label="onReceiversPage ? 'Close' : 'Scan Again'"
              :icon="onReceiversPage ? 'pi pi-check' : 'pi pi-qrcode'"/>
    </div>
  </Col>
</template>