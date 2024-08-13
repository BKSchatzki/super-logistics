import Settings from "@components/settings/settings.vue"

registerChildRoute('project_root', 
    [
        { 
            path: 'settings',
            name: 'settings',
            component: Settings,
        }

    ]
);




