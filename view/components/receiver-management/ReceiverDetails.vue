<script setup>
import {computed} from "vue";
import {useStore} from "vuex";
import {useAPI} from "@utils/composables/useAPI";
import {frhtOptions} from "@utils/dropdowns";
import LabeledDetail from "@/components/data/LabeledDetail.vue";
import ManageDetails from "@/components/data/ManageDetails.vue";
import ReceiverForm from "@/components/receiver-management/ReceiverForm.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";

const props = defineProps({
  subject: Object
})

const {print} = useAPI();
const store = useStore();
const user = computed(() => store.state.user ?? {});
const isInternalUser = computed(() => !!user.value?.isInternal);

const receivingPrintDisabledReason = computed(() => {
  return isInternalUser.value ? '' : 'Internal users only (receiving print policy).';
});
const receivingPrintDisabled = computed(() => !!receivingPrintDisabledReason.value);

const podDisabledReason = computed(() => {
  if (!isInternalUser.value) {
    return 'Internal users only (receiving print policy).';
  }
  if (!props.subject?.image_path) {
    return 'POD attachment required.';
  }
  return '';
});
const podDisabled = computed(() => !!podDisabledReason.value);

const printLabels = () => {
  if (receivingPrintDisabled.value) {
    return;
  }
  print(props.subject, "transactions/receiving/labels", "Advance Warehouse Labels");
};

const printReceivers = () => {
  if (receivingPrintDisabled.value) {
    return;
  }
  print(props.subject, "transactions/receiving/docs", "Advance Warehouse Receivers");
};

const printLabelsReceivers = () => {
  if (receivingPrintDisabled.value) {
    return;
  }
  printLabels();
  printReceivers();
};

const printPOD = () => {
  if (podDisabled.value) {
    return;
  }
  print({id: props.subject.id}, "transactions/receiving/pod", "Proof of Delivery");
};

</script>

<template>
  <ManageDetails topic="transactions" :subject="props.subject" header="Receiver Details">
    <template #detail-body>
      <Panel>
        <template #header>
          <Row>
            <span class="text-xl font-extralight">Receiver No.</span>
            <span class="text-xl font-extralight">{{ subject['id'] }}</span>
          </Row>
        </template>
        <LabeledDetail :subject label="Shipper" property="shipper"/>
        <LabeledDetail :subject label="Exhibitor" property="exhibitor"/>
        <LabeledDetail :subject label="Client" property="client"/>
        <LabeledDetail :subject label="Show" property="show"/>
        <LabeledDetail :subject label="Zone" property="zone"/>
        <LabeledDetail :subject label="Booth" property="booth"/>
        <LabeledDetail :subject label="Pallet" property="pallet" falseLabel="Incomplete"/>
        <LabeledDetail :subject label="Trailer" property="trailer" falseLabel="Incomplete"/>
        <LabeledDetail :subject label="Carrier" property="carrier"/>
        <LabeledDetail :subject label="Tracking" property="tracking"/>
      </Panel>
      <Panel header="Shipment Information">
        <div class="flex flex-row justify-between mb-0">
          <span class="capitalize align_baseline font-bold">Freight Type:</span>
          <span class="capitalize align_baseline font-extralight">{{
              frhtOptions.find(opt => opt.value === props.subject.freight_type).label
            }}</span>
        </div>
        <LabeledDetail :subject label="Crates" property="crate_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Cartons" property="carton_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Skids" property="skid_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Fiber Cases" property="fiber_case_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Carpets" property="carpet_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Misc" property="misc_pcs" falseLabel="0"/>
        <LabeledDetail :subject label="Total Packages" property="total_pcs"/>
        <LabeledDetail :subject label="Total Weight" property="total_weight"/>
      </Panel>
      <Panel header="Handling">
        <LabeledDetail :subject label="Special Handling" property="special_handling"/>
        <LabeledDetail :subject label="Remarks" property="remarks"/>
        <LabeledDetail :subject label="Received" property="nice_created_at"/>
        <LabeledDetail :subject label="Received by" property="created_by_user.user_login"/>
        <LabeledDetail :subject label="Last Updated" property="nice_updated_at"/>
        <LabeledDetail :subject label="Last Updated by" property="updated_by_user.user_login"/>
      </Panel>
      <Panel header="Status">
        <LabeledDetail :subject label="Active" property="active"/>
        <LabeledDetail :subject label="Trashed" property="trashed"/>
      </Panel>
      <Panel header="Printing">
        <Col>
          <Row>
            <div
              class="print-action-wrap w-full"
              :class="{'print-action-disabled': receivingPrintDisabled}"
              :data-disabled-reason="receivingPrintDisabledReason"
            >
              <Button
                class="w-full"
                type="button"
                severity="contrast"
                variant="outlined"
                label="Labels"
                :disabled="receivingPrintDisabled"
                @click="printLabels"
              />
            </div>
            <div
              class="print-action-wrap w-full"
              :class="{'print-action-disabled': receivingPrintDisabled}"
              :data-disabled-reason="receivingPrintDisabledReason"
            >
              <Button
                class="w-full"
                type="button"
                severity="contrast"
                variant="outlined"
                label="Receivers"
                :disabled="receivingPrintDisabled"
                @click="printReceivers"
              />
            </div>
          </Row>
          <Row>
            <div
              class="print-action-wrap w-full"
              :class="{'print-action-disabled': receivingPrintDisabled}"
              :data-disabled-reason="receivingPrintDisabledReason"
            >
              <Button
                class="w-full"
                type="button"
                severity="contrast"
                variant="outlined"
                label="Print Labels and Receivers"
                :disabled="receivingPrintDisabled"
                @click="printLabelsReceivers"
              />
            </div>
          </Row>
          <Row>
            <div
              class="print-action-wrap w-full"
              :class="{'print-action-disabled': podDisabled}"
              :data-disabled-reason="podDisabledReason"
            >
              <Button
                class="w-full"
                type="button"
                severity="contrast"
                variant="outlined"
                label="View/Print POD"
                :disabled="podDisabled"
                @click="printPOD"
              />
            </div>
          </Row>
        </Col>
      </Panel>
    </template>
    <template #edit-form="{close}">
      <ReceiverForm :labelData="subject" :close method="update"/>
    </template>
  </ManageDetails>
</template>

<style scoped>
.print-action-wrap {
  position: relative;
}

.print-action-disabled::after {
  content: attr(data-disabled-reason);
  position: absolute;
  top: -0.85rem;
  right: 0;
  z-index: 2;
  font-size: 0.625rem;
  line-height: 1;
  color: rgb(107 114 128);
  background: rgba(255, 255, 255, 0.95);
  padding: 0.1rem 0.35rem;
  border-radius: 0.25rem;
  pointer-events: none;
  white-space: nowrap;
}
</style>