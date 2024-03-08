import MaterialOrdering from '@components/material-ordering/material-ordering.vue'

weDevsPMRegisterChildrenRoute('project_root',
    [
        {
            path: '/materials',
            component: MaterialOrdering,
            name: 'materials',
        }
    ]
);
