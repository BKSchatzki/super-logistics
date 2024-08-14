<script>
import ReportModal from "@components/reports/report-modal.vue";

export default {
  components: {ReportModal},
  data() {
    return {
      palletNum: ''
    }
  },
  methods: {
    viewReport() {
      console.log(this.palletNum);
    }
  }
}
</script>

<template>
  <report-modal title="Pallet Manifest" :viewFunc="viewReport">
    <label for="pallet-manifest-input">Pallet Number:</label>
    <input id="pallet-manifest-input" type="text" v-model="palletNum"/>
  </report-modal>
</template>

<style scoped lang="less">

</style>
