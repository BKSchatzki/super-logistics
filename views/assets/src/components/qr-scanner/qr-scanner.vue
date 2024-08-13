<script>
import { QrcodeStream } from 'vue-qrcode-reader';
import LookupMixin from '@components/lookup/mixin';
import ViewTransaction from "@components/lookup/trans-view.vue";
import TransView from "@components/lookup/trans-view.vue";

export default {
  components: {
    TransView,
    ViewTransaction,
    QrcodeStream
  },
  mixins: [LookupMixin],
  data() {
    return {
      showScanner: true,
      decodedContent: null
    };
  },
  provide() {
    return {
      admin: true
    }
  },
  computed : {
    foundTrans() {
      return this.$store.state.transaction;
    }
  },
  methods: {
    onDecode(content) {
      const data = JSON.parse(content);
      this.decodedContent = data;
      this.showScanner = false;
      this.sendToBackend(data);
    },
    resetScanner() {
      this.decodedContent = null;
      this.showScanner = true;
      this.$store.commit('setTransaction', {});
    },
    sendToBackend(content) {
      this.getTransaction(content.trans_id);
    },
    isTheRightTrans(trans) {
      if (trans.id && parseInt(trans.id) === parseInt(this.decodedContent.trans_id)) {
        return true;
      }
      return false;
    }
  }
};
</script>

<template>
  <div>
    <qrcode-stream v-if="showScanner" @decode="onDecode"></qrcode-stream>
    <div v-if="decodedContent">
      <button class="btn btn-lg btn-primary" @click="resetScanner">Scan Again</button>
    </div>
    <trans-view v-if="isTheRightTrans(foundTrans)" :trans="foundTrans" />
  </div>
</template>

<style scoped lang="less">

</style>
