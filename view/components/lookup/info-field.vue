<script>

export default {
  props: {
    label: {
      type: String,
      required: true
    },
    val: {
      type: String,
      required: true
    },
    width: {
      type: Number,
      default: null
    },
  },
  computed: {
    title: function() {
      let field = (this.field || '').replace(/\b\w/g, function (char) {
        return char.toUpperCase();
      });
      return this.label || field;
    },
    wide: function() {
      return (this.width === null) ? '' : `-${this.width}`;
    }
  },

}
</script>

<template>
  <div :class="`col${wide} d-flex align-items-center text-nowrap mb-2`">
    <p class="col-form-label me-2 text-secondary">
      {{ title }}
    </p>
    <p class="col mb-0 form-control text-break fw-bold"
        :id="`${label}-info`">
      {{ val }}
    </p>
  </div>
</template>

