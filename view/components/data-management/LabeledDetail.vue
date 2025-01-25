<script setup>
import {computed} from 'vue';

const props = defineProps({
  label: String,
  property: String,
  subject: Object,
  unit: String
})

const data = computed(() => {
  const d = props.property.split('.').reduce((obj, key) => obj[key], props.subject);
  if (typeof d === 'boolean') {
    return d ? props.property : 'Not ' + props.property;
  }
  return d;
});

const label = computed(() => {
  return props.label ?? props.property
});
</script>

<template>
  <div class="flex flex-row justify-between mb-0">
    <span class="capitalize align_baseline font-bold">{{ label }}:</span>
    <span class="capitalize align_baseline font-extralight">{{ data }} {{ unit }}</span>
  </div>
</template>

<style scoped>

</style>