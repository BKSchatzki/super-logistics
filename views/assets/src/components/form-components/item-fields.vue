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
      bols: this.initItem.bols,
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
        bols: this.bols,
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
    }
  },
  watch: {
    pcs() { this.emitUpdate(); },
    bols() { this.emitUpdate(); },
    weight() { this.emitUpdate(); },
    notes() { this.emitUpdate(); },
    tracking() { this.emitUpdate(); }
  }
}
</script>

<template>
  <div class="input-group input-group-sm mb-1">
    <span class="input-group-text sl-item-label-complex">{{ label }}</span>
    <input class="form-control" type="number" v-model="pcs" :disabled="readOnly"/>
    <input class="form-control" type="number" v-model="bols" :disabled="readOnly"/>
    <input class="form-control" type="number" v-model="weight" :disabled="readOnly"/>
    <input class="form-control sl-items-note" type="text" v-model="notes" :disabled="readOnly"/>
    <input class="form-control" type="text" v-model="tracking" :disabled="readOnly"/>
  </div>
</template>

