import { createRouter, createWebHashHistory } from 'vue-router'
import QRScanner from '@/components/qr-scanner/QRScanner.vue'
import ReportsMain from '@/components/reports/ReportsMain.vue'
import UserMgmtMain from '@/components/user-management/UserMgmtMain.vue'
import ClientMgmtMain from '@/components/client-management/ClientMgmtMain.vue'
import TransactionsMain from '@/components/transactions/TransactionsMain.vue'
import ShowMgmtMain from "@/components/show-management/ShowMgmtMain.vue";
import ShippingMain from "@/components/shipping/ShippingMain.vue"

const router = createRouter({
  history: createWebHashHistory(localized.pageURL),
  routes: [
    {
      path: '/transactions',
      name: 'transactions',
      component: TransactionsMain,
    },
    {
      path: '/scanner',
      name: 'scanner',
      component: QRScanner,
    },
    {
      path: '/clients',
      name: 'clients',
      component: ClientMgmtMain,
    },
    {
      path: '/reports',
      name: 'reports',
      component: ReportsMain,
    },
    {
      path: '/users',
      name: 'user-management',
      component: UserMgmtMain,
    },
    {
      path: '/shows',
      name: 'shows',
      component: ShowMgmtMain,
    },
    {
      path: '/shipper-labels',
      name: 'shipper-labels',
      component: ShippingMain,
    },
  ],
})

export default router
