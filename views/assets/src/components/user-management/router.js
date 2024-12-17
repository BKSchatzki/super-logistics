import UserManagementScreen from "@components/user-management/user-management.vue"

registerChildRoute('project_root',
    [
        {
            path: 'user-management',
            name: 'user-management',
            component: UserManagementScreen,
        }

    ]
);




