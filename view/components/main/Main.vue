<script setup>
import {watchEffect} from "vue";
import NavBar from '@/components/main/NavBar.vue';

import {useUserAPI} from "@utils/composables/useUserAPI.js";

const {getCurrentUser} = useUserAPI();

const user = getCurrentUser();
watchEffect(() => {
  console.log("Current User: ", user);
}, {immediate: true})
</script>

<template>
  <div class="sl-interface">
    <NavBar class="sli-header m-6" :user="user"/>
    <div class="m-8">
      <router-view/>
    </div>
  </div>
</template>

<style>

.sl-interface {
  width: 100%;
  margin: 0 auto;
}

</style>
