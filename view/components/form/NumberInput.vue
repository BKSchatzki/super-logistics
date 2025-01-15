<script setup>
import {v4 as uuid} from 'uuid';
import InputLabel from "@/components/form/InputLabel.vue";

const props = defineProps({
  id: {
    type: String,
    default: uuid(),
  },
  label: String,
  modelValue: [String, Number],
  default: Number,
  disabled: Boolean,
})

// Change Handling defined in composable
const emits = defineEmits(['update:modelValue']);
const updateValue = (event) => {
  const value = event.target ? event.target.value : event.value;
  emits('update:modelValue', value);
};

</script>

<template>
  <InputLabel :id :label="label">
    <InputNumber
        class="flex-auto w-full"
        inputClass="w-12"
        :id
        :modelValue
        @input="updateValue"
        :default
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputLabel>
</template>