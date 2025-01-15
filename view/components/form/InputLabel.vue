<script setup>
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
  }
});
</script>

<template>
  <IftaLabel :class>
    <slot/>
    <label :for="id" :class="labelClass">{{ label }}</label>
  </IftaLabel>
</template>

<style scoped>

</style>