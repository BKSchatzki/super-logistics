import GlobalKanban from '@components/global-kanban/global-kanban.vue'

weDevsPMRegisterChildrenRoute('project_root',
    [
        {
            path: '/kanban',
            component: GlobalKanban,
            name: 'kanban',
        }
    ]
);
