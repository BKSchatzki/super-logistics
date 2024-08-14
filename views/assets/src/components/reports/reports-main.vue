<script>

import {defineComponent} from "vue";
import TrailerManInterface from "@components/reports/trailer-man-interface.vue";
import showReportInterface from "@components/reports/show-report-interface.vue";
import PalletManInterface from "@components/reports/pallet-man-interface.vue";

export default {
  components: {TrailerManInterface, showReportInterface, PalletManInterface},
}
</script>

<template>
<div>
  <div>
    <h1>Reports</h1>
  </div>
  <div>
    <trailer-man-interface/>
    <pallet-man-interface/>
    <show-report-interface/>
  </div>
</div>
</template>

<style scoped>

</style>
