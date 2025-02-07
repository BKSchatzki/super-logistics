<script setup>
import {computed} from "vue";
import * as yup from "yup";
import {useStore} from "vuex";
import {useForm} from "vee-validate";
import {useFormAssist} from "@utils/composables/useFormAssist.js";
import {useAPI} from "@utils/composables/useAPI";
import FormTextInput from "@/components/form/FormTextInput.vue";
import FormTextarea from "@/components/form/FormTextarea.vue";
import FormNumberInput from "@/components/form/FormNumberInput.vue";
import FormSelectInput from "@/components/form/FormSelectInput.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";
import {freightOptions, stateOptions} from "@utils/dropdowns.js";
import PageTitle from "@/components/general-ui/PageTitle.vue";

// <editor-fold desc="Form">--------------------------------------------------

// Validation
const validationSchema = yup.object({
  shipper: yup.string().required('Shipper is required'),
  exhibitor: yup.string().required('Exhibitor is required'),
  show_id: yup.number().nullable().required('Show is required'),
  zone_id: yup.number().nullable().required('Zone is required'),
  booth_id: yup.number().nullable().required('Booth is required'),
  carrier: yup.string().required('Carrier is required'),
  tracking: yup.string().optional(),
  street_address: yup.string().required('Street address is required'),
  shipper_city: yup.string().required('City is required'),
  shipper_state: yup.string().required('State is required'),
  shipper_zip: yup.string().required('Zip code is required'),
  freight_type: yup.number().optional(),
  total_pcs: yup.number().nullable().required('Total pieces are required').moreThan(0, 'Total pieces must be greater than 0'),
});

const {values, errors, meta, handleSubmit, resetForm} = useForm({validationSchema});
const {print} = useAPI();
const printLabels = handleSubmit(values => {
  const printPromise = print(values, 'transactions/shipping', 'shipping labels');
  printPromise.then(() => {
    resetForm();
  })
});

// </editor-fold>--------------------------------------------------------------

// <editor-fold desc="Options">------------------------------------------------

const {getDroptions} = useFormAssist();
const store = useStore();

// Shows
const showOptions = getDroptions('shows');

// <editor-fold desc="Show Places">---------------------------

// Restricting show places to the selected show
const shows = computed(() => store.state.shows); // getDroptions populated the store in the line before
const selectedShow = computed(() => {
  return shows.value.find(show => show.id === values.show_id);
});

// Zones
const zoneOptions = computed(() => {
  const zones = selectedShow.value ? selectedShow.value.zones : [];
  return zones.map(zone => {
    return {label: zone.name, value: zone.id};
  });
});

// Booths
const boothOptions = computed(() => {
  const booths = selectedShow.value ? selectedShow.value.booths : [];
  return booths.map(booth => {
    return {label: booth.name, value: booth.id};
  });
});

// </editor-fold>----------------------------------------------

// </editor-fold>--------------------------------------------------------------

</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="mb-4">
      <PageTitle title="Shipping"/>
    </div>
    <Panel header="Shipping Labels">
      <div class="mb-8">
        <p>Please fill out the following form to print shipping labels. No account is necessary, only items received by
          the advance warehouse will be billed.</p>
      </div>
      <form @submit.prevent="printLabels">
        <Col>
          <div class="flex flex-row align-top gap-4">
            <Col>
              <Row>
                <FormTextInput name="shipper" label="From" placeholder="Shipper Name"/>
              </Row>
              <Row>
                <FormTextInput name="street_address" label="Street Address" placeholder="Street Address"/>
              </Row>
              <Row>
                <FormTextInput name="shipper_city" label="City" placeholder="City"/>
                <FormSelectInput name="shipper_state" label="State" placeholder="State" :options="stateOptions" filter/>
                <FormTextInput name="shipper_zip" label="Zip Code" placeholder="Zip Code"/>
              </Row>
              <Row>
                <FormTextInput name="carrier" label="Carrier" placeholder="Carrier Name"/>
                <FormSelectInput name="freight_type" :options="freightOptions" label="Freight Type"
                                 placeholder="Select"/>
                <FormNumberInput name="total_pcs" label="Qty. of Packages" placeholder="#"/>
              </Row>
            </Col>
            <Col>
              <Row>
                <FormTextInput name="exhibitor" label="Exhibitor" placeholder="Exhibitor Name"/>
              </Row>
              <Row>
                <FormSelectInput name="show_id" label="Show" :options="showOptions" placeholder="Select Show"/>
              </Row>
              <Row>
                <FormSelectInput name="zone_id" label="Zone" :options="zoneOptions" filter placeholder="Select Zone"/>
                <FormSelectInput name="booth_id" label="Booth" :options="boothOptions" filter
                                 placeholder="Select Booth"/>
              </Row>
            </Col>
          </div>
          <Row>
            <FormTextarea name="tracking" label="Tracking Number or Pro Number"
                          placeholder="Type your tracking numbers here, all of the tracking numbers will appear on all of your labels."/>
          </Row>
          <div class="flex justify-end gap-2">
            <Button @click="resetForm" severity="secondary" label="Clear"/>
            <Button @click="printLabels" severity="primary" label="Print Labels"/>
          </div>
        </Col>
      </form>
    </Panel>
  </div>
</template>
