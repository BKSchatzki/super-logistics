<script setup>
import {computed, reactive, ref, watchEffect} from "vue";
import {useStore} from "vuex";
import {useAPI} from "@utils/composables/useAPI";
import {useForm} from "@utils/composables/useForm.js";
import Camera from "simple-vue-camera";
import Col from "@/components/form/Col.vue";
import TextInput from "@/components/form/TextInput.vue";
import VerticalNumberInput from "@/components/form/VerticalNumberInput.vue";
import SelectInput from "@/components/form/SelectInput.vue";
import Row from "@/components/form/Row.vue";
import TextareaInput from "@/components/form/TextareaInput.vue";
import CheckInput from "@/components/form/CheckInput.vue";
import {frhtOptions, stateOptions} from "@utils/dropdowns.js";

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

// <editor-fold desc="Form">-------------------------------------

const initialFormData = reactive({
  shipper: '',
  exhibitor: '',
  show_id: null,
  zone_id: null,
  booth_id: null,
  carrier: '',
  tracking: '',
  street_address: '',
  shipper_city: '',
  shipper_state: '',
  shipper_zip: '',
  freight_type: null,
  crate_pcs: 0,
  carton_pcs: 0,
  skid_pcs: 0,
  fiber_case_pcs: 0,
  carpet_pcs: 0,
  misc_pcs: 0,
  total_pcs: 0,
  total_weight: 0,
  pallet: '',
  trailer: '',
  special_handling: false,
  remarks: '',
  image_path: null,
  image: null,
  ...props.labelData,
});
const {form, submit, getDroptions} = useForm(initialFormData);
watchEffect(() => {
  form['total_pcs'] = form['crate_pcs'] + form['carton_pcs'] + form['skid_pcs'] + form['fiber_case_pcs'] + form['carpet_pcs'] + form['misc_pcs'];
})

// Image Capture
const camera = ref();
const resolution = ref({width: 576, height: 720});
const captureImage = async () => {
  form['image'] = await camera.value?.snapshot();
}
const clearPicture = () => {
  form['image'] = null
  form['image_path'] = null
}
const preview = computed(() => {
  if (form['image_path']) {
    return form['image_path'];
  } else if (form['image']) {
    return URL.createObjectURL(form['image']);
  }
})
const fadeIn = (event) => {
  event.target.classList.remove('opacity-0');
  event.target.classList.add('opacity-100');
};
const picFrameCSS = computed(() => {
  return form['image'] === null ? 'border-red-800' : 'border-orange-300';
})

// </editor-fold>---------------------------------------------------

// <editor-fold desc="Submit">--------------------------------------

const submitButtonText = computed(() => {
  return props.method === 'post' ? 'Receive Transaction' : 'Update Transaction';
})
const updateTxn = async () => {
  await post(form, `transactions/update`, false);
  await get({active: 1, trashed: 0});
}
const {get, post, print} = useAPI('transactions');
const submitForm = async () => {
  if (props.method === 'post') {
    await submit('transactions', props.method);
  } else if (props.method === 'update') {
    await updateTxn();
  }
  // props.close();
}
const submitAndPrint = async () => {
  let data = {};
  if (props.method === 'post') {
    data = await submit('transactions', props.method);
  } else if (props.method === 'update') {
    data = await updateTxn();
  }
  form['id'] = data.id;
  await print(form, 'transactions/receiving/labels', 'shipping labels');
  await print(form, 'transactions/receiving/docs', 'receiving forms');
  // props.close();
}

// </editor-fold>--------------------------------------------------

// <editor-fold desc="Options">------------------------------------

// Shows
const store = useStore();
const showOptions = getDroptions('shows');
const shows = computed(() => store.state.shows); // getDroptions populated the store in the line before
const selectedShow = computed(() => {
  return shows.value.find(show => show.id === form['show_id']);
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
  <Col>
    <Stepper value="4">
      <StepItem value="1">
        <Step>Shipper Information</Step>
        <StepPanel v-slot="{ activateCallback }">
          <Col>
            <Row>
              <TextInput v-model="form['shipper']" label="From" placeholder="Shipper Name"/>
            </Row>
            <Row>
              <TextInput v-model="form['shipper_city']" label="City" placeholder="City"/>
              <SelectInput v-model="form['shipper_state']" label="State" placeholder="State" :options="stateOptions"
                           filter/>
              <TextInput v-model="form['shipper_zip']" label="Zip Code" placeholder="Zip Code"/>
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
              <TextInput v-model="form['exhibitor']" label="Exhibitor" placeholder="Exhibitor Name"/>
            </Row>
            <Row>
              <SelectInput v-model="form['show_id']" label="Show" :options="showOptions" placeholder="Select Show"/>
            </Row>
            <Row>
              <SelectInput v-model="form['zone_id']" label="Zone" :options="zoneOptions" filter
                           placeholder="Select Zone"/>
              <SelectInput v-model="form['booth_id']" label="Booth" :options="boothOptions" filter
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
              <TextInput v-model="form['carrier']" label="Carrier" placeholder="Carrier Name"/>
              <SelectInput v-model="form['freight_type']" :options="frhtOptions" label="Freight Type"
                           placeholder="Select"/>
            </Row>
            <Row>
              <TextareaInput v-model="form['tracking']" label="Tracking Number or Pro Number"
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
              <VerticalNumberInput v-model="form['crate_pcs']" label="Crates"/>
              <VerticalNumberInput v-model="form['carton_pcs']" label="Cartons"/>
              <VerticalNumberInput v-model="form['carpet_pcs']" label="Carpets"/>
            </Row>
            <Row>
              <VerticalNumberInput v-model="form['fiber_case_pcs']" label="Fiber Cases"/>
              <VerticalNumberInput v-model="form['misc_pcs']" label="Misc."/>
              <VerticalNumberInput v-model="form['skid_pcs']" label="Skids"/>
            </Row>
            <Row>
              <InputGroup>
                <InputNumber v-model="form['total_weight']" placeholder="Total Weight"/>
                <InputGroupAddon>lbs.</InputGroupAddon>
              </InputGroup>
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
              <TextInput label="Pallet No." v-model="form['pallet']"/>
              <TextInput label="Trailer No." v-model="form['trailer']"/>
              <CheckInput label="Special Handling" v-model="form['special_handling']"/>
            </Row>
            <Row>
              <TextareaInput label="Remarks" v-model="form['remarks']"
                             placeholder="Required if Special Handling is checked."/>
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
          <Button class="w-full" @click="close" severity="secondary" label="Cancel"/>
          <Button class="w-full" @click="submitForm" severity="primary" :label="submitButtonText"/>
        </Row>
        <Row>
          <Button class="w-full" @click="submitAndPrint" severity="primary" :label="`${submitButtonText} and Print`"/>
        </Row>
      </Col>
    </div>
  </Col>
</template>