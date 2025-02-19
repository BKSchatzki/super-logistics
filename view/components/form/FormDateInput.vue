<script setup>
import {useField} from 'vee-validate';
import {v4 as uuid} from 'uuid';
import InputFrame from "@/components/form/InputFrame.vue";
import DatePicker from "primevue/datepicker";

// Data and Configuration
const props = defineProps({
  id: {type: String, default: uuid(),},
  label: String,
  mode: {type: String, default: 'single',},
  inline: Boolean,
  disabled: Boolean,
  showTime: Boolean,
  timeFormat: {type: String, default: 'HH:mm',},
  dateFormat: {type: String, default: 'M/d/y',},
  name: {type: String, required: true},
})

const {value, errorMessage} = useField(props.name)

</script>

<template>
  <InputFrame :id :label="label">
    <DatePicker
        class="w-full"
        v-model="value"
        :selectionMode="mode"
        :inline
        :disabled
        :showTime
        :timeFormat
        :dateFormat
        :invalid="!!errorMessage"
    />
  </InputFrame>
</template>