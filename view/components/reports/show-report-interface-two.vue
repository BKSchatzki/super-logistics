<script>
import reportModal from "@/components/reports/report-modal.vue";
import SimpleField from "@/components/form-components/simple-field.vue";
import DropdownField from "@/components/form-components/dropdown-field.vue";
import DataMixin from "@/components/data-input/mixin"
import ReportMixin from "@/components/reports/mixin"

export default {
  components: {DropdownField, SimpleField, reportModal},
  mixins: [DataMixin, ReportMixin],
  data() {
    return {
      startDate: null,
      endDate: null,
      selectedClient: "",
      selectedShow: "",
    }
  },
  computed: {
    shows() {
      return this.$store.state.shows;
    },
    clients() {
      return this.$store.state.clients;
    },
  },
  methods: {
    viewReport() {
      console.log(this.selectedClient, this.selectedShow, this.startDate, this.endDate);
      this.viewShowReportTwo(
          this.selectedClient,
          this.selectedShow,
          this.startDate,
          this.endDate
      )
    }
  },
  created() {
    this.getClients();
    this.getRelevantShows();
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
  <report-modal modal-i-d="show-report-two" title="Show Report Two" :viewFunc="viewReport">
    <div class="row">
      <div class="col">
        <dropdown-field :choices="clients" v-model="selectedClient" field="client"></dropdown-field>
      </div>
      <div class="col">
        <dropdown-field :choices="shows" v-model="selectedShow" field="show"></dropdown-field>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <simple-field type="date" v-model="startDate" field="start" :required="true"/>
      </div>
      <div class="col">
        <simple-field type="date" v-model="endDate" field="end" :required="true"/>
      </div>
    </div>
  </report-modal>
</template>

<style>

</style>
