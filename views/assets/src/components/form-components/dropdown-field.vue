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
    addNew: {
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
    }
  }
}
</script>

<template>
  <div class="sl-field col">
    <label class="col-form-label" :for="field">{{ title }}</label>
    <select class="form-control sl-input" :id="field" v-model="selectedValue">
      <option value="">Select</option>
      <option v-for="choice in choices" :value="choice[0]">
        {{ choice[1] }}
      </option>
    </select>
    <button v-if="addNew" class="add-new fa-solid fa-circle-plus" ></button>
  </div>
</template>

<style scoped lang="less">
.add-new {
  color: #0d66c2;
  border: none;
}
.sl-input {
  width: 100%;
  max-width: 125px;
}
</style>
