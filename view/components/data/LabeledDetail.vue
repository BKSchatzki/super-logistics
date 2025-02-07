<script setup>
import {computed} from 'vue';

const props = defineProps({
  label: String,
  property: String,
  subject: Object,
  unit: String,
  trueLabel: {
    type: String,
    default: 'Yes'
  },
  falseLabel: {
    type: String,
    default: 'No'
  }
})

// To get nested data from the subject object - iterating through nested objects
const data = computed(() => {
  if (!props.property) return '';
  const d = props.property.split('.').reduce((obj, key) => obj[key], props.subject);
  if (typeof d === 'boolean') {
    return d ? props.trueLabel : props.falseLabel;
  }
  if (!d) {
    return props.falseLabel;
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