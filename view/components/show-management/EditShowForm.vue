<script setup>
import FormRow from "@/components/form/FormRow.vue";
import TextInput from "@/components/form/TextInput.vue";
import {useForm} from "@utils/useForm";

const props = defineProps({
  subject: Object
});
const emit = defineEmits(['close']);

const {form, clearForm, submit} = useForm(props.subject);

// Submission
const toastConfig = {
  success: {
    summary: 'Update',
    detail: `Successfully updated show`
  },
  error: {
    summary: 'Update',
    detail: `Failed to update show`
  }
}
const submitEditForm = async () => {
  await submit('shows', 'update', toastConfig)
  emit('close')
}
const cancel = () => {
  clearForm();
  emit('close');
}
</script>

<template>
  <Toast/>
  <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
    Edit show name, the ID will always be static.
  </span>
  <FormRow>
    <TextInput label="Last Name" v-model="form.name"/>
  </FormRow>
  <div class="flex justify-end gap-2">
    <Button type="button" label="Cancel" severity="secondary" @click="cancel"></Button>
    <Button type="button" label="Save" severity="primary" @click="submitEditForm"></Button>
  </div>
</template>

<style scoped>

</style>