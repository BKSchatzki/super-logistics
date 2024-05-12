<script>

export default {
  props: {
    value: {
      type: String,
      default: "",
    },
  },

  computed: {
    previewSrc: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    },
  },
  watch: {
    value: {
      immediate: true,
      handler(value) {
        this.$emit("input", value);
        this.previewSrc = value;
      },
    },
  }
}
</script>

<template>
  <div class="sl-image-field">
    <div class="sl-image-preview" id="sl-image-preview">
      <img v-if="previewSrc !== ''" :src="previewSrc" alt="Most recently view of transaction">
    </div>
    <input type="file" id="fileElem" accept="image/*" multiple>
  </div>
</template>

<style scoped lang="less">
.sl-image-preview {
  min-width: 100px;
  min-height: 100px;
  max-width: 300px;
  max-height: 300px;
  border: 1px solid #ccc;
  border-radius: 5px;
  overflow: hidden;
  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}
</style>
