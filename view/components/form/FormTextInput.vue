<script setup>
import {v4 as uuid} from 'uuid';
import InputText from "primevue/inputtext";
import InputFrame from "@/components/form/InputFrame.vue";
import {useField} from "vee-validate";

const props = defineProps({
  id: {type: String, default: uuid()},
  label: String,
  name: {type: String, required: true},
  placeholder: String,
  disabled: Boolean,
})

const {value, errorMessage, validate} = useField(props.name);

</script>

<template>
  <InputFrame :id :label :name>
    <InputText
        v-model="value"
        :invalid="!!errorMessage"
        @blur="validate"
        class="flex-auto w-full min-w-24"
        :id
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputFrame>
</template>