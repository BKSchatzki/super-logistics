<script setup>
import {v4 as uuid} from 'uuid';
import InputLabel from "@/components/form/InputLabel.vue";

const props = defineProps({
  id: {
    type: String,
    default: uuid(),
  },
  label: String,
  modelValue: String,
  disabled: Boolean,
  placeholder: String,
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
    <Textarea
        class="flex-auto w-full"
        inputClass="w-full"
        :id
        :modelValue
        @input="updateValue"
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputLabel>
</template>