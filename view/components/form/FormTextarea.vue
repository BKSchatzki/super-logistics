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
  name: {type: String, required: true},
  disabled: {type: Boolean, default: false},
  placeholder: String,
})

const {value, errorMessage, validate} = useField(props.name);

</script>

<template>
  <InputFrame :id :label :name>
    <Textarea
        v-model="value"
        :invalid="!!errorMessage"
        @blur="validate"
        class="flex-auto w-full"
        inputClass="w-full"
        :id
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputFrame>
</template>