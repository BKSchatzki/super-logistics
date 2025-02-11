<script setup>
import {ref} from 'vue'
import ReceiverForm from "@/components/receiver-management/ReceiverForm.vue";
import PageTitle from "@/components/general-ui/PageTitle.vue";
import Col from "@/components/form/Col.vue";

const showScanner = ref(true);
const decodedContent = ref(null);

const onDecode = (content) => {
  showScanner.value = false;
  decodedContent.value = JSON.parse(content[0].rawValue);
}
const resetScanner = () => {
  decodedContent.value = null;
  showScanner.value = true;
}
</script>

<template>
  <Col>
    <div class="mb-4">
      <PageTitle title="Scanner"/>
    </div>
    <Panel>
      <div v-if="showScanner" class="flex flex-row items-center gap-4">
        <div class="flex flex-col w-full gap-4">
          <div class="border-4 border-solid border-red-800 rounded-lg p-0 max-w-screen-md">
            <QrcodeStream @detect="onDecode"></QrcodeStream>
          </div>
          <router-link to="/transactions">
            <Button severity="contrast" label="View All Transactions" icon="pi pi-list"/>
          </router-link>
        </div>
      </div>
      <ReceiverForm v-else :close="resetScanner" :labelData="decodedContent"/>
    </Panel>
  </Col>
</template>
