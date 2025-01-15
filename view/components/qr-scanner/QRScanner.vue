<script>
import { QrcodeStream } from 'vue-qrcode-reader';


export default {
  data() {
    return {
      showScanner: true,
      openForm: false,
      decodedContent: null
    };
  },
  provide() {
    return {
      admin: true,
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
      if (data.trans_id) {
        this.sendToBackend(data);
      } else {
        this.openPopulatedForm();
      }
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
    },
    openPopulatedForm() {
      this.openForm = true;
    },
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
    <qr-filled-trans-form v-if="openForm && decodedContent" :trans="decodedContent"/>
  </div>
</template>

<style>
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
