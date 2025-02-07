<script setup>
import { ref } from 'vue'

const props = defineProps({
  buttonLabel: String,
  header: String,
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
    <slot name="form" :close="close"/>
  </Dialog>
</template>