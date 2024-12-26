<script>
import SimpleField from "@/components/form-components/simple-field.vue"

export default {
  name: 'multi-create-field',
  components: {SimpleField},
  props: {
    field: {
      type: String,
      required: true
    },
    addPlace: {
      type: Function,
      required: true
    },
    removePlace: {
      type: Function,
      required: true
    },
    places: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      newPlace: ''
    }
  },
  methods: {
    add() {
      this.addPlace(this.newPlace)
      this.newPlace = ''
      document.querySelector(`#${this.field}-field`).focus();
    }
  }
}
</script>

<template>
  <div class="col">
    <div class="d-flex align-items-center">
      <simple-field type="text" v-model="newPlace" :field="field"/>
      <button type="button" class="btn btn-sm btn-secondary ms-2" @click.prevent="add">Add</button>
    </div>
    <button type="button"
            v-for="place in places"
            class="btn btn-sm btn-outline-secondary mt-2 me-2"
            @click.once.prevent="() => removePlace(place.name)">
      {{ place.name }}
    </button>
  </div>
</template>

<style>

</style>
