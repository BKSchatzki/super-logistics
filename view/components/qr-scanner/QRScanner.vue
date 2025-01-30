<script setup>
import {ref} from 'vue'
import TransactionForm from "@/components/transactions/TransactionForm.vue";

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
  <div>
    <div v-if="showScanner" class="flex flex-col md:justify-items-center gap-4">
      <h1 class="font-sans text-3xl">Scan Shipping Labels</h1>
      <div class="border-4 border-solid border-red-800 rounded-lg p-0 ">
        <QrcodeStream @detect="onDecode"></QrcodeStream>
      </div>
      <router-link to="/transactions">
        <Button severity="contrast" label="View All Transactions" icon="pi pi-list"/>
      </router-link>
    </div>
    <div v-else>
      <h1 class="font-sans text-3xl">New Transaction</h1>
      <TransactionForm :close="resetScanner" :labelData="decodedContent"/>
    </div>
  </div>
</template>
