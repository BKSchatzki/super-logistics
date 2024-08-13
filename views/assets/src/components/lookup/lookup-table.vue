<script>
import LookupRow from '@components/lookup/lookup-row.vue';
import LookupMixin from '@components/lookup/mixin';

export default {
  mixins: [LookupMixin],
  props: {
    lookupResults: {
      type: Array,
      required: true
    },
  },
  components: {
    LookupRow
  },
  methods: {
    toggleResults() {
      this.lookupResults = [];
    }
  },
  created() {
    this.getUsers();
  },
}
</script>

<template>
  <table class="table table-hover" aria-describedby="table of transaction lookup results">
    <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Show</th>
      <th scope="col">Client</th>
      <th scope="col">Carrier</th>
      <th scope="col">Shipper</th>
      <th scope="col">Exhibitor</th>
    </tr>
    </thead>
    <tbody>
      <lookup-row v-for="r in lookupResults" :trans="r"/>
    </tbody>
  </table>
</template>

<style scoped lang="less">

</style>
