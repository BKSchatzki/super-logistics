<script setup>
import {computed, reactive, watchEffect} from "vue";
import {useStore} from "vuex";
import {useForm} from "@utils/composables/useForm.js";
import Col from "@/components/form/Col.vue";
import TextInput from "@/components/form/TextInput.vue";
import VerticalNumberInput from "@/components/form/VerticalNumberInput.vue";
import SelectInput from "@/components/form/SelectInput.vue";
import Row from "@/components/form/Row.vue";
import TextareaInput from "@/components/form/TextareaInput.vue";
import CheckInput from "@/components/form/CheckInput.vue";
import {frhtOptions, stateOptions} from "@utils/dropdowns.js";
import {useAPI} from "@utils/composables/useAPI";

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

// Form
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
  ...props.labelData,
});
const {form, submit, getDroptions} = useForm(initialFormData);
watchEffect(() => {
  form['total_pcs'] = form['crate_pcs'] + form['carton_pcs'] + form['skid_pcs'] + form['fiber_case_pcs'] + form['carpet_pcs'] + form['misc_pcs'];
})
const submitButtonText = computed(() => {
  return props.method === 'post' ? 'Receive Transaction' : 'Update Transaction';
})

// Submit
const {print} = useAPI('transactions');
const submitNewForm = async () => {
  await submit('transactions', props.method);
  props.close();
}
const submitAndPrint = async () => {
  const data = await submit('transactions', props.method);
  form['id'] = data.id;
  await print(form, 'transactions/receiving/labels', 'shipping labels');
  await print(form, 'transactions/receiving/docs', 'receiving forms');
  // props.close();
}

// Options

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
              <SelectInput v-model="form['shipper_state']" label="State" placeholder="State" :options="stateOptions" filter/>
              <TextInput v-model="form['shipper_zip']" label="Zip Code" placeholder="Zip Code"/>
            </Row>
            <Row>
              <Button severity="primary" @click="() => activateCallback('2')" label="Next" icon="pi pi-arrow-down"/>
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
              <Button severity="primary" @click="() => activateCallback('3')" label="Next" icon="pi pi-arrow-down"/>
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
              <Button severity="primary" @click="() => activateCallback('4')" label="Next" icon="pi pi-arrow-down"/>
            </Row>
          </Col>
        </StepPanel>
      </StepItem>
      <StepItem value="4">
        <Step>Shipment Information</Step>
        <StepPanel v-slot="{ activateCallback }">
          <div class="flex flex-col items-center">
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
                <Button severity="secondary" @click="() => activateCallback('3')" label="Back" icon="pi pi-arrow-up"/>
                <Button severity="primary" @click="() => activateCallback('5')" label="Next" icon="pi pi-arrow-down"/>
              </Row>
            </Col>
          </div>
        </StepPanel>
      </StepItem>
      <StepItem value="5">
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
              <Button severity="secondary" @click="() => activateCallback('4')" label="Back" icon="pi pi-arrow-up"/>
            </Row>
          </Col>
        </StepPanel>
      </StepItem>
    </Stepper>
    <div class="flex items-end gap-4">
      <Button @click="close" severity="secondary" label="Cancel"/>
      <Button @click="submitNewForm" severity="primary" :label="submitButtonText"/>
      <Button @click="submitAndPrint" severity="primary" :label="`${submitButtonText} and Print`"/>
    </div>
  </Col>
</template>