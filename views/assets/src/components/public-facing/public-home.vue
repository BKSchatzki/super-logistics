<script>
import GenerateQR from '@components/public-facing/generate-qr.vue';
import NavBar from "@components/frontend/navbar.vue";
export default {
  components: {NavBar, GenerateQR }
}
</script>

<template>
  <div class="sl-main">
    <GenerateQR />
  </div>
</template>

<style scoped lang="less">
.sl-interface {
  width: 100%;
  margin: 0 auto;
}
.sli-header {
  font-family: 'Roboto', sans-serif;
  padding: 10px;
  border-radius: 20px;
  background: #ffffff;
  box-shadow:  -5px 5px 25px #b8b8b8,
    5px -5px 10px #ffffff;
  margin-bottom: 25px;
}
.sl-main {
  font-family: 'Roboto', sans-serif;
  padding: 25px;
  border-radius: 20px;
  background: #ffffff;
  box-shadow:  -5px 5px 25px #b8b8b8,
    5px -5px 10px #ffffff;
  margin-bottom: 25px;
}
</style>
