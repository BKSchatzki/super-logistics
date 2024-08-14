<script>
import reportModal from "@components/reports/report-modal.vue";
import reportMixin from "@components/reports/mixin";

export default {
  components: {reportModal},
  data() {
    return {
      trailerNum: ''
    }
  },
  mixins: [reportMixin],
  methods: {
    viewReport() {
      console.log(this.trailerNum);
      this.viewTrailerManifest(this.trailerNum);
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
