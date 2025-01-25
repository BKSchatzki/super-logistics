<script setup>
import Row from "@/components/form/Row.vue";
import TextInput from "@/components/form/TextInput.vue";
import {useForm} from "@utils/composables/useForm.js";
import SelectInput from "@/components/form/SelectInput.vue";

const props = defineProps({
  subject: Object
});
const emit = defineEmits(['close']);


const {form, clearForm, submit, getDroptions} = useForm({
  ...props.subject,
  show_id: props.subject.show.id
});

// Submission
const toastConfig = {
  success: {
    summary: 'Update',
    detail: `Successfully updated transaction`
  },
  error: {
    summary: 'Update',
    detail: `Failed to update transaction`
  }
}
const submitEditForm = async () => {
  await submit('transactions', 'update', toastConfig)
  emit('close')
}
const cancel = () => {
  clearForm();
  emit('close');
}

// Shows Droptions
const showOptions = getDroptions('shows');
</script>

<template>
  <Toast/>
  <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
    Edit transaction name, show, or zone. Additional zones will require additional transactions.
  </span>
  <Row>
    <TextInput label="Name" v-model="form.name"/>
  </Row>
  <Row>
    <SelectInput label="Show" v-model="form.show_id" :options="showOptions"/>
  </Row>
  <Row>
    <TextInput label="Zone" v-model="form.zone"/>
  </Row>
  <div class="flex justify-end gap-2">
    <Button type="button" label="Cancel" severity="secondary" @click="cancel"></Button>
    <Button type="button" label="Save" severity="primary" @click="submitEditForm"></Button>
  </div>
</template>

<style scoped>

</style>