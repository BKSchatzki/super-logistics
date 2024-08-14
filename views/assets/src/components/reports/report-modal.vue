<script>
export default {
  props: {
    title: {
      type: String,
      required: true
    },
    viewFunc: {
      type: Function,
      required: true
    }
  }
}
</script>

<template>
  <div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trailerManModal">
      Trailer Manifest
    </button>
    <div class="modal fade" id="trailerManModal" tabindex="-1" aria-labelledby="trailerManModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="trailerManModalLabel">{{ title }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <slot/>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
            <button @click="viewFunc" type="button" class="btn btn-primary">View Report</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
