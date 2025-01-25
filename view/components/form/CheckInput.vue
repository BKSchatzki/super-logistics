<script setup>
import {v4 as uuid} from 'uuid';

const props = defineProps({
  label: String,
  modelValue: Boolean,
  id: {
    type: String,
    default: uuid()
  },
  size: {
    type: String,
    default: 'large'
  }
})

const emits = defineEmits(['update:modelValue']);
const updateValue = (event) => {
  const value = event.target ? event.target.checked : event;
  emits('update:modelValue', value);
};
</script>

<template>
  <div class="flex items-center gap-2">
    <Checkbox :modelValue :inputId="id" size="large" @change="updateValue" binary/>
    <label :for="id"> {{ label }} </label>
  </div>
</template>