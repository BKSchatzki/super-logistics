<script setup>
import { computed, ref } from "vue";
import { useStore } from "vuex";
import { useAPI } from "@utils/composables/useAPI";
import { frhtOptions } from "@utils/dropdowns";
import LabeledDetail from "@/components/data/LabeledDetail.vue";
import ManageDetails from "@/components/data/ManageDetails.vue";
import ReceiverForm from "@/components/receiver-management/ReceiverForm.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";

const props = defineProps({
  subject: Object,
});

const { print, post } = useAPI();
const store = useStore();
const user = computed(() => store.state.user ?? {});
const isInternalUser = computed(() => !!user.value?.isInternal);

const receivingPrintDisabledReason = computed(() => {
  return isInternalUser.value
    ? ""
    : "Internal users only (receiving print policy).";
});
const receivingPrintDisabled = computed(
  () => !!receivingPrintDisabledReason.value,
);

const hasPod = computed(() => !!props.subject?.pod_path);

const podViewDisabledReason = computed(() => {
  if (!hasPod.value) {
    return "No carrier POD attached.";
  }
  return "";
});
const podViewDisabled = computed(() => !!podViewDisabledReason.value);

const printLabels = () => {
  if (receivingPrintDisabled.value) return;
  print(
    props.subject,
    "transactions/receiving/labels",
    "Advance Warehouse Labels",
  );
};

const printReceivers = () => {
  if (receivingPrintDisabled.value) return;
  print(
    props.subject,
    "transactions/receiving/docs",
    "Advance Warehouse Receivers",
  );
};

const printLabelsReceivers = () => {
  if (receivingPrintDisabled.value) return;
  printLabels();
  printReceivers();
};

const printPOD = () => {
  if (podViewDisabled.value) return;
  print(
    { id: props.subject.id },
    "transactions/receiving/pod",
    "Proof of Delivery",
  );
};

const podFileInput = ref(null);
const podUploading = ref(false);
const podDeleting = ref(false);
const confirmingDeletePod = ref(false);

const triggerPodUpload = () => {
  podFileInput.value?.click();
};

const handlePodFileSelected = (event) => {
  const file = event.target.files?.[0];
  if (!file) return;
  podUploading.value = true;
  post(
    { id: props.subject.id, pod: file },
    "transactions/receiving/pod/upload",
    false,
    "Carrier POD uploaded.",
    "Failed to upload carrier POD.",
  )
    .then((res) => {
      props.subject.pod_path = res?.data?.pod_path ?? file.name;
    })
    .finally(() => {
      podUploading.value = false;
      if (podFileInput.value) podFileInput.value.value = "";
    });
};

const deletePOD = () => {
  podDeleting.value = true;
  post(
    { id: props.subject.id },
    "transactions/receiving/pod/delete",
    false,
    "Carrier POD deleted.",
    "Failed to delete carrier POD.",
  )
    .then(() => {
      props.subject.pod_path = null;
    })
    .finally(() => {
      podDeleting.value = false;
      confirmingDeletePod.value = false;
    });
};

const podFilename = computed(() => {
  if (!props.subject?.pod_path) return null;
  try {
    return decodeURIComponent(props.subject.pod_path.split("/").pop());
  } catch {
    return props.subject.pod_path.split("/").pop();
  }
});
</script>

