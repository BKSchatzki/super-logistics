<script setup>
import { ref } from 'vue'

const props = defineProps({
  buttonLabel: String,
  header: String,
  description: String,
  width: String
})

const visible = ref(false)

const close = () => {
  visible.value = false
}

</script>

<template>
  <Button class="mb-4 right-0" :label="buttonLabel" @click="visible = true"/>
  <Dialog v-model:visible="visible" modal :header :style="{ width: `${width ?? 25}rem` }">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">
      {{ description }}
    </span>
    <slot name="form" :close="close"/>
  </Dialog>
</template>