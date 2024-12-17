<script>
import UserManagementMixin from '@components/user-management/mixin';

export default {
  mixins: [ UserManagementMixin ],
  computed: {
    user() {
      return this.$store.state.user;
    },
    otherUsers() {
      return this.$store.state.users;
    }
  },
  methods: {
    fetchUsers() {
      if (!this.user.roles) {
        console.log("getting current user");
        this.getCurrentUser();
        return
      }
      if (!this.user.roles.includes("internal_admin")) {
        console.log("getting all app users");
        this.getAllAppUsers();
      }
      if (this.users.roles.includes("client_admin")) {
        console.log("getting client app users");
        this.getClientAppUsers();
      }
    }
  },
  watch: {
    user() {
      this.fetchUsers();
    },
    otherUsers() {
      console.log("otherUsers", this.otherUsers);
    }
  },
  created() {
    this.fetchUsers();
  }
}
</script>

<template>
  <div class="container">
    <h1>User Management</h1>
  </div>
</template>

<style scoped>

</style>