<template>
  <ManageDetails
    topic="transactions"
    :subject="props.subject"
    header="Receiver Details"
  >
    <template #detail-body>
      <Panel>
        <template #header>
          <Row>
            <span class="text-xl font-extralight">Receiver No.</span>
            <span class="text-xl font-extralight">{{ subject["id"] }}</span>
          </Row>
        </template>
        <LabeledDetail :subject label="Shipper" property="shipper" />
        <LabeledDetail :subject label="Exhibitor" property="exhibitor" />
        <LabeledDetail :subject label="Client" property="client" />
        <LabeledDetail :subject label="Show" property="show" />
        <LabeledDetail :subject label="Zone" property="zone" />
        <LabeledDetail :subject label="Booth" property="booth" />
        <LabeledDetail
          :subject
          label="Pallet"
          property="pallet"
          falseLabel="Incomplete"
        />
        <LabeledDetail
          :subject
          label="Trailer"
          property="trailer"
          falseLabel="Incomplete"
        />
        <LabeledDetail :subject label="Carrier" property="carrier" />
        <LabeledDetail :subject label="Tracking" property="tracking" />
      </Panel>
      <Panel header="Shipment Information">
        <div class="flex flex-row justify-between mb-0">
          <span class="capitalize align_baseline font-bold">Freight Type:</span>
          <span class="capitalize align_baseline font-extralight">{{
            frhtOptions.find((opt) => opt.value === props.subject.freight_type)
              .label
          }}</span>
        </div>
        <LabeledDetail
          :subject
          label="Crates"
          property="crate_pcs"
          falseLabel="0"
        />
        <LabeledDetail
          :subject
          label="Cartons"
          property="carton_pcs"
          falseLabel="0"
        />
        <LabeledDetail
          :subject
          label="Skids"
          property="skid_pcs"
          falseLabel="0"
        />
        <LabeledDetail
          :subject
          label="Fiber Cases"
          property="fiber_case_pcs"
          falseLabel="0"
        />
        <LabeledDetail
          :subject
          label="Carpets"
          property="carpet_pcs"
          falseLabel="0"
        />
        <LabeledDetail
          :subject
          label="Misc"
          property="misc_pcs"
          falseLabel="0"
        />
        <LabeledDetail :subject label="Total Packages" property="total_pcs" />
        <LabeledDetail :subject label="Total Weight" property="total_weight" />
      </Panel>
      <Panel header="Handling">
        <LabeledDetail
          :subject
          label="Special Handling"
          property="special_handling"
        />
        <LabeledDetail :subject label="Remarks" property="remarks" />
        <LabeledDetail :subject label="Received" property="nice_created_at" />
        <LabeledDetail
          :subject
          label="Received by"
          property="created_by_user.user_login"
        />
        <LabeledDetail
          :subject
          label="Last Updated"
          property="nice_updated_at"
        />
        <LabeledDetail
          :subject
          label="Last Updated by"
          property="updated_by_user.user_login"
        />
      </Panel>
      <Panel header="Status">
        <LabeledDetail :subject label="Active" property="active" />
        <LabeledDetail :subject label="Trashed" property="trashed" />
      </Panel>
      <Panel header="Printing">
        <Col>
          <Row>
            <div
              class="print-action-wrap w-full"
              :class="{ 'print-action-disabled': receivingPrintDisabled }"
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
              :class="{ 'print-action-disabled': receivingPrintDisabled }"
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
              :class="{ 'print-action-disabled': receivingPrintDisabled }"
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
        </Col>
      </Panel>
      <Panel header="Carrier POD">
        <Col>
          <div class="pod-status text-sm mb-2">
            <span v-if="hasPod" class="text-green-600">
              Attached: {{ podFilename }}
            </span>
            <span v-else class="text-gray-500"> No carrier POD attached. </span>
          </div>
          <input
            ref="podFileInput"
            type="file"
            accept=".pdf,.jpg,.jpeg,.png"
            class="hidden"
            @change="handlePodFileSelected"
          />
          <Row>
            <div
              class="print-action-wrap w-full"
              :class="{ 'print-action-disabled': podViewDisabled }"
              :data-disabled-reason="podViewDisabledReason"
            >
              <Button
                class="w-full"
                type="button"
                severity="contrast"
                variant="outlined"
                label="View/Print POD"
                :disabled="podViewDisabled"
                @click="printPOD"
              />
            </div>
          </Row>
          <Row v-if="isInternalUser">
            <Button
              class="w-full"
              type="button"
              severity="info"
              variant="outlined"
              :label="
                podUploading
                  ? 'Uploading...'
                  : hasPod
                    ? 'Replace POD'
                    : 'Upload POD'
              "
              :disabled="podUploading"
              @click="triggerPodUpload"
            />
          </Row>
          <Row v-if="isInternalUser && hasPod">
            <Button
              v-if="!confirmingDeletePod"
              class="w-full"
              type="button"
              severity="danger"
              variant="outlined"
              label="Delete POD"
              @click="confirmingDeletePod = true"
            />
            <template v-else>
              <Button
                class="w-full"
                type="button"
                severity="secondary"
                variant="outlined"
                label="Cancel"
                :disabled="podDeleting"
                @click="confirmingDeletePod = false"
              />
              <Button
                class="w-full"
                type="button"
                severity="danger"
                :label="podDeleting ? 'Deleting...' : 'Confirm Delete'"
                :disabled="podDeleting"
                @click="deletePOD"
              />
            </template>
          </Row>
        </Col>
      </Panel>
    </template>
    <template #edit-form="{ close }">
      <ReceiverForm :labelData="subject" :close method="update" />
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
