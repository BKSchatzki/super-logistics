<script setup>
import CheckInput from "@/components/form/CheckInput.vue";

const props = defineProps({
  checkBoxes: {
    type: Object,
    required: true
  }
})

</script>

<template>
  <CheckInput v-model="checkBoxes.active" label="Active"/>
  <CheckInput v-model="checkBoxes.inactive" label="Inactive"/>
  <CheckInput v-model="checkBoxes.deleted" label="Deleted"/>
  <div class="flex items-center">
    <span v-if="checkBoxes.none" class="text-gray-300 font-extralight">No Filters Applied</span>
  </div>
</template>

<style scoped>

</style>