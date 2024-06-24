import ClientHome from '@components/client/client-home.vue'

registerChildRoute('project_root',
    [
        {
            path: 'client',
            component: ClientHome,
            name: 'client',
            meta: {
              label: 'Client Home',
              order: 1
            }
        }
    ]
);
