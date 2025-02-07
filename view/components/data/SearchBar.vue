<script setup>
const props = defineProps({
  modelValue: String,
  placeholder: {type: String, default: "Search everywhere"}
})
const emits = defineEmits(['update:modelValue']);
const updateValue = (event) => {
  const value = event.target ? event.target.value : event.value;
  emits('update:modelValue', value);
};
</script>

<template>
  <IconField class="w-auto">
    <InputIcon>
      <i class="pi pi-search"/>
    </InputIcon>
    <InputText :value="modelValue" :placeholder @input="updateValue"/>
  </IconField>
</template>