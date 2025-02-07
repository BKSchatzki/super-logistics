<script setup>
import {v4 as uuid} from 'uuid';
import {useField} from 'vee-validate';

const props = defineProps({
  label: String,
  name: {
    type: String,
    required: true
  },
  id: {
    type: String,
    default: uuid()
  },
  size: {
    type: String,
    default: 'large'
  }
})

const {value} = useField(props.name)
</script>

<template>
  <div class="flex items-center gap-2">
    <Checkbox v-model="value" :inputId="id" size="large" binary/>
    <label :for="id"> {{ label }} </label>
  </div>
</template>