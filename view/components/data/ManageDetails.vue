<script setup>
import {computed, ref} from 'vue';
import {useAPI} from "@utils/composables/useAPI.js";
import {toCapitalCase, toSingular} from '@utils/helpers.js';
import Col from '@/components/form/Col.vue';

// <editor-fold desc="Data">-------------------------------------

const props = defineProps({
  subject: Object,
  topic: {
    type: String,
    required: true
  },
  header: {
    type: String,
    default: null
  },
  allowEdit: {
    type: Boolean,
    default: true
  },
  allowTrash: {
    type: Boolean,
    default: true
  },
  allowArchive: {
    type: Boolean,
    default: true
  },
  nameIDKey: {
    type: String,
    default: 'name'
  }
})
const title = computed(() => {
  return props.header ?? `${toCapitalCase(toSingular(props.topic))} Details`
})
const niceSingular = computed(() => {
  return toCapitalCase(toSingular(props.topic));
})
const subjectStatus = computed(() => {
  if (props.subject.trashed) {
    return 'Deleted '
  }
  if (!props.subject.active) {
    return 'Archived '
  }
  return '';
});

// </editor-fold> ---------------------------------------------------------

// <editor-fold desc="Modal Visibility" > ---------------------------------

const visible = computed(() => {
  return props.subject !== null
})
const emit = defineEmits(['close'])
const triggerUnselect = () => {
  emit('close', null)
}

// </editor-fold> ---------------------------------------------------------

// <editor-fold desc="Actions">--------------------------------------------

const {trash, restore, markInactive, markActive} = useAPI(props.topic)
const status = ref('viewing')
const verb = computed(() => {
  switch (status.value) {
    case 'editing':
      return 'Edit'
    case 'archiving':
      return 'Archive'
    case 'activating':
      return 'Activate'
    default:
      return 'View'
  }
})

// Deletion
const deleteSubject = () => {
  trash({id: props.subject.id})
      .then(triggerUnselect)
}
const restoreSubject = () => {
  restore({id: props.subject.id})
      .then(triggerUnselect)
}

// Marking as Inactive
const markSubjectInactive = () => {
  markInactive({id: props.subject.id})
      .then(triggerUnselect)
}
const markSubjectActive = () => {
  markActive({id: props.subject.id})
      .then(triggerUnselect)
}

// </editor-fold> ---------------------------------------------------------

</script>

<template>
  <Toast/>
  <Dialog v-model:visible="visible" @update:visible="triggerUnselect" modal
          contentClass="w-[400]px min-h-60" :draggable="false">
    <template #header>
      <template v-if="status === 'viewing'">
        <span class="p-dialog-title">{{ title }}</span>
        <Button v-if="allowArchive && !!subject['active'] && !subject['trashed']" variant="text" severity="secondary" icon="pi pi-folder"
                rounded @click="() => status = 'archiving'"/>
        <Button v-if="allowEdit && !!subject['active'] && !subject['trashed']" variant="text" severity="secondary" icon="pi pi-pencil"
                rounded @click="() => status = 'editing'"/>
        <Button v-if="allowArchive && !subject['active']" variant="text" severity="secondary"
                icon="pi pi-folder-open" rounded @click="() => status = 'activating'"/>
        <Button v-if="allowTrash && !subject['trashed']" variant="text" severity="danger" icon="pi pi-trash"
                rounded @click="() => status = 'deleting'"/>
        <Button v-if="allowTrash && !!subject['trashed']" variant="text" severity="success" icon="pi pi-undo"
                rounded @click="() => status = 'restoring'"/>
      </template>
      <template v-if="status === 'deleting'">
        <span class="p-dialog-title text-red-400">Delete {{ niceSingular }}</span>
      </template>
      <template v-else-if="status !== 'viewing'">
        <div class="flex flex-row gap-2">
          <span class="p-dialog-title">{{ verb }} {{ niceSingular }} </span>
          <span class="p-dialog-title font-extralight">{{ props.subject.user_login }}</span>
        </div>
      </template>
    </template>
    <div v-if="status === 'viewing'" class="flex flex-col gap-2">
      <slot name="detail-body" :subject="props.subject"/>
    </div>
    <div v-if="status === 'deleting'" class="flex flex-col gap-2">
      <Col>
        <p class="text-red-400">Are you sure you want to delete {{ props.subject[nameIDKey] }}?</p>
        <div class="flex justify-end flex-row gap-2">
          <Button label="Cancel" severity="secondary" @click="() => status = 'viewing'"/>
          <Button label="Delete" severity="danger" @click="deleteSubject"/>
        </div>
      </Col>
    </div>
    <div v-if="status === 'restoring'">
      <Col>
        <p>Are you sure you want to restore {{ props.subject[nameIDKey] }}?</p>
        <div class="flex justify-end flex-row gap-2">
          <Button label="Cancel" severity="secondary" @click="() => status = 'viewing'"/>
          <Button label="Restore" severity="primary" @click="restoreSubject"/>
        </div>
      </Col>
    </div>
    <div v-if="status === 'archiving'" class="flex flex-col gap-2">
      <Col>
        <p>Mark {{ props.subject[nameIDKey] }} as inactive?</p>
        <div class="flex justify-end flex-row gap-2">
          <Button label="Cancel" severity="secondary" @click="() => status = 'viewing'"/>
          <Button label="Mark Inactive" severity="primary" @click="markSubjectInactive"/>
        </div>
      </Col>
    </div>
    <div v-if="status === 'activating'">
      <Col>
        <p>Mark {{ props.subject[nameIDKey] }} as active again?</p>
        <div class="flex justify-end flex-row gap-2">
          <Button label="Cancel" severity="secondary" @click="() => status = 'viewing'"/>
          <Button label="Mark Active" severity="primary" @click="markSubjectActive"/>
        </div>
      </Col>
    </div>
    <div v-if="status === 'editing'" class="flex flex-col gap-2">
      <slot name="edit-form" :formData="props.subject" :close="() => status = 'viewing'"/>
    </div>
  </Dialog>
</template>