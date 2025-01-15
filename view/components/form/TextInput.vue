<script setup>
import {v4 as uuid} from 'uuid';
import InputText from "primevue/inputtext";
import InputLabel from "@/components/form/InputLabel.vue";

const props = defineProps({
  id: {
    type: String,
    default: uuid(),
  },
  label: String,
  modelValue: [String, Number],
  placeholder: String,
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
    <InputText
        class="flex-auto w-full min-w-24"
        :id
        :value="modelValue"
        @input="updateValue"
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputLabel>
</template>