<script>
import GKMixins from "@components/global-kanban/mixin";
export default {
  name: "AddNewColumn",
  mixins: [GKMixins],
  methods: {
    addColumn() {
      console.log(this.title);
      this.addBoard(this.title);
      this.title = "";
    }
  },
  data() {
    return {
      title: "",
    }
  }
}
</script>

<template>
  <div class="kbc-th kbc-th-new-section kbc-non-sortable ui-sortable-handle">
    <div class="kbc-section-header">
      <input type="text" placeholder="Add new section" required="required" @keyup.enter="addColumn" v-model="title">
    </div>
  </div>
</template>

<style scoped lang="less">

</style>
