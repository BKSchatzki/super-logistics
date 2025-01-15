<script setup>
import {computed} from "vue";
import {useStore} from "vuex";
import {useForm} from "@utils/useForm";
import AutoInput from "@/components/form/AutoInput.vue";
import FormRow from "@/components/form/FormRow.vue";
import {toSingular} from "@utils/helpers.js";

const store = useStore();
const props = defineProps({
  subject: Object,
  initialFormData: Object,
  topic: String
});
const emit = defineEmits(['closed']);

// Permissions
const user = computed(() => store.state.user);

// Form
const {form, clearForm, submit} = useForm(props.initialFormData);

// Submission
const topicSingular = toSingular(props.topic)
const toastConfig = {
  success: {
    summary: `${topicSingular} Updated`,
    detail: `Successfully updated ${topicSingular}`
  },
  error: {
    summary: 'Update Failed',
    detail: `Failed to update ${topicSingular}`
  }
}
const submitEditUserForm = async () => {
  await submit(props.topic, 'update', toastConfig)
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
    Edit user information. Passwords and usernames cannot be changed.
  </span>
  <FormRow v-for="row of props.initialFormData">
    <AutoInput v-for="field of row" v-if="field.display" :fieldData="field" v-model="field.value"/>
  </FormRow>
  <div class="flex justify-end gap-2">
    <Button type="button" label="Cancel" severity="secondary" @click="cancel"></Button>
    <Button type="button" label="Save" severity="primary" @click="submitEditUserForm"></Button>
  </div>
</template>