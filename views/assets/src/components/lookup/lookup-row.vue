<script>
import LookupMixin from '@components/lookup/mixin';
import TransViewBtn from "@components/lookup/trans-view-btn.vue";

export default {
  components: {TransViewBtn},
  props: {
    trans: {
      type: Object,
      required: true,
      validator: function(value) {
        const validators = [
          value.hasOwnProperty('id'),
          value.hasOwnProperty('client_id'),
          value.hasOwnProperty('show_id'),
          value.hasOwnProperty('shipper_id'),
        ];
        return validators.every((v) => v === true);
      }
    }
  },
  mixins: [LookupMixin]
}

</script>

<template>
  <tr>
    <th scope="row" class="align-middle">{{ trans.id }}</th>
    <td class="align-middle">{{ trans.show_name }}</td>
    <td class="align-middle">{{ trans.client_name }}</td>
    <td class="align-middle">{{ trans.carrier_name }}</td>
    <td class="align-middle">{{ trans.shipper_name }}</td>
    <td class="align-middle">{{ trans.exhibitor_name }}</td>
    <td>
      <trans-view-btn :trans="trans"/>
    </td>
  </tr>
</template>

<style scoped lang="less">

</style>
