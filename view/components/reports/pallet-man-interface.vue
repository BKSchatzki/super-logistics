<script>
import ReportModal from "@/components/reports/report-modal.vue";
import reportMixin from "@/components/reports/mixin";
import mainMixin from "@/root/mixin.js";

export default {
  components: {ReportModal},
  mixins: [mainMixin, reportMixin],
  data() {
    return {
      palletNum: ''
    }
  },
  computed: {
    pdfurl() {
      return this.$store.state.loadedPDF;
    }
  },
  methods: {
    viewReport() {
      console.log(this.palletNum);
      this.viewPalletManifest(this.palletNum);
    }
  },
  watch: {
    pdfurl() {
      console.log("this.pdfurl: ", this.pdfurl);
      if (this.pdfurl !== '') {
        this.downloadPdf(this.pdfurl);
      }
    }
  }
}
</script>

<template>
  <report-modal modal-i-d="pallet-manifest" title="Pallet Manifest" :viewFunc="viewReport">
    <label for="pallet-manifest-input">Pallet Number:</label>
    <input id="pallet-manifest-input" type="text" v-model="palletNum"/>
  </report-modal>
</template>

<style>

</style>
