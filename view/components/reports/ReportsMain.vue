<script>

import TrailerManInterface from "@/components/reports/trailer-man-interface.vue";
import ShowReportInterface from "@/components/reports/show-report-interface.vue";
import ShowReportInterfaceTwo from "@/components/reports/show-report-interface-two.vue";
import PalletManInterface from "@/components/reports/pallet-man-interface.vue";

export default {
  components: {
    TrailerManInterface,
    ShowReportInterface,
    ShowReportInterfaceTwo,
    PalletManInterface
  },
  data() {
    return {
      dateRange: null
    }
  }
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
    <show-report-interface-two/>
  </div>
</div>
</template>

<style scoped>

</style>
