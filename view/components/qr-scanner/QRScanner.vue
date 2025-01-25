<script setup>
import {ref} from 'vue'
import NewTransactionForm from "@/components/transactions/NewTransactionForm.vue";

const showScanner = ref(true);
const decodedContent = ref(null);
const openForm = ref(false);

const onDecode = (content) => {
  showScanner.value = false;
  decodedContent.value = JSON.parse(content[0].rawValue);
}
const resetScanner = () => {
  this.decodedContent = null;
  this.showScanner = true;
  this.$store.commit('setTransaction', {});
}
</script>

<template>
  <div>
    <div v-if="showScanner" class="border-4 border-solid border-sky-950 rounded-lg p-0 ">
      <QrcodeStream @detect="onDecode"></QrcodeStream>
    </div>
    <NewTransactionForm v-else/>
  </div>
</template>
