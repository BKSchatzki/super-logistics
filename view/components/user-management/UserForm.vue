<script setup>
import {computed, ref, watch} from "vue";
import {useStore} from "vuex";
import {useForm} from 'vee-validate';
import * as yup from 'yup';
import {useFormAssist} from "@utils/composables/useFormAssist";
import {useAPI} from "@utils/composables/useAPI";
import FormSelectInput from "@/components/form/FormSelectInput.vue";
import FormTextInput from "@/components/form/FormTextInput.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";

// <editor-fold desc="Setup">-------------------------------------------------------

const user = computed(() => useStore().state.user);

const props = defineProps({
  formData: {type: Object, default: () => ({})},
  method: {type: String, default: 'post'},
  close: {
    type: Function, default: () => {
    }
  },
  description: String
});

// </editor-fold>-------------------------------------------------------------------

// <editor-fold desc="Form">--------------------------------------------------------

// Validation Schema
const validationSchema = yup.object().shape({
  first_name: yup.string().required().label('First Name'),
  last_name: yup.string().required().label('Last Name'),
  user_login: yup.string().required().label('Username'),
  user_email: yup.string().email().required().label('Email'),
  role: yup.string().required().label('User Type'),
  client_id: yup.number().nullable().label('Client ID'),
  shows: yup.array().of(yup.number()).nullable().label('Shows'),
});

// Initial Values
const getIDList = (shows) => {
  return shows.map(show => show['id']);
}
const clientID = computed(() => {
  if (user.value['isClient']) {
    return user.value['client']['id'];
  } else if (props.formData['client']) {
    return props.formData['client']['id'];
  } else {
    return undefined;
  }
});
const initialValues = ref({
  first_name: '',
  last_name: '',
  user_login: '',
  user_email: '',
  role: '',
  ...props.formData,
  ...(clientID.value ? {client_id: clientID.value} : {}),
  ...(props.formData['shows'] ? {shows: getIDList(props.formData['shows'])} : {})
});

// Form
const {meta, values, errors, handleSubmit} = useForm({validationSchema, initialValues});
const {submitToAPI, getRoleDroptions, getDroptions} = useFormAssist();

// Submission
const submitForm = handleSubmit(async (values) => {
  await submitToAPI('users', values, props.method);
  props.close();
})

// </editor-fold>---------------------------------------------------------------------

// <editor-fold desc="Options">-------------------------------------------------------

const {get} = useAPI();

// Role Options
const roleOptions = getRoleDroptions();

// Client Options
const clientOptions = getDroptions('clients')
watch(clientOptions, (newOptions) => {
});
const displayClientField = computed(() => {
  return (values['role'] === 'client_employee' || values['role'] === 'client_admin') && user.value['isInternal'];
});
watch(() => values['role'], (newRole) => {
  if (newRole === 'client_admin' || newRole === 'client_employee') {
    get({active: 1, trashed: 0}, 'clients');
  }
});

// Show Options
const showOptions = getDroptions('shows', {client_id: values['client_id']});
const displayShowField = computed(() => values['role'] === 'client_employee');
watch(() => values['client_id'], (cID) => {
  if (cID) {
    get({client_id: cID, active: 1, trashed: 0}, 'shows');
  }
});

// </editor-fold>---------------------------------------------------------------------

</script>

<template>
  <form @submit.prevent="submitForm" novalidate>
    <Col>
      <Row v-if="description">
        <span>{{ description }}</span>
      </Row>
      <Row>
        <FormTextInput name="first_name" label="First Name" placeholder="First Name"/>
        <FormTextInput name="last_name" label="Last Name" placeholder="Last Name"/>
      </Row>
      <Row>
        <FormTextInput label="Username" name="user_login" placeholder="No spaces or special characters please"
                       :disabled="method === 'update'"/>
      </Row>
      <Row>
        <FormTextInput label="Email" name="user_email" placeholder="example@domain.com"/>
      </Row>
      <Row>
        <FormSelectInput type="select" label="Type" :options="roleOptions" name="role" placeholder="Select"/>
        <FormSelectInput v-if="displayClientField" type="select" label="Client" :options="clientOptions"
                         name="client_id" placeholder="Select"/>
      </Row>
      <Row>
        <FormSelectInput v-if="displayShowField" type="select" label="Shows" :options="showOptions" name="shows"
                         placeholder="Assign Multiple" multiple/>
      </Row>
      <div class="flex justify-end gap-2">
        <Button type="button" label="Cancel" severity="secondary" @click="close"></Button>
        <Button type="submit" label="Add New" severity="primary" @click="submitForm"></Button>
      </div>
    </Col>
  </form>
</template>