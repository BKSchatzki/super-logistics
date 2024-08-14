<script>

import {defineComponent} from "vue";
import TrailerManInterface from "@components/reports/trailer-man-interface.vue";

export default defineComponent({
  components: {TrailerManInterface}
})
</script>

<template>
<div>
  <div>
    <h1>Reports</h1>
  </div>
  <div>
    <trailer-man-interface/>
  </div>
</div>
</template>

<style scoped>

</style>
