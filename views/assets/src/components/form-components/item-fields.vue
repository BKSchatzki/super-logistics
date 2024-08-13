<script>
export default {
  props: {
    initItem: {
      type: Object,
      required: true
    },
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      typeKey: {
        3 : "crates",
        2 : "cartons",
        4 : "skids",
        5 : "fiberCases",
        6 : "carpets",
        7 : "misc"
      },
      type: this.initItem.type,
      pcs: this.initItem.pcs,
      bol_count: this.initItem.bol_count,
      weight: this.initItem.weight,
      notes: this.initItem.notes,
      tracking: this.initItem.tracking
    }
  },
  computed: {
    label() {
      return this.camelToCapital(this.typeKey[this.type]);
    }
  },
  methods: {
    emitUpdate() {
      const updatedItem = {
        label: this.label,
        type: this.type,
        pcs: this.pcs,
        bol_count: this.bol_count,
        weight: this.weight,
        notes: this.notes,
        tracking: this.tracking
      };
      this.$emit('update-item', updatedItem);
    },
    camelToCapital(str) {
      let words = str.split(/(?=[A-Z])/);
      words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
      return words.join(' ');
    },
    setToInit() {
      this.type = this.initItem.type;
      this.pcs = this.initItem.pcs;
      this.bol_count = this.initItem.bol_count;
      this.weight = this.initItem.weight;
      this.notes = this.initItem.notes;
      this.tracking = this.initItem.tracking;
    }
  },
  watch: {
    pcs() { this.emitUpdate(); },
    bol_count() { this.emitUpdate(); },
    weight() { this.emitUpdate(); },
    notes() { this.emitUpdate(); },
    tracking() { this.emitUpdate(); },
    initItem() { this.setToInit(); }
  }
}
</script>

<template>
  <div class="input-group input-group-sm mb-1">
    <span class="input-group-text sl-item-label-complex">{{ label }}</span>
    <input class="form-control" type="number" v-model="pcs" :readonly="readOnly"/>
    <input class="form-control" type="number" v-model="bol_count" :readonly="readOnly"/>
    <input class="form-control" type="number" v-model="weight" :readonly="readOnly"/>
    <input class="form-control sl-items-note" type="text" v-model="notes" :readonly="readOnly"/>
    <input class="form-control" type="text" v-model="tracking" :readonly="readOnly"/>
  </div>
</template>

