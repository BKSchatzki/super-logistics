<script>

export default {
  props: {
    field: {
      type: String,
      required: true
    },
    label: {
      type: String,
    },
    value: {
      type: String,
      required: true
    },
    choices: {
      type: Array,
      required: true
    },
    isShow: {
      type: Boolean,
      default: false
    },
    required: {
      type: Boolean,
      default: false
    },
    readOnly: {
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
    selectedValue: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      }
    },
    rootClass() {
      return this.readOnly ? 'text-nowrap mb-2' : '';
    },
    infoValue() {
      const theRightChoice = this.choices.find(c => c.id === parseInt(this.value));
      return theRightChoice ? theRightChoice.name : 'Select';
    }
  }
}
</script>

<template>
  <div :class="`col d-flex align-items-center ${rootClass}`">
    <label v-if="!readOnly" class="col-form-label me-2" :for="field">
      {{ title }}{{ required ? '*' : ''}}
    </label>
    <select v-if="!readOnly" class="form-control" :id="field" v-model="selectedValue">
      <option value="">Select</option>
      <option v-for="choice in choices" :value="choice.id">
        {{ choice.name }}
      </option>
    </select>
    <slot v-if="!readOnly" name="add-new"></slot>
    <p v-if="readOnly" class="col-form-label me-2 text-secondary">
      {{ title }}
    </p>
    <p v-if="readOnly" class="col mb-0 form-control text-break fw-bold"
       :id="`${label}-info`">
      {{ infoValue }}
    </p>
  </div>
</template>
