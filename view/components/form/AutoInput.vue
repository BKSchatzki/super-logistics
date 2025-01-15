<script setup>
import SelectInput from '@/components/form/SelectInput.vue';
import TextInput from '@/components/form/TextInput.vue';

const props = defineProps({
  fieldInfo: Object,
  modelValue: [String, Array, Number],
})

const emit = defineEmits(['update:modelValue']);
const updateValue = (event) => {
  const value = event.target ? event.target.value : event.value;
  emit('update:modelValue', value);
}
</script>

<template>
  <TextInput
      v-if="fieldInfo.type === 'text'"
      :value="modelValue"
      @input="updateValue"
  />
  <SelectInput
      v-if="fieldInfo.type === 'select'"
      :modelValue="modelValue"
      @change="updateValue"
  />

</template>

<style scoped>

</style>