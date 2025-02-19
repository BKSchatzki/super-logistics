<script setup>
import {v4 as uuid} from 'uuid';
import {computed} from 'vue';
import {IftaLabel, MultiSelect, Select} from "primevue";
import {ErrorMessage, useField} from "vee-validate";

const props = defineProps({
  id: {
    type: String,
    default: uuid(),
  },
  label: String,
  name: String,
  options: Array,
  optionLabel: {
    type: String,
    default: 'label'
  },
  optionValue: {
    type: String,
    default: 'value'
  },
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

// Type
const componentType = computed(() => props.multiple ? MultiSelect : Select);

const {value, errorMessage, validate} = useField(props.name);

</script>

<template>
  <div class="flex flex-col gap-1.5 w-full">
    <IftaLabel class="w-full">
      <component
          :is="componentType"
          :inputId="id"
          v-model="value"
          :invalid="!!errorMessage"
          @blur="validate"
          class="flex-auto w-full min-w-24"
          :options
          :optionLabel
          :optionValue
          :editable
          :filter
          :showClear
          :placeholder
          :disabled
          autocomplete="off"
          :maxSelectedLabels="2"/>
      <label :for="id" class="font-semibold">{{ label }}</label>
    </IftaLabel>
    <ErrorMessage class="text-red-500 text-xs" :name/>
  </div>
</template>