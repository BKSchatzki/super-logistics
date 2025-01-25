<script setup>
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";
import TextInput from "@/components/form/TextInput.vue";
import {useForm} from "@utils/composables/useForm.js";

const props = defineProps({
  subject: Object,
  stop: Function
});
const emit = defineEmits(['close']);

const initialData = {
  ...props.subject,
};
const {form, clearForm, submit} = useForm(initialData);

// Submission
const submitEditClientForm = async () => {
  await submit('clients', 'update')
  props.stop()
}
const cancel = () => {
  clearForm();
  props.stop();
}
</script>

<template>
  <Toast/>
  <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
    Edit client name, the ID will always be static.
  </span>
  <Col>
    <Row>
      <TextInput label="Name" v-model="form.name"/>
    </Row>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="cancel"></Button>
      <Button type="button" label="Save" severity="primary" @click="submitEditClientForm"></Button>
    </div>
  </Col>
</template>