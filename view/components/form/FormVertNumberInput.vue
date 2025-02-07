<script setup>

import {useField} from "vee-validate";

const props = defineProps({
  name: {
    type: String,
    required: true
  },
  id: {
    type: String,
    default: ''
  },
  label: {
    type: String,
    default: null
  },
  invalid: {
    type: Boolean,
    default: false
  }
});

const {value} = useField(props.name);
</script>

<template>
  <div class="flex flex-col gap-2 items-center">
  <InputNumber v-model="value" :inputId="id" showButtons buttonLayout="vertical"
               style="width: 6rem;" :min="0" :max="99" :invalid>
    <template #incrementbuttonicon>
      <span class="pi pi-plus" />
    </template>
    <template #decrementbuttonicon>
      <span class="pi pi-minus" />
    </template>
  </InputNumber>
  <label v-if="label" :for="id">{{ label }}</label>
  </div>
</template>