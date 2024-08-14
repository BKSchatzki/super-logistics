import ReportsMain from "@components/reports/reports-main.vue";

registerChildRoute('project_root',
    [
        {
            path: 'reports',
            component: ReportsMain,
            name: 'reports',
            meta: {
                label: 'Reports',
                order: 2
            }
        }
    ]
);
