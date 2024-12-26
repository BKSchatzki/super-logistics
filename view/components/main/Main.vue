<script>
import NavBar from '@/components/main/NavBar.vue';
import U from '@utils/UserUtility.js';

export default {
  components: {
    NavBar,
  },
  data() {
    return {
      initialLoad: true
    }
  },
  computed: {
    user() {
      return this.$store.state.user
    }
  },
  created() {
    U.getCurrentUser();
  }
};
</script>

<template>
  <div class="sl-interface">
    <NavBar class="sli-header m-6" :user="user"/>
    <Panel class="sl-main m-6">
      <router-view/>
    </Panel>
  </div>
</template>

<style>
.sl-interface {
  width: 100%;
  margin: 0 auto;
}

.sli-header {
  box-shadow: -5px 5px 25px #b8b8b8,
    5px -5px 10px #ffffff;
}

.sl-main {
  box-shadow: -5px 5px 25px #b8b8b8,
    5px -5px 10px #ffffff;
}
</style>
