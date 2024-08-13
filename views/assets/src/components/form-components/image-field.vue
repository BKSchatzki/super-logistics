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
  data() {
    return {
      previewSrc: this.value, // Initialize previewSrc with the prop value
    };
  },
  methods: {
    getImageFile(event) {
      const file = event.target.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.previewSrc = e.target.result;
          this.$emit("input", event.target.files[0]); // Emit the new value to update the prop
        };
        reader.readAsDataURL(file);
      } else {
        alert('Please select a valid image file.');
      }
    },
  },
  watch: {
    value() {
      if (!this.value) {
        this.previewSrc = '';
      }
    }
  }
};
</script>

<template>
  <div class="sl-image-field">
    <p class="mb-0">{{ readOnly ? 'Most Recent Image' : 'Image'}}</p>
    <div class="sl-image-preview rouded border" id="sl-image-preview">
      <img v-if="previewSrc !== ''" :src="previewSrc" alt="Most recent view of transaction">
    </div>
    <input v-if="!readOnly" type="file" id="fileElem" accept="image/*" v-on:change="getImageFile">
  </div>
</template>

<style scoped lang="less">
.sl-image-preview {
  width: 100%;
  height: 200px;
  display: flex;
  overflow: hidden;
}
img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
