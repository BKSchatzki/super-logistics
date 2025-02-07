<script setup>
import {v4 as uuid} from 'uuid';
import InputFrame from "@/components/form/InputFrame.vue";

const props = defineProps({
  id: {
    type: String,
    default: uuid(),
  },
  label: String,
  modelValue: [String, Number],
  placeholder: String,
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
  <InputFrame :id :label="label">
    <InputNumber
        class="flex-auto w-full"
        inputClass="w-12"
        :id
        :modelValue
        @input="updateValue"
        :defaultValue="props.default"
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputFrame>
</template>