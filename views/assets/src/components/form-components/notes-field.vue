<script>
export default {
  props: {
    value: {
      type: String,
      default: "",
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    noteValue: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.$emit('input', newValue);
      }
    }
  }
}
</script>

<template>
  <div v-if="readOnly">
    <label for="note">Most Recent Note</label>
    <p class="form-control textytext">{{ noteValue }}</p>
  </div>
  <div v-else>
    <label for="note">Note</label>
    <textarea v-model="noteValue" class="form-control textytext"></textarea>
  </div>
</template>

<style scoped lang="less">
.textytext {
  height: 90%;
  resize: none;
}
div {
  width: 100%;
  height: 100%
}
</style>
