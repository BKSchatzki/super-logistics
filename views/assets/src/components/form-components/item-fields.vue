<script>
export default {
  props: {
    initItem: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      label: this.initItem.label,
      type: this.initItem.type,
      pieces: this.initItem.pieces,
      bols: this.initItem.bols,
      weight: this.initItem.weight,
      notes: this.initItem.notes,
      tracking: this.initItem.tracking
    }
  },
  methods: {
    emitUpdate() {
      const updatedItem = {
        label: this.label,
        type: this.type,
        pieces: this.pieces,
        bols: this.bols,
        weight: this.weight,
        notes: this.notes,
        tracking: this.tracking
      };
      this.$emit('update-item', updatedItem);
    }
  },
  watch: {
    type() { this.emitUpdate(); },
    pieces() { this.emitUpdate(); },
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
    <input class="form-control" type="number" v-model="pieces" />
    <input class="form-control" type="number" v-model="bols" />
    <input class="form-control" type="number" v-model="weight" />
    <input class="form-control sl-items-note" type="text" v-model="notes" />
    <input class="form-control" type="text" v-model="tracking" />
  </div>
</template>

