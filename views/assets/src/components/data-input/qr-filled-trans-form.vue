<script>

import TransDetails from "@components/lookup/trans-details.vue";
import LookupMixin from "@components/lookup/mixin";
import DataMixin from "@components/data-input/mixin";
import TransactionSection from "@components/data-input/transaction-section.vue";
import ItemsSection from "@components/data-input/items-section.vue";
import TransactionForm from "@components/data-input/transaction-form.vue";

export default {
  props: {
    parentOpen: {
      type: Boolean,
      default: null
    },
    trans: {
      type: Object,
      required: true
    }
  },
  components: {
    TransactionForm,
    ItemsSection,
    TransactionSection,
    TransDetails
  },
  mixins: [LookupMixin, DataMixin],
  data() {
    return {
      localOpen: true,
      updating: false,
      notesOpen: false,
    };
  },
  computed: {
    pdfurl() {
      return this.$store.state.loadedPDF;
    },
    computedOpen() {
      return this.parentOpen !== null ? this.parentOpen : this.localOpen;
    },
  },
  inject: ['admin'],
  methods: {
    toggleForm() {
      if (this.parentOpen !== null) {
        this.$emit('closeView');
      } else {
        this.localOpen = !this.localOpen;
      }
    },
  },
  watch: {
    parentOpen(newVal) {
      if (newVal !== null) {
        this.localOpen = newVal;
      }
    },
    pdfurl() {
      if (this.pdfurl !== '') {
        this.downloadPdf(this.pdfurl)
      }
    }
  },
  created() {
    if (this.parentOpen !== null) {
      this.localOpen = this.parentOpen;
    }
    this.getUsers();
  }
};
</script>

<template>
  <div class="btb-modal container-fluid" v-if="computedOpen" @click="toggleForm" tabindex="-1" :aria-labelledby="`View Selected transaction`">
    <div class="btb-modal-content container" @click.stop>
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4>New Transaction</h4>
        <button @click="toggleForm" type="button" class="btn btn-close" aria-label="Close"></button>
      </div>
      <div>
        <transaction-form :init-trans="trans" :read-only="false" @submit="toggleForm"/>
      </div>
    </div>
  </div>
</template>

<style scoped lang="less">
.btb-modal {
  position: fixed;
  z-index: 999999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
  display: flex;
  justify-content: center;
  align-items: center;
}

.btb-modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50rem;
}
</style>
