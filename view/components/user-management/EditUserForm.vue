<script setup>
import {computed} from "vue";
import {useStore} from "vuex";
import {useForm} from "@utils/useForm";
import SelectInput from "@/components/form/SelectInput.vue";
import TextInput from "@/components/form/TextInput.vue";
import FormRow from "@/components/form/FormRow.vue";

const store = useStore();

const props = defineProps({
  subject: Object
});
const emit = defineEmits(['closed']);

// Permissions
const user = computed(() => store.state.user);

// Form
const subjectShows = computed(() => props.subject.shows ? props.subject.shows.map(s => s.id) : []);
const initialData = {
  ...props.subject,
  shows: subjectShows.value,
  client: props.subject.client ? props.subject.client.id : null,
};
const {form, getDroptions, clearForm, getRoleDroptions, submit} = useForm(initialData);

// Submission
const toastConfig = {
  success: {
    summary: 'Update',
    detail: `Successfully updated user}`
  },
  error: {
    summary: 'Update',
    detail: `Failed to update user`
  }
}
const submitEditUserForm = async () => {
  await submit('users', 'update', toastConfig)
  emit('close')
}
const cancel = () => {
  clearForm();
  emit('close');
}

// Select Options

// Role Options
const roleOptions = getRoleDroptions(user.value);

// Client Options
const clientOptions = getDroptions('clients')
const displayClientField = computed(() => {
  return (form.role === 'client_employee' || form.role === 'client_admin') && user.value['isInternal'];
});

// Show Options
const showOptions = getDroptions('shows', {client_id: form.client});
const displayShowField = computed(() => form.role === 'client_employee' && user.value['isInternal']);


</script>

<template>
  <Toast/>
  <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
    Edit user information. Passwords and usernames cannot be changed.
  </span>
  <FormRow>
    <TextInput label="First Name" v-model="form.first_name"/>
    <TextInput label="Last Name" v-model="form.last_name"/>
  </FormRow>
  <FormRow>
    <TextInput label="Username" v-model="form.user_login" disabled/>
  </FormRow>
  <FormRow>
    <TextInput label="Email" v-model="form.user_email"/>
  </FormRow>
  <FormRow>
    <SelectInput ref="roleInput" type="select" label="Type" :options="roleOptions" v-model="form.role"
               placeholder="Select Type of User"/>
    <SelectInput v-if="displayClientField" type="select" label="Client" :options="clientOptions" v-model="form.client"
               placeholder="Select the Client"/>
  </FormRow>
  <FormRow>
    <SelectInput v-if="displayShowField" type="select" label="Shows" :options="showOptions" v-model="form.shows"
               placeholder="Select Shows" multiple/>
  </FormRow>
  <div class="flex justify-end gap-2">
    <Button type="button" label="Cancel" severity="secondary" @click="cancel"></Button>
    <Button type="button" label="Save" severity="primary" @click="submitEditUserForm"></Button>
  </div>
</template>