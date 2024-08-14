<script>

export default {
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  computed: {
    routes: function() {
      return getRegisteredChildRoutes('project_root')
    }
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
        <ul v-if="user.roles" class="navbar-nav me-auto mb-2 mb-lg-0">
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
          <li v-if="!user.roles.includes('subscriber')" key="reports" class="nav-item">
            <router-link :to="{ name: 'reports' }" class="nav-link">
              Reports
            </router-link>
          </li>
          <li v-if="user.roles.includes('subscriber')" key="client" class="nav-item">
            <router-link :to="{ name: 'client' }" class="nav-link">
              Client Home
            </router-link>
          </li>
          <li key="settings" class="nav-item">
            <router-link :to="{ name: 'settings' }" class="nav-link">
              Settings
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
