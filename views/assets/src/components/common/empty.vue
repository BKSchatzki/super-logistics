<template>
	<router-view></router-view>
</template>

<script>
	export default {
    watch : {
      user() {
        if (this.user.roles && !this.user.roles.includes('subscriber')) {
          this.$router.push('/input')
        } else {
          this.$router.push('/client')
        }
      }
    }
	}
</script>
