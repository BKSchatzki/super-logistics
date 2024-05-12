<script>
import LabelsMixin from "@components/transaction-lookup/mixin";

export default {
  mixins: [LabelsMixin],
}
</script>

<template>
  <div class="sl-labels">
    <h3>Label Generator</h3>
    <p>Print all your labels here.</p>
  </div>
</template>

<style lang="less" scoped>
h3 {
  font-family: "Roboto", sans-serif;
}
</style>
