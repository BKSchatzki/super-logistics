<script>
export default {
  props: {
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
    <label
        class="col-form-label"
        :for="field">
      {{ title }}
    </label>
    <input
        class="form-control btb-input"
        v-model="inputValue"
        :id="field"
        type="text"
        :required="required">
  </div>
</template>

<style scoped lang="less">
</style>
