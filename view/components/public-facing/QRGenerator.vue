<script>

export default {
  computed: {
    pdfurl() {
      return this.$store.state.loadedPDF
    }
  },
  watch: {
    pdfurl() {
      if (this.pdfurl !== '') {
        this.downloadPdf(this.pdfurl)
      }
    }
  }
}

</script>

<template>
  <TransactionForm qr-gen/>
</template>
