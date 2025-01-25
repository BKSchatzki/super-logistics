<script setup>
const props = defineProps({
  modelValue: {
    type: Number,
    default: 0
  },
  id: {
    type: String,
    default: ''
  },
  label: {
    type: String,
    default: null
  }
});

// Change Handling defined in composable
const emits = defineEmits(['update:modelValue']);
const updateValue = (event) => {
  const value = event.target ? event.target.value : event.value;
  emits('update:modelValue', value);
};
</script>

<template>
  <InputNumber :modelValue :inputId="id" showButtons buttonLayout="vertical" style="width: 3rem" :min="0" :max="99">
    <template #incrementbuttonicon>
      <span class="pi pi-plus" />
    </template>
    <template #decrementbuttonicon>
      <span class="pi pi-minus" />
    </template>
  </InputNumber>
  <label v-if="label" :for="id">{{ label }}</label>
</template>

<style scoped>

</style>