<script setup>
import {ref, computed} from "vue";
import {useRouter} from 'vue-router';

const router = useRouter();

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

const items = ref([
  {
    label: 'Home',
    route: 'home',
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Transactions',
    route: 'transactions',
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Scanner',
    route: 'scanner',
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Reports',
    route: 'reports',
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Clients',
    route: 'clients',
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Users',
    route: 'users',
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Shows',
    route: 'shows',
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Public Home',
    route: 'public-home',
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  }
]);
const filteredItems = computed(() => {
  return items.value.filter(item => isAllowed(props.user, item.permissions));
});

const isAllowed = (user, permissions) => {
  return permissions.some(permission => user.roles ? user.roles.includes(permission) : false);
};

</script>

<template>
  <Menubar :model="filteredItems">
    <template #item="{ item, props }">
      <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
        <a v-ripple :href="href" v-bind="props.action" @click="navigate">
          <span :class="item.icon"/>
          <span>{{ item.label }}</span>
        </a>
      </router-link>
      <a v-else v-ripple :href="item.url" :target="item.target" v-bind="props.action">
        <span :class="item.icon"/>
        <span>{{ item.label }}</span>
      </a>
    </template>
  </Menubar>
<!--  <a class="navbar-brand" href="#">Super Logistics</a>-->
<!--  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"-->
<!--          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">-->
<!--    <span class="navbar-toggler-icon"></span>-->
<!--  </button>-->
<!--  <div class="collapse navbar-collapse" id="navbarNav">-->
<!--    <ul v-if="user.roles" class="navbar-nav me-auto mb-2 mb-lg-0">-->
<!--      <li v-if="!user.roles.includes('subscriber')" key="input" class="nav-item">-->
<!--        <RouterLink :to="{ name: 'home' }" class="nav-link">-->
<!--          Transactions-->
<!--        </RouterLink>-->
<!--      </li>-->
<!--      <li v-if="!user.roles.includes('subscriber')" key="scanner" class="nav-item">-->
<!--        <RouterLink :to="{ name: 'scanner' }" class="nav-link">-->
<!--          Scanner-->
<!--        </RouterLink>-->
<!--      </li>-->
<!--      <li v-if="!user.roles.includes('subscriber')" key="reports" class="nav-item">-->
<!--        <RouterLink :to="{ name: 'reports' }" class="nav-link">-->
<!--          Reports-->
<!--        </RouterLink>-->
<!--      </li>-->
<!--      <li v-if="user.roles.includes('subscriber')" key="client" class="nav-item">-->
<!--        <RouterLink :to="{ name: 'client' }" class="nav-link">-->
<!--          Client Home-->
<!--        </RouterLink>-->
<!--      </li>-->
<!--      <li key="settings" class="nav-item">-->
<!--        <RouterLink :to="{ name: 'settings' }" class="nav-link">-->
<!--          Settings-->
<!--        </RouterLink>-->
<!--      </li>-->
<!--      <li key="user-management" class="nav-item">-->
<!--        <RouterLink :to="{ name: 'user-management' }" class="nav-link">-->
<!--          User Management-->
<!--        </RouterLink>-->
<!--      </li>-->
<!--      <li key="public-home" class="nav-item">-->
<!--        <RouterLink :to="{ name: 'public-home' }" class="nav-link">-->
<!--          User Management-->
<!--        </RouterLink>-->
<!--      </li>-->
<!--    </ul>-->
<!--  </div>-->
<!--  </div>-->
<!--  </nav>-->
</template>
