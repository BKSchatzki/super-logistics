<template>
	<router-view></router-view>
</template>

<script>

	export default {
    computed: {
      user() {
        return this.$store.state.user;
      }
    },
		created () {
			if ( '/' === this.$route.path ) {
				this.$router.push(
					{
						name: 'projects'
					}
				);
			}
		}
	}
</script>
