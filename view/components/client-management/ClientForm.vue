<script setup>
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";
import FormTextInput from "@/components/form/FormTextInput.vue";
import {useFormAssist} from "@utils/composables/useFormAssist.js";
import * as yup from "yup";
import {useForm} from "vee-validate";

// Setup
const props = defineProps({
  formData: {type: Object, default: () => ({})},
  method: {type: String, default: 'post'},
  close: {
    type: Function, default: () => {
    }
  },
  description: String
});

// <editor-fold desc="Form">--------------------------------------------------------

// Validation Schema
const validationSchema = yup.object().shape({
  name: yup.string().required().label('Name'),
});

// Initial Values
const initialValues = {
  ...props.formData,
};

// Form
const {meta, values, errors, handleSubmit, resetForm} = useForm({validationSchema, initialValues});
const {submitToAPI} = useFormAssist();

// Submission
const submitForm = handleSubmit(async () => {
  await submitToAPI('clients', values,props.method)
  props.close()
});
const cancel = () => {
  resetForm();
  props.close();
}

// </editor-fold>-------------------------------------------------------------------

</script>

<template>
  <Toast/>
  <form @submit.prevent="submitForm" novalidate>
    <Col>
      <Row v-if="description">
          <span>{{ description }}</span>
      </Row>
      <Row>
        <FormTextInput label="Name" name="name"/>
      </Row>
      <div class="flex justify-end gap-2">
        <Button type="button" label="Cancel" severity="secondary" @click="cancel"></Button>
        <Button type="submit" :label="props.method === 'post' ? 'Add New' : 'Update'" severity="primary" @click="submitForm"></Button>
      </div>
    </Col>
  </form>
</template>