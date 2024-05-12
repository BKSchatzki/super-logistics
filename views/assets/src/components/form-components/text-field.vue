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
    }
  },
  computed: {
    title: function() {
      let field = (this.field || '').replace(/\b\w/g, function (char) {
        return char.toUpperCase();
      });
      return this.label || field;
    },
    inputValue: {
      get() {
        return this.value;
      },
      set() {
        this.$emit('input', this.inputValue);
      }
    }
  },

}
</script>

<template>
  <div class="sl-field col">
    <label class="col-form-label" :for="field">{{ title }}</label>
    <input class="form-control sl-input" v-model="inputValue" :id="field" type="text">
  </div>
</template>

<style scoped lang="less">
.sl-input {
  max-width: 125px;
}
</style>
