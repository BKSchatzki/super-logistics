<script setup>
import {computed, onMounted, ref} from "vue";
import {useStore} from "vuex";
import U from '@utils/UserUtility';
import D from '@utils/DataUtility';
const store = useStore();

// Form
const visible = ref(false);
const text = {
  internal: {
    button: "Add New client",
    title: "New Client",
    description: "Clients organize trade shows and conventions, they are the primary billed party for shows."
  }
}
const formData = ref({
  name: ''
})

// Add New
const addNew = () => {
  D.postData('clients', formData)
  visible.value = false
}
</script>

<template>
  <Button class="mb-4 right-0" :label="text.internal.button" @click="visible = true"/>

  <Dialog v-model:visible="visible" modal :header="text.internal.title" :style="{ width: '25rem' }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      {{ text.internal.description }}
    </span>
    <div class="flex items-center gap-4 mb-4">
      <label for="name" class="font-semibold w-24">Name</label>
      <InputText v-model="formData.name" id="name" class="flex-auto" autocomplete="off"/>
    </div>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
      <Button type="button" label="Add New" severity="primary" @click="addNew"></Button>
    </div>
  </Dialog>
</template>