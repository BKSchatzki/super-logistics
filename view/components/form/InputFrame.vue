<script setup>
import {ErrorMessage, Field} from "vee-validate";
import {IftaLabel} from "primevue";

defineProps({
  id: String,
  label: String,
  class: {
    type: String,
    default: 'w-full'
  },
  labelClass: {
    type: String,
    default: 'font-semibold'
  },
  name: String,
});
</script>

<template>
  <div class="flex flex-col gap-1.5 w-full">
    <IftaLabel :class>
      <slot/>
      <label :for="id" :class="labelClass">{{ label }}</label>
    </IftaLabel>
    <ErrorMessage class="text-red-500 text-xs" :name/>
  </div>
</template>