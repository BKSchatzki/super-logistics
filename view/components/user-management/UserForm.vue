<script setup>
import {computed, watch} from "vue";
import {useStore} from "vuex";
import {useForm} from "@utils/composables/useForm";
import {useAPI} from "@utils/composables/useAPI";
import SelectInput from "@/components/form/SelectInput.vue";
import TextInput from "@/components/form/TextInput.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";

// Permissions
const user = computed(() => useStore().state.user);

const props = defineProps({
  formData: {type: Object, default: {}},
  method: {type: String, default: 'post'},
  close: {
    type: Function, default: () => {
    }
  }
})


// Form
const getIDList = (shows) => {
  return shows.map(show => show['id']);
}
const clientID = computed(() => {
  if (user.value.isClient) {
    return user.value.client.id;
  } else if (props.formData['client']) {
    return props.formData['client']['id'];
  } else {
    return null;
  }
});
const {form, visible, submit, getRoleDroptions, getDroptions} = useForm({
  first_name: '',
  last_name: '',
  user_login: '',
  user_email: '',
  role: '',
  ...props.formData,
  client_id: clientID,
  shows: props.formData['shows'] ? getIDList(props.formData['shows']) : []
});

// Submission
const submitForm = async () => {
  await submit('users', props.method);
  props.close();
}

// <editor-fold desc="Options">--------------------------
const { get } = useAPI();

// Role Options
const roleOptions = getRoleDroptions();

// Client Options
const clientOptions = getDroptions('clients')
const displayClientField = computed(() => {
  return (form.role === 'client_employee' || form.role === 'client_admin') && user.value['isInternal'];
});
watch(() => form.role, (newRole) => {
  if (newRole === 'client_admin' || newRole === 'client_employee') {
    get({active: 1, trashed: 0}, 'clients');
  }
});

// Show Options
const showOptions = getDroptions('shows', {client_id: form.client_id});
const displayShowField = computed(() => form.role === 'client_employee');
watch(() => form.client_id, (cID) => {
  if (cID) {
    get({client_id: cID, active: 1, trashed: 0}, 'shows');
  }
});

// </editor-fold>

</script>

<template>
  <Col>
    <Row>
      <TextInput label="First Name" v-model="form.first_name" placeholder="First Name"/>
      <TextInput label="Last Name" v-model="form.last_name" placeholder="Last Name"/>
    </Row>
    <Row>
      <TextInput label="Username" v-model="form.user_login" placeholder="No spaces or special characters please" :disabled="method === 'update'"/>
    </Row>
    <Row>
      <TextInput label="Email" v-model="form.user_email" placeholder="example@domain.com"/>
    </Row>
    <Row>
      <SelectInput type="select" label="Type" :options="roleOptions" v-model="form.role"
                   placeholder="Select"/>
      <SelectInput v-if="displayClientField" type="select" label="Client" :options="clientOptions"
                   v-model="form.client_id"
                   placeholder="Select"/>
    </Row>
    <Row>
      <SelectInput v-if="displayShowField" type="select" label="Shows" :options="showOptions" v-model="form.shows"
                   placeholder="Assign Multiple" multiple/>
    </Row>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="close"></Button>
      <Button type="submit" label="Add New" severity="primary" @click="submitForm"></Button>
    </div>
  </Col>
</template>