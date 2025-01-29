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


</script>

<template>
  <Toast/>
  <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
    Edit user information. Passwords and usernames cannot be changed.
  </span>
</template>