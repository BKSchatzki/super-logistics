<script>
import noteIndividual from "@/components/form-components/note-individual.vue";
import DataMixin from "@/components/data-input/mixin.js";
import U from "@utils/UserUtility";

export default {
  components: {
    noteIndividual
  },
  mixins: [DataMixin],
  props: {
    updates: {
      type: Array,
      default: () => [],
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
    transactionId: {
      type: Number,
      default: 0,
    },
    value: {
      type: String,
      default: '',
    }
  },
  data() {
    return {
      addingNote: false,
    };
  },
  computed: {
    noteValue: {
      get() {
        return this.value;
      },
      set(newValue) {
        this.$emit('input', newValue);
      }
    },
    subscriber() {
      const user = this.$store.state.user;
      return user.roles ? user.roles.includes("subscriber") : false;
    },
    pHeight() {
      return this.subscriber ? '170px' : '200px';
    },
    notes() {
      return this.updates;
    }
  },
  methods: {
    postNote() {
      this.addNote(this.noteValue, this.transactionId);
      this.addingNote = false;
      this.$emit('input', '');
    },
    toggleNote() {
      this.addingNote = !this.addingNote;
    }
  },
  created() {
    U.getCurrentUser();
  }
}
</script>

<template>
  <div id="note-field">
    <label for="note">Notes</label>
    <div v-if="readOnly && !addingNote" class="border rounded mb-1 p-1 overflow-auto" :style="{ height: pHeight }">
      <note-individual v-for="update in updates" :key="`update-${update.id}`" :update="update" :delete="!subscriber"/>
    </div>
    <textarea v-else v-model="noteValue" class="form-control mb-1 auto-height" :rows="subscriber ? 13 : 15" style="resize: none"></textarea>
    <div v-if="subscriber">
      <button class="btn btn-sm btn-primary me-1" @click="addingNote ? postNote() : toggleNote()">
        {{ addingNote ? 'Post Note' : 'Add Note' }}
      </button>
      <button v-if="addingNote" class="btn btn-sm btn-secondary" @click="toggleNote">
        Cancel
      </button>
    </div>
  </div>
</template>

<style>
#note-field {
  height: 100%;
  width: 100%;
}
.auto-height {
  height: auto !important;
}
</style>
