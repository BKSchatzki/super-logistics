import { createRouter, createWebHashHistory } from 'vue-router'
import DataInput from '@/components/data-input/data-input.vue'
import QRScanner from '@/components/qr-scanner/QRScanner.vue'
import ReportsMain from '@/components/reports/ReportsMain.vue'
import UserMgmtMain from '@/components/user-management/UserMgmtMain.vue'
import ClientMgmtMain from '@/components/client-management/ClientMgmtMain.vue'
import Settings from '@/components/settings/Settings.vue'
import PublicHome from '@/components/public-facing/PublicHome.vue'
import TransactionsMain from '@/components/transactions/TransactionsMain.vue'

const router = createRouter({
  history: createWebHashHistory(localized.pageURL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: DataInput,
    },
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
      path: '/settings',
      name: 'settings',
      component: Settings,
    },
    {
      path: '/public',
      name: 'public-home',
      component: PublicHome,
    },
  ],
})

export default router
