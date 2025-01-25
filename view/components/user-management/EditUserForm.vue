<script setup>
import {computed, watch} from "vue";
import {useStore} from "vuex";
import {useForm} from "@utils/composables/useForm.js";
import {useAPI} from "@utils/composables/useAPI.js";
import SelectInput from "@/components/form/SelectInput.vue";
import TextInput from "@/components/form/TextInput.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";

const store = useStore();
const props = defineProps({
  subject: {type: Object, required: true},
  stop: {type: Function, required: true}
});

// Permissions
const user = computed(() => store.state.user);

// Form
const subjectShows = computed(() => props.subject['shows'] ? props.subject['shows'].map(s => s.id) : []);
console.log("subject: ", props.subject);
const {form, getDroptions, clearForm, getRoleDroptions, submit} = useForm({
  ...props.subject,
  shows: subjectShows.value,
  client_id: props.subject['client'] ? props.subject['client'].id : null,
});

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
  props.stop()
}
const cancel = () => {
  clearForm()
  props.stop()
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
const showOptions = getDroptions('shows', {client_id: form['client_id']});
const displayShowField = computed(() => form['role'] === 'client_employee' && user.value['isInternal']);
const {get: getShows} = useAPI('shows');
watch(() => form['client_id'], (cID) => {
  if (cID) {
    getShows({client_id: cID});
  }
});

</script>

<template>
  <Toast/>
  <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
    Edit user information. Passwords and usernames cannot be changed.
  </span>
  <Col>
    <Row>
      <TextInput label="First Name" v-model="form['first_name']"/>
      <TextInput label="Last Name" v-model="form['last_name']"/>
    </Row>
    <Row>
      <TextInput label="Username" v-model="form['user_login']" disabled/>
    </Row>
    <Row>
      <TextInput label="Email" v-model="form['user_email']"/>
    </Row>
    <Row>
      <SelectInput ref="roleInput" type="select" label="Type" :options="roleOptions" v-model="form['role']"
                   placeholder="Select Type of User"/>
      <SelectInput v-if="displayClientField" type="select" label="Client" :options="clientOptions"
                   v-model="form['client_id']"
                   placeholder="Select the Client"/>
    </Row>
    <Row>
      <SelectInput v-if="displayShowField" type="select" label="Shows" :options="showOptions" v-model="form.shows"
                   placeholder="Select Shows" multiple/>
    </Row>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="cancel"></Button>
      <Button type="button" label="Save" severity="primary" @click="submitEditUserForm"></Button>
    </div>
  </Col>
</template>