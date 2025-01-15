<script setup>
import {computed, defineProps, ref, watch} from "vue";
import {useRouter} from "vue-router";
import {useUserAPI} from "@utils/useUserAPI.js";

const router = useRouter();
const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})
const {logOut} = useUserAPI();

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
    icon: 'pi pi-qrcode',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Reports',
    route: 'reports',
    icon: 'pi pi-file',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Clients',
    route: 'clients',
    icon: 'pi pi-address-book',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Users',
    route: 'users',
    icon: 'pi pi-users',
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
    permissions: []
  },
  {
    label: 'Log In',
    url: localized.loginURL,
    icon: 'pi pi-home',
    permissions: []
  },
  {
    label: 'Log Out',
    action: logOut,
    icon: 'pi pi-home',
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  }
]);
const filteredItems = computed(() => {
  return items.value.filter(item => isAllowed(props.user, item['permissions']));
});

const isAllowed = (user, permissions) => {
  if (props.user.role === 'administrator') {
    return true;
  }

  return !props.user.role ? permissions.length === 0 : permissions.includes(props.user.role);
};
watch(props.user, (updatedUser) => {
  if (!updatedUser.role) {
    router.push({name: 'public-home'});
    return permissions.length === 0;
  }
})

const logIn = () => {
  window.location.href = localized.loginURL;
}

</script>

<template>
  <Menubar :model="filteredItems">
    <template #item="{ item, props }">
      <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
        <a :href="href" v-bind="props.action" @click="navigate">
          {{ item.label }}
        </a>
      </router-link>
    </template>
    <template #end>
      <Button v-if="!props.user.id" severity="primary" icon="pi pi-sign-in" @click="logIn" label="Log In"></Button>
      <Button v-else severity="secondary" icon="pi pi-sign-out" @click="logOut" label="Log Out"></Button>
    </template>
  </Menubar>
</template>
