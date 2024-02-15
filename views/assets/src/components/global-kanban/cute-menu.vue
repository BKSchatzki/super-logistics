<script>
export default {
  name: "cute-menu"
}
</script>

<template>
  <div class="gk-cute-menu">
    <slot/>
  </div>
</template>

<style scoped lang="less">
.gk-cute-menu {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0px;
  width: 40px;
  overflow: hidden;
}
</style>
