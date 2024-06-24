<script>

export default {
  computed: {
    routes: function() {
      return getRegisteredChildRoutes('project_root')
    },
    user() {
      return this.$store.state.user ? this.$store.state.user : {roles: []};
    }
  },
  created() {
    this.getCurrentUserRoles();
  }
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Super Logistics</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li v-if="!user.roles.includes('subscriber')" key="input" class="nav-item">
            <router-link :to="{ name: 'input' }" class="nav-link">
              Transactions
            </router-link>
          </li>
          <li v-if="!user.roles.includes('subscriber')" key="scanner" class="nav-item">
            <router-link :to="{ name: 'scanner' }" class="nav-link">
              Scanner
            </router-link>
          </li>
          <li v-if="user.roles.includes('subscriber')" key="client" class="nav-item">
            <router-link :to="{ name: 'client' }" class="nav-link">
              Client Home
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
