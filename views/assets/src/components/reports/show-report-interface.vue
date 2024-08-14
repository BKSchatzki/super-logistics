<script>
import reportModal from "@components/reports/report-modal.vue";
import SimpleField from "@components/form-components/simple-field.vue";

export default {
  components: {SimpleField, reportModal},
  data() {
    return {
      trailerNum: ''
    }
  },
  methods: {
    viewReport() {
      console.log(this.trailerNum);
    }
  }
}
</script>

<template>
  <report-modal title="Trailer Manifest" :viewFunc="viewReport">
    <label for="trailer-manifest-input">Trailer Number:</label>
    <input id="trailer-manifest-input" type="text" v-model="trailerNum"/>
  </report-modal>
</template>

<style scoped lang="less">

</style>
