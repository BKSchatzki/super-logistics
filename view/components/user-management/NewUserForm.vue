<script setup>
import {computed, reactive, watch} from "vue";
import {useStore} from "vuex";
import {useAPI} from "@utils/composables/useAPI.js";
import {useForm} from "@utils/composables/useForm.js";
import SelectInput from "@/components/form/SelectInput.vue";
import TextInput from "@/components/form/TextInput.vue";
import Col from "@/components/form/Col.vue";
import Row from "@/components/form/Row.vue";

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

</script>

<template>
  <Button class="mb-4 right-0" :label="formText.button" @click="visible = true"/>
  <Dialog v-model:visible="visible" modal :header="formText.title" class="w-auto">
    <span class="text-surface-500 dark:text-surface-400 block mb-8 w-full">
      {{ formText.description }}
    </span>
  </Dialog>
</template>