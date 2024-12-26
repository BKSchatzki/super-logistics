<script>
import LookupMixin from "@/components/lookup/mixin.js";

export default {
  props: {
    update: {
      type: Object,
      required: true
    }
  },
  mixins: [LookupMixin],
  computed: {
    users() {
      return this.$store.state.users;
    },
    username() {
      const author = this.users.find(user => user.id === parseInt(this.update.user_id));
      return author ? author.display_name : 'getting user';
    },
  },
methods: {
    removeNote() {
      this.$emit('remove-note', this.update.id);
    }
  }
}
</script>
<template>
<div v-if="update.note && update.note.trim() !== ''" class="border rounded p-1 mb-1">
  <b>{{ username }}: </b>
<!--  <button @click="removeNote" type="button" class="btn-close" aria-label="Close"></button>-->
  <p class="mb-0">{{ update.note }}</p>
</div>
</template>
