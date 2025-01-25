<script setup>
import {useForm} from "@utils/composables/useForm.js";
import Col from "@/components/form/Col.vue";
import TextInput from "@/components/form/TextInput.vue";
import NumberInput from "@/components/form/NumberInput.vue";
import VerticalNumberInput from "@/components/form/VerticalNumberInput.vue";
import SelectInput from "@/components/form/SelectInput.vue";
import Row from "@/components/form/Row.vue";
import TextareaInput from "@/components/form/TextareaInput.vue";
import CheckInput from "@/components/form/CheckInput.vue";

const {form, submit, clearForm, getDroptions} = useForm({
  shipper: '',
  exhibitor: '',
  show_id: null,
  zone_id: null,
  booth_id: null,
  carrier: '',
  tracking: '',
  street_address: '',
  city: '',
  state: '',
  zip: '',
  freight_type: null,
  crate_pcs: 0,
  carton_pcs: 0,
  skid_pcs: 0,
  fiber_case_pcs: 0,
  carpet_pcs: 0,
  misc_pcs: 0,
  total_pcs: 0,
});
</script>

<template>
  <Col>
    <div class="flex flex-row align-top gap-4">
      <Panel header="Shipper Information">
        <Col>
          <Row>
            <TextInput v-model="form['shipper']" label="From" placeholder="Shipper Name"/>
          </Row>
          <Row>
            <TextInput v-model="form['street_address']" label="Street Address" placeholder="Street Address"/>
          </Row>
          <Row>
            <TextInput v-model="form['city']" label="City" placeholder="City"/>
            <SelectInput v-model="form['state']" label="State" placeholder="State" :options="stateOptions" filter/>
            <TextInput v-model="form['zip']" label="Zip Code" placeholder="Zip Code"/>
          </Row>
        </Col>
      </Panel>
      <Panel header="Show Information">
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
      </Panel>
      <Panel header="Freight Information">
        <Col>
          <Row>
            <TextInput v-model="form['carrier']" label="Carrier"/>
            <SelectInput v-model="form['freight_type']" :options="freightOptions" label="Freight Type"/>
            <NumberInput v-model="form['total_pcs']" label="Qty. of Packages" placeholder="#"/>
          </Row>
          <Row>
            <TextareaInput v-model="form['tracking']" label="Tracking Number or Pro Number"
                           placeholder="Type all of your Tracking or Pro numbers here, separated by commas. One each will appear on your labels."/>
          </Row>
        </Col>
      </Panel>
      <Panel header="Shipment Information">
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
            <NumberInput v-model="form['total_weight']" label="Total Weight"/>
          </Row>
        </Col>
      </Panel>
      <Panel header="Advance Warehouse Processing">
        <Col>
          <Row>
            <TextInput label="Pallet No." v-model="form['pallet']"/>
            <TextInput label="Trailer No." v-model="form['trailer']"/>
            <CheckInput label="Special Handling" v-model="form['special_handling']"/>
          </Row>
          <Row>
            <TextareaInput label="Remarks" v-model="form['remarks']"
                           placeholder="Any special information regarding the handling of these items, required if Special Handling is checked."/>
          </Row>
        </Col>
      </Panel>
    </div>
    <div class="flex justify-end gap-2">
      <Button @click="clearForm" severity="secondary" label="Cancel / Scan Again"/>
      <Button @click="() => alert('Transaction submitted')" severity="primary" label="Receive Transaction"/>
    </div>
  </Col>
</template>