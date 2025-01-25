<script setup>
import {v4 as uuid} from 'uuid';
import {computed} from 'vue';
import {Select, MultiSelect} from "primevue";
import InputLabel from "@/components/form/InputLabel.vue";

const props = defineProps({
  id: {
    type: String,
    default: uuid(),
  },
  label: String,
  modelValue: [String, Array, Number],
  options: Array,
  placeholder: String,
  multiple: Boolean,
  disabled: Boolean,
  editable: Boolean,
  showClear: Boolean,
  filter: Boolean,
  type: {
    type: String,
    default: 'text'
  }
})

// Updating handled by composable
const emits = defineEmits(['update:modelValue']);
const updateValue = (event) => {
  const value = event.target ? event.target.value : event.value;
  emits('update:modelValue', value);
};

// Type
const componentType = computed(() => props.multiple ? MultiSelect : Select);

</script>

<template>
  <InputLabel :id :label="label">
    <component
        class="flex-auto w-full min-w-24"
        :is="componentType"
        :inputId="id"
        :options
        :modelValue
        optionLabel="label"
        optionValue="value"
        @change="updateValue"
        :editable
        :filter
        :showClear
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputLabel>
</template>