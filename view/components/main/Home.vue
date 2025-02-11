<script setup>
import {computed, ref} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {useUserAPI} from "@utils/composables/useUserAPI";
import shippingImage from "@/assets/home-buttons/shipping.png";
import receiverImage from "@/assets/home-buttons/receiver.png";
import scannerImage from "@/assets/home-buttons/scanner.png";
import clientImage from "@/assets/home-buttons/clients.png";
import reportImage from "@/assets/home-buttons/reports.png";
import showImage from "@/assets/home-buttons/shows.png";
import userImage from "@/assets/home-buttons/users.png";

const router = useRouter();
const store = useStore();
const user = computed(() => store.state.user);
const {logOut} = useUserAPI();

const items = ref([
  {
    label: 'Receivers',
    route: 'transactions',
    image: receiverImage,
    icon: 'pi pi-home',
    description: "View the active receivers relevant to your account. Packages received are accountable here.",
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Scanner',
    route: 'scanner',
    image: scannerImage,
    icon: 'pi pi-qrcode',
    description: "Scan new packages at the dock to add them to the system and produce labels and receiver docs.",
    permissions: ['internal_admin', 'internal_employee']
  },
  {
    label: 'Reports',
    route: 'reports',
    image: reportImage,
    icon: 'pi pi-file',
    description: "Print reports to examine logistics in further detail.",
    permissions: ['administrator', 'client_admin', 'client_employee', 'internal_admin']
  },
  {
    label: 'Clients',
    route: 'clients',
    image: clientImage,
    icon: 'pi pi-address-book',
    description: "View and manage clients. Use Users page to manage accounts.",
    permissions: ['internal_admin']
  },
  {
    label: 'Users',
    route: 'users',
    image: userImage,
    icon: 'pi pi-users',
    description: "View and manage users, roles, as well as client and show assignment.",
    permissions: ['client_admin', 'internal_admin']
  },
  {
    label: 'Shows',
    route: 'shows',
    image: showImage,
    icon: 'pi pi-home',
    description: "Manage and edit show configurations.",
    permissions: ['client_admin', 'client_employee', 'internal_admin', 'internal_employee']
  },
  {
    label: 'Shipping Labels',
    route: 'shipper-labels',
    image: shippingImage,
    icon: 'pi pi-home',
    description: "Generate shipping labels to send to the advance warehouse.",
    permissions: ['internal_admin', 'client_admin', 'client_employee', 'public']
  }
]);

const isAllowed = (user, permissions) => {

  console.log("User: ", user);
  console.log("Permissions: ", permissions);

  if (!user.role && permissions.includes('public')) {
    return true;
  }
  if (user.role === 'administrator') {
    return true;
  }

  return !user.role ? permissions.length === 0 : permissions.includes(user.role);
};

const filteredItems = computed(() => {
  return items.value.filter(item => isAllowed(user.value, item['permissions']));
});

</script>

<template>
  <Panel>
    <div class="flex flex-wrap gap-4">
      <Card v-for="item in filteredItems"
            style="overflow: hidden"
            class="hover:shadow-lg hover:text-primary-500 cursor-pointer w-full sm:w-64 transition duration-500"
            :key="item.label"
            :onClick="() => router.push({name: item.route})">
        <template #header>
          <img :src="item.image" :alt="item.label">
        </template>
        <template #title>
          {{ item.label }}
        </template>
        <template #content>
          <span>{{ item.description }}</span>
        </template>
      </Card>
    </div>
  </Panel>
</template>

<style scoped>

</style>