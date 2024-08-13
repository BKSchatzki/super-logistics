<script>

export default {
  props: {
    type: {
      type: String,
      required: true
    },
    field: {
      type: String,
      required: true
    },
    value: {
      type: String,
      required: true
    },
    label: {
      type: String,
    },
    width: {
      type: Number,
      default: null
    },
    required: {
      type: Boolean,
      default: false
    },
    readOnly: {
      type: Boolean,
      default: false
    },
    xlLabel: {
      type: Boolean,
      required: false
    }
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
    },
    inputValue: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.$emit('input', newValue);
      }
    }
  },
}
</script>

<template>
  <div :class="`btb-field col${wide}`">
    <label v-if="!readOnly"
           class="col-form-label me-2"
           :for="`${field}-field`"
           :style="{ whiteSpace: 'nowrap', overflow: 'visible', textOverflow: 'ellipsis' }">
      {{ title }}{{ required ? '*' : ''}}
    </label>
    <input v-if="!readOnly"
           class="form-control"
           v-model="inputValue"
           :id="`${field}-field`"
           :type="type"
           :required="required">
    <p v-if="readOnly" class="col-form-label me-2 text-secondary">
      {{ title }}
    </p>
    <p v-if="readOnly" class="col mb-0 form-control text-break fw-bold"
       :id="`${label}-info`">
      {{ value }}
    </p>
  </div>
</template>

