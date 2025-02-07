<script setup>
import {v4 as uuid} from 'uuid';
import InputFrame from "@/components/form/InputFrame.vue";
import {useField} from "vee-validate";

const props = defineProps({
  id: {
    type: String,
    default: uuid(),
  },
  label: String,
  name: String,
  placeholder: String,
  default: Number,
  disabled: Boolean,
})

const {value, errorMessage, validate} = useField(props.name);

</script>

<template>
  <InputFrame :id :label :name>
    <InputNumber
        v-model="value"
        :invalid="!!errorMessage"
        :defaultValue="props.default"
        @blur="validate"
        class="flex-auto w-full"
        inputClass="w-12"
        :id
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputFrame>
</template>