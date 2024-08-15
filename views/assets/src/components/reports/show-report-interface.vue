<script>
import reportModal from "@components/reports/report-modal.vue";
import SimpleField from "@components/form-components/simple-field.vue";

export default {
  components: {SimpleField, reportModal},
  data() {
    return {
      dateRange: null,
      pickerOptions: {
        shortcuts: [{
          text: 'Last week',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: 'Last month',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: 'Last 3 months',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
    }
  },
  methods: {
    viewReport() {
      console.log(this.dateRange);
    }
  },
  watch: {
    dateRange: function (newVal, oldVal) {
      console.log(newVal);
      let str = newVal;
      if (str.length === 2 && oldVal.length !== 3) {
        this.dateRange += '/';
      }
      if (str.length === 5 && oldVal.length !== 6) {
        this.dateRange += '/';
      }
      if (str.length > 10) {
        this.dateRange = str.slice(0, 10);
      }
    }
  }
}
</script>

<template>
  <report-modal modal-i-d="show-report" title="Show Report" :viewFunc="viewReport">
    <label for="show-report-input">Dates:</label>
    <simple-field type="date" v-model="dateRange" field="start" :required="true"/>
  </report-modal>
</template>

<style scoped lang="less">

</style>
