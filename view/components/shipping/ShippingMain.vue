<script setup>
import {computed} from "vue";
import {useStore} from "vuex";
import {useForm} from "@utils/composables/useForm.js";
import {useAPI} from "@utils/composables/useAPI";
import TextInput from "@/components/form/TextInput.vue";
import TextareaInput from "@/components/form/TextareaInput.vue";
import NumberInput from "@/components/form/NumberInput.vue";
import SelectInput from "@/components/form/SelectInput.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";
import {freightOptions, stateOptions} from "@utils/dropdowns.js";

// Form
const {form, clearForm, getDroptions} = useForm({
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
  total_pcs: null,
});
const {print} = useAPI();
const printLabels = () => {
  console.log("form: ", form);
  const printPromise = print(form, 'transactions/shipping', 'shipping labels');
  printPromise.then(() => {
    clearForm();
  })
};

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

// Testing
const loadTestData = () => {
  form['shipper'] = 'Test Supply Co.';
  form['exhibitor'] = 'Exam Providers USA';
  form['show_id'] = 11;
  form['zone_id'] = 1;
  form['booth_id'] = 20;
  form['carrier'] = 'UPS';
  form['tracking'] = 'IOPUHWICPVUHNN';
  form['street_address'] = '2481 Arbor Crest Dr.';
  form['city'] = 'Midland City';
  form['state'] = 'TX';
  form['zip'] = '22312';
  form['freight_type'] = 1;
  form['total_pcs'] = 5;
}

</script>

<template>
  <div class="flex flex-col gap-4">
    <h1 class="font-sans text-3xl">Shipping</h1>
    <Button severity="danger" @click="loadTestData">Test Form</Button>
    <Fieldset class="flex flex-col gap-2" legend="Welcome" toggleable collapsed>
      <p class="mb-2 font-extralight">
        Welcome to the shipping page, if you were never assigned an account, you are probably in the right place.
      </p>
      <p class="mb-2 font-extralight">
        No account is needed to print a shipping label. The transaction is not created until the label is scanned at the
        advance warehouse.
      </p>
      <div class="mb-2">
        <span class="font-extralight">
          To print a shipping label, please fill out the following form. Multiple packages sent at once can be specified with the
        </span>
        <span class="font-normal">
          Qty. of Packages field.
        </span>
      </div>
      <p class="font-semibold">
        Thank you!
      </p>
    </Fieldset>
    <Panel header="Shipping Labels">
      <Col>
        <div class="flex flex-row align-top gap-4">
          <Col>
            <Row>
              <TextInput v-model="form['shipper']" label="From" placeholder="Shipper Name"/>
            </Row>
            <Row>
              <TextInput v-model="form['street_address']" label="Street Address" placeholder="Street Address"/>
            </Row>
            <Row>
              <TextInput v-model="form['shipper_city']" label="City" placeholder="City"/>
              <SelectInput v-model="form['shipper_state']" label="State" placeholder="State" :options="stateOptions" filter/>
              <TextInput v-model="form['shipper_zip']" label="Zip Code" placeholder="Zip Code"/>
            </Row>
            <Row>
              <TextInput v-model="form['carrier']" label="Carrier" placeholder="Carrier Name"/>
              <SelectInput v-model="form['freight_type']" :options="freightOptions" label="Freight Type"
                           placeholder="Select"/>
              <NumberInput v-model="form['total_pcs']" label="Qty. of Packages" placeholder="#"/>
            </Row>
          </Col>
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
          </Col>
        </div>
        <Row>
          <TextareaInput v-model="form['tracking']" label="Tracking Number or Pro Number"
                         placeholder="Type your tracking numbers here, all of the tracking numbers will appear on all of your labels."/>
        </Row>
        <div class="flex justify-end gap-2">
          <Button @click="clearForm" severity="secondary" label="Clear"/>
          <Button @click="printLabels" severity="primary" label="Print Labels"/>
        </div>
      </Col>
    </Panel>
  </div>
</template>
