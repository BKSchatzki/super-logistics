<script setup>
import {ref, onMounted} from 'vue';
import {useField} from "vee-validate";
import {v4 as uuid} from 'uuid';
import InputFrame from "@/components/form/InputFrame.vue";

// <editor-fold desc="Setup">-----------------------------------------

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
const {value, errorMessage, validate, setValue} = useField(props.name);

// </editor-fold>-------------------------------------------------------

// <editor-fold desc="Transform Functions">-----------------------------

const getListString = (places) => {
  return places.map(place => place['name']).join(',\n');
}
const transformValue = (val) => {
  if (val !== undefined && val !== null) {
    const places = val.split(',').map(placeName => placeName.trim());
    if (places.at(-1) === '') {
      places.pop();
    }
    setValue(places);
  } else {
    setValue([]);
  }
}
const handleInput = evt => {
  const val = evt.target.value;
  transformValue(val);
}

// </editor-fold>-------------------------------------------------------

const userFacingValue = ref(getListString(value.value));
onMounted(() => {
  transformValue(userFacingValue.value);
});

</script>

<template>
  <InputFrame :id :label :name>
    <Textarea
        v-model="userFacingValue"
        :invalid="!!errorMessage"
        @blur="validate"
        @input="handleInput"
        class="flex-auto w-full"
        inputClass="w-full"
        :id
        :placeholder
        :disabled
        autocomplete="off"
    />
  </InputFrame>
</template>