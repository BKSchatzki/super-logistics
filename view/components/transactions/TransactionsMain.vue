<script setup>
import {computed} from "vue";
import {useStore} from "vuex";
import NewTxnForm from "./NewTxnForm.vue";

const store = useStore();
// be able to add new transactions
// show transactions
const txns = computed(() => store.state.txns);
// edit transactions
// mark transactions inactive
// delete transactions
</script>

<template>
  <div>
    <div class="flex flex-row justify-between mb-4">
      <h1>Transactions</h1>
      <NewTxnForm/>
    </div>
    <DataTable :value="txns">
      <template #empty>No transactions to see here. Go ahead and make one!</template>
    </DataTable>
  </div>
</template>