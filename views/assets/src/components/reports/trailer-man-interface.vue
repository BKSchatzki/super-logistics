<script>
import reportModal from "@components/reports/report-modal.vue";
import reportMixin from "@components/reports/mixin";
import mainMixin from "@helpers/mixin/mixin";

export default {
  components: {reportModal},
  mixins: [mainMixin, reportMixin],
  data() {
    return {
      trailerNum: ''
    }
  },
  computed: {
    pdfurl() {
      return this.$store.state.loadedPDF;
    }
  },
  methods: {
    viewReport() {
      console.log(this.trailerNum);
      this.viewTrailerManifest(this.trailerNum);
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
  <report-modal modal-i-d="trailer-manifest" title="Trailer Manifest" :viewFunc="viewReport">
    <label for="trailer-manifest-input">Trailer Number:</label>
    <input id="trailer-manifest-input" type="text" v-model="trailerNum"/>
  </report-modal>
</template>

<style scoped lang="less">

</style>
