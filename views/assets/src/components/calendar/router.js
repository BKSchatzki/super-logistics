import Calendar from '@components/calendar/calendar.vue';

weDevsPMRegisterChildrenRoute('project_root', 
    [
        { 
            path: '/calendar',
            component: Calendar,
            name: 'calendar',
        },
    ]
);
