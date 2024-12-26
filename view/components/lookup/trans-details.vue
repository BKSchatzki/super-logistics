<script>
import InfoField from "@/components/lookup/info-field.vue";
import LookupMixin from "@/components/lookup/mixin";

export default {
  components: {InfoField},
  mixins: [LookupMixin],
  props: {
    trans: {
      type: Object,
      required: true
    },
  },
  computed: {
    most_recent_image() {
      const updates = this.trans.updates.filter(u => u.image_path);
      if (updates.length >= 1) {
        updates.sort((a, b) => new Date(b.datetime) - new Date(a.datetime));
      } else if (updates.length === 0) {
        return '';
      }
      return this.filePathToUrl(updates[0].image_path);
    },
    most_recent_note() {
      const updates = this.trans.updates.filter(update => update.note);
      if (updates.length >= 1) {
        updates.sort((a, b) => new Date(b.datetime) - new Date(a.datetime));
      } else if (updates.length === 0) {
        return "No notes";
      }
      return updates[0].note;
    }
  }
}
</script>

<template>
  <div>
    <div>
      <div class="row mx-0 mb-2 gap-2 overflow-hidden" v-bind:style="{ height: '250px' }">
        <div class="col border rounded p-0" id="sl-image-preview">
          <img v-if="most_recent_image" style="object-fit: contain; max-width: 100%;" :src="most_recent_image" alt="Most recent view of the transaction">
        </div>
        <div class="col border rounded">
          <p class="text-secondary" >Most recent note:</p>
          <p>{{ most_recent_note }}</p>
        </div>
      </div>
      <div class="row">
        <InfoField label="Client" :val="trans.client_name"/>
        <InfoField label="Carrier" :val="trans.carrier_name"/>
        <InfoField label="Freight" :val="trans.freight_type"/>
      </div>
      <div class="row">
        <InfoField label="Show" :val="trans.show_name"/>
        <InfoField label="Shipment" :val="trans.shipment"/>
        <InfoField label="Pallet" :val="trans.pallet_no"/>
      </div>
      <div class="row">
        <InfoField label="Shipper" :val="trans.shipper_name"/>
        <InfoField label="Tracking" :val="trans.tracking"/>
        <InfoField label="Receiver" :val="trans.receiver"/>
      </div>
      <div class="row">
        <InfoField label="Exhibitor" :val="trans.exhibitor_name"/>
        <InfoField label="Zone/Color" :val="trans.place"/>
        <InfoField label="Trailer" :val="trans.trailer"/>
      </div>
    </div>
  </div>
</template>

