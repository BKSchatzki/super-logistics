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
      required: true,
      validator: function(array) {
        return array.every(item =>
            typeof item === 'object' &&
            item !== null &&
            'id' in item &&
            'name' in item
        );
      }
    },
    addNewFn: {
      type: Function,
      default: null
    },
    isShow: {
      type: Boolean,
      default: false
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
    <label class="col-form-label me-2" :for="field">
      {{ title }}{{ required ? '*' : ''}}
    </label>
    <select class="form-control" :id="field" v-model="selectedValue">
      <option value="">Select</option>
      <option v-for="choice in choices" :value="choice.id">
        {{ choice.name }}
      </option>
    </select>
    <add-new-form
        v-if="addNewFn"
        :add-new-fn="addNewFn"
        :field="field"
        :is-show="isShow"
        :required="required"></add-new-form>
  </div>
</template>

<style scoped lang="less">
</style>
