<script setup>
import {computed, ref} from 'vue';
import {useAPI} from "@utils/useAPI.js";
import {useToast} from "primevue/usetoast"
import EditUserForm from "@/components/user-management/EditUserForm.vue";
import LabeledDetail from "@/components/data-management/LabeledDetail.vue";
import {toCapitalCase, toSingular} from "@utils/helpers.js";

const props = defineProps({
  subject: Object,
  primaryKey: String,
  nameKey: String,
  details: Object,
  topic: String
})
const emit = defineEmits(['close', 'deleteUser'])
const toast = useToast()
const visible = computed(() => {
  return props.subject !== null
})

// Actions
const {destroy} = useAPI()
const status = ref('viewing')

// Deletion
const deleteSubject = () => {
  destroy(props.topic, {id: props.subject.id})
      .then(() => {
            toast.add({
              severity: 'success',
              summary: `${toCapitalCase(props.topic)} Deleted`,
              detail: `${toSingular(props.topic)} ${props.subject[props.primaryKey]} has been deleted.`,
              life: 3000
            })
            triggerUnselect()
          }
      )
}

// Closing
const triggerUnselect = () => {
  emit('close', null)
}
</script>

<template>
  <Toast/>
  <Dialog v-model:visible="visible" @update:visible="triggerUnselect" modal
          contentClass="w-[400]px min-h-60" :draggable="false">
    <template #header>
      <template v-if="status === 'viewing'">
        <span class="p-dialog-title">User Details</span>
        <Button variant="text" severity="danger" icon="pi pi-trash" rounded
                @click="() => status = 'deleting'"/>
        <Button variant="text" severity="secondary" icon="pi pi-user-edit" rounded
                @click="() => status = 'editing'"/>
      </template>
      <template v-if="status === 'deleting'">
        <span class="p-dialog-title text-red-400">Delete User</span>
      </template>
      <template v-if="status === 'editing'">
        <div class="flex flex-row gap-2">
          <span class="p-dialog-title">Edit User </span>
          <span class="p-dialog-title font-extralight">{{ props.subject.user_login }}</span>
        </div>
      </template>
    </template>
    <div v-if="status === 'viewing'" class="flex flex-col gap-2">
      <LabeledDetail :subject="subject" label="name" property="name"/>
      <LabeledDetail :subject="subject" label="Username" property="user_login"/>
      <LabeledDetail :subject="subject" label="Role" property="nice_role"/>
      <LabeledDetail v-if="props.subject.client" :subject="subject" label="Client" property="client.name"/>
      <LabeledDetail :subject="subject" label="Email" property="user_email"/>
    </div>
    <div v-if="status === 'deleting'" class="flex flex-col gap-2">
      <p class="text-red-400">Are you sure you want to delete {{ props.subject[primaryKey] }}?</p>
      <div class="flex justify-end flex-row gap-2">
        <Button label="Cancel" severity="secondary" @click="() => status = 'viewing'"/>
        <Button label="Delete" severity="danger" @click="deleteSubject"/>
      </div>
    </div>
    <div v-if="status === 'editing'" class="flex flex-col gap-2">
      <EditUserForm :subject="props.subject" @close="() => status = 'viewing'"/>
    </div>
  </Dialog>
</template>