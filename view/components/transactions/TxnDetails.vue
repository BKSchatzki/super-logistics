<script setup>
import {computed, ref} from 'vue';
import {useAPI} from "@utils/composables/useAPI.js";
import {useToast} from "primevue/usetoast"
import EditTxnForm from "@/components/transactions/EditTxnForm.vue";
import LabeledDetail from "@/components/data-management/LabeledDetail.vue";

const props = defineProps({
  subject: Object
})
const emit = defineEmits(['close', 'deleted'])
const visible = computed(() => {
  return props.subject !== null
})
const toast = useToast()

// Actions
const {markInactive, trash} = useAPI('transactions')
const status = ref('viewing')

// Deletion
const deleteSubject = () => {
  trash({id: props.subject.id})
      .then((res) => {
            console.log("res: ", res);
            toast.add({
              severity: 'info',
              summary: 'Transaction Deleted',
              detail: `${res.data.name} has been deleted.`,
              life: 3000
            })
            triggerUnselect()
          }
      )
}

// Marking as Inactive
const markSubjectInactive = () => {
  markInactive('transactions', {id: props.subject.id})
      .then(() => {
            toast.add({
              severity: 'info',
              summary: 'Transaction Archived',
              detail: `${props.subject.name} has been marked as inactive`,
              life: 3000
            })
            triggerUnselect()
          }
      )
}

// Closing
const triggerUnselect = () => {
  emit('close')
}
</script>

<template>
  <Toast/>
  <Dialog v-model:visible="visible" @update:visible="triggerUnselect" modal :style="{ width: '25rem' }"
          :draggable="false">
    <template #header>
      <template v-if="status === 'viewing'">
        <span class="p-dialog-title">Transaction Details</span>
        <Button variant="text" severity="secondary" icon="pi pi-folder-open" rounded
                @click="() => status = 'inactivating'"/>
        <Button variant="text" severity="danger" icon="pi pi-trash" rounded
                @click="() => status = 'deleting'"/>
        <Button variant="text" severity="secondary" icon="pi pi-pen-to-square" rounded
                @click="() => status = 'editing'"/>
      </template>
      <template v-if="status === 'deleting'">
        <span class="p-dialog-title text-red-400">Delete Transaction</span>
      </template>
      <template v-if="status === 'inactivating'">
        <span class="p-dialog-title">Mark Inactive</span>
      </template>
      <template v-if="status === 'editing'">
        <div class="flex flex-row gap-2">
          <span class="p-dialog-title">Edit Transaction </span>
          <span class="p-dialog-title font-extralight">{{ subject.id }}</span>
        </div>
      </template>
    </template>
    <div v-if="status === 'viewing'" class="flex flex-col gap-2">
      <LabeledDetail :subject label="Name" property="name"/>
      <LabeledDetail :subject label="Show" property="show.name"/>
      <LabeledDetail :subject label="Zone" property="zone"/>
      <LabeledDetail :subject label="Status" property="active"/>
    </div>
    <div v-if="status === 'deleting'" class="flex flex-col gap-2">
      <p class="text-red-400">Are you sure you want to delete {{ props.subject.name }}?</p>
      <span class="text-red-400">Deletion is meant for mistakes or duplicates, if this transaction has been paid or otherwise concluding, please mark it as</span>
      <span class="font-semibold" @click="() => status = 'inactivating'">inactive.</span>
      <div class="flex justify-end flex-row gap-2">
        <Button label="Cancel" severity="secondary" @click="() => status = 'viewing'"/>
        <Button label="Delete" severity="danger" @click="deleteSubject"/>
      </div>
    </div>
    <div v-if="status === 'inactivating'" class="flex flex-col gap-2">
      <p>Mark {{ props.subject.name }} as inactive?</p>
      <div class="flex justify-end flex-row gap-2">
        <Button label="Cancel" severity="secondary" @click="() => status = 'viewing'"/>
        <Button label="Mark Inactive" severity="primary" @click="markSubjectInactive"/>
      </div>
    </div>
    <div v-if="status === 'editing'" class="flex flex-col gap-2">
      <EditTxnForm :subject="props.subject" @close="() => status = 'viewing'"/>
    </div>
  </Dialog>
</template>