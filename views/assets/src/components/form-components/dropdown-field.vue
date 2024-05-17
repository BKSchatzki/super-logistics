<script>
import addNewForm from "@components/form-components/add-new-form.vue";

export default {
  components: {
    addNewForm
  },
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
    addNewFn: {
      type: Function,
      default: null
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
  <div class="btb-field col">
    <label class="col-form-label" :for="field">{{ title }}</label>
    <select class="form-control btb-input" :id="field" v-model="selectedValue">
      <option value="">Select</option>
      <option v-for="choice in choices" :value="choice[0]">
        {{ choice[1] }}
      </option>
    </select>
    <add-new-form
        v-if="addNewFn"
        :add-new-fn="addNewFn"
        :field="field"></add-new-form>
  </div>
</template>

<style scoped lang="less">
.btb-input {
  width: 100%;
  max-width: 125px;
}
</style>
