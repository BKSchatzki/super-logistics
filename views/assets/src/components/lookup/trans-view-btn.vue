<script>
import TransView from "@components/lookup/trans-view.vue";

export default {
  components: {TransView},
  props: {
    trans: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      open: false
    }
  },
  methods: {
    toggleOpen() {
      this.open = !this.open;
    }
  }
}
</script>

<template>
<div>
  <trans-view found="foundTrans" :trans="trans" :parent-open="open" @closeView="toggleOpen"/>
  <button type="button" class="btn btn-sm btn-primary" @click="toggleOpen">View</button>
</div>
</template>

<style scoped lang="less">

</style>
