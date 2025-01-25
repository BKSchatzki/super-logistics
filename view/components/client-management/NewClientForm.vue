<script setup>
import {reactive} from "vue";
import {useForm} from '@utils/composables/useForm.js';
import TextInput from "@/components/form/TextInput.vue";
import Row from "@/components/form/Row.vue";
import Col from "@/components/form/Col.vue";

// Form
const text = {
  internal: {
    button: "Add New client",
    title: "New Client",
    description: "Clients organize trade shows and conventions, they are the primary billed party for shows."
  }
}
const {form, visible, submit} = useForm({name: ''});

// Add New
const toastConfig = reactive({
  success: {
    summary: 'New Client',
    detail: `Client has been added successfully.`,
  },
  fail: {
    summary: 'New Client',
    detail: `There was an error adding the new client. Please try again.`,
  }
});
const submitNewForm = async () => {
  await submit('clients', 'post', toastConfig);
}
</script>

<template>
  <Button class="mb-4 right-0" :label="text.internal.button" @click="visible = true"/>
  <Toast/>
  <Dialog v-model:visible="visible" modal :header="text.internal.title" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      {{ text.internal.description }}
    </span>
    <Col>
      <Row>
        <TextInput label="Name" v-model="form.name"/>
      </Row>
      <div class="flex justify-end gap-2">
        <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
        <Button type="submit" label="Add New" severity="primary" @click="submitNewForm"></Button>
      </div>
    </Col>
  </Dialog>
</template>