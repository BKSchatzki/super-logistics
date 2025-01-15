<script setup>
import {computed, reactive, watch} from "vue";
import {useToast} from "primevue/usetoast"
import {useStore} from "vuex";
import {useAPI} from "@utils/useAPI";
import {useForm} from "@utils/useForm";
import SelectInput from "@/components/form/SelectInput.vue";
import TextInput from "@/components/form/TextInput.vue";
import FormRow from "@/components/form/FormRow.vue";

const store = useStore();
const {get, post} = useAPI();
const toast = useToast();

// Permissions
const user = computed(() => store.state.user);

// Form
const text = {
  client: {
    button: "Add New Client User",
    title: "New Client User",
    description: "Add a new user to handle logistics for your shows"
  },
  internal: {
    button: "Add New User",
    title: "New User",
    description: "Add any type of user to the system for use by your office or for the client."
  },
  loading: {
    button: "loading...",
    title: "loading...",
    description: "loading..."
  }
};
const formText = computed(() => {
  if (!user.value) return text.loading;
  return text[user.value['isInternal'] ? 'internal' : 'client'];
});
const staticFormData = {
  first_name: '',
  last_name: '',
  user_login: '',
  user_email: '',
  role: '',
  client: user.value.client ? user.value.client.id : null,
  shows: []
}
const {form, visible, submit, getRoleDroptions, getDroptions} = useForm(staticFormData);

// Submission
const toastConfig = reactive({
  success: {
    summary: 'New User',
    detail: `User has been added. Registration email sent to provided email.`,
  },
  fail: {
    summary: 'New User',
    detail: `There was an error adding the new user. Please try again.`,
  }
});
const submitNewForm = async () => {
  await submit('users', 'post', toastConfig);
}

// Select Options

// Role Options
const roleOptions = getRoleDroptions();

// Client Options
const clientOptions = getDroptions('clients')
const displayClientField = computed(() => {
  return (form.role === 'client_employee' || form.role === 'client_admin') && user.value['isInternal'];
});
watch(() => form.role, (newRole) => {
  if (newRole === 'client_admin' || newRole === 'client_employee') {
    get('clients');
  }
});

// Show Options
const showOptions = getDroptions('shows', {client_id: form.client});
const displayShowField = computed(() => form.role === 'client_employee' && user.value['isInternal']);
watch(() => form.client, (cID) => {
  if (cID) {
    get('shows', {client_id: cID});
  }
});

</script>

<template>
  <Button class="mb-4 right-0" :label="formText.button" @click="visible = true"/>
  <Toast/>
  <Dialog v-model:visible="visible" modal :header="formText.title" class="w-auto">
    <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
      {{ formText.description }}
    </span>
    <FormRow>
      <TextInput label="First Name" v-model="form.first_name" placeholder="Jerry"/>
      <TextInput label="Last Name" v-model="form.last_name" placeholder="Smith"/>
    </FormRow>
    <FormRow>
      <TextInput label="Username" v-model="form.user_login" placeholder="username_01"/>
    </FormRow>
    <FormRow>
      <TextInput label="Email" v-model="form.user_email" placeholder="example@domain.com"/>
    </FormRow>
    <FormRow>
      <SelectInput type="select" label="Type" :options="roleOptions" v-model="form.role"
                 placeholder="Select Type of User"/>
      <SelectInput v-if="displayClientField" type="select" label="Client" :options="clientOptions" v-model="form.client"
                 placeholder="Select the Client"/>
    </FormRow>
    <FormRow>
      <SelectInput v-if="displayShowField" type="select" label="Shows" :options="showOptions" v-model="form.shows"
                 placeholder="Select Shows" multiple/>
    </FormRow>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
      <Button type="submit" label="Add New" severity="primary" @click="submitNewForm"></Button>
    </div>
  </Dialog>
</template>