<script>
import OverviewMixin from "@components/project/project-overview/mixin";
export default {
  name: "profitability",
  mixins: [OverviewMixin],
  computed: {
    materialCosts() {
      return this.$store.state.materialCosts;
    },
    laborCosts() {
      return 2370;
    },
    payments() {
      return 2000;
    },
  },
  created() {
    this.getMaterialCosts(this.$store.state.project.id);
    this.getLaborCosts(this.$store.state.project.id);
  },
}
</script>

<template>
  <div>
    <h2>Project Profitability</h2>
    <table class="p-table" aria-describedby="Profitability Table">
      <tr>
        <th>Material Costs</th>
        <td>{{ materialCosts }}</td>
      </tr>
      <tr>
        <th>Labor Costs</th>
        <td>{{ laborCosts }}</td>
      </tr>
      <tr>
        <th>Total in Payments</th>
        <td>{{ payments }}</td>
      </tr>
      <tr>
        <th>Profitability</th>
        <td>{{ payments - (laborCosts + materialCosts) }}</td>
      </tr>
    </table>
  </div>
</template>

<style scoped lang="less">
.p-table {
  width: 62%;
  border-collapse: collapse;
  th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
  }
  th {
    background-color: #f2f2f2;
  }
}
</style>
