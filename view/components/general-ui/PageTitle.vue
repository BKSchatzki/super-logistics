<script setup>
const props = defineProps({
  title: String
});
</script>

<template>
  <h1 class="text-3xl font-sans font-semibold text-primary-500">{{ title }}</h1>
</template>