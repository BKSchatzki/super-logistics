<script setup>
import {v4 as uuid} from 'uuid';
import InputLabel from "@/components/form/InputLabel.vue";

// Data and Configuration
const props = defineProps({
  id: {
    type: String,
    default: uuid(),
  },
  label: String,
  modelValue: [Date, Array],
  mode: {
    type: String,
    default: 'single',
  },
  inline: Boolean,
  disabled: Boolean,
})

// <editor-fold desc="Change Handling">
const emits = defineEmits(['update:modelValue']);
const updateValue = (event) => {
  // Defined to handle the range mode, single may require a different approach
  if (props.modelValue.length === 2) {
    emits('update:modelValue', [event]);
  } else {
    emits('update:modelValue', [props.modelValue[0], event]);
  }
};
// </editor-fold>

</script>

<template>
  <InputLabel :id :label="label">
    <DatePicker
        class="w-full"
        :modelValue
        :selectionMode="mode"
        :inline
        @date-select="updateValue"
        :disabled
    />
  </InputLabel>
</template>