import LabelsHome from '@components/labels/labels.vue'

registerChildRoute('project_root',
    [
        {
            path: 'labels',
            component: LabelsHome,
            name: 'labels',
            meta: {
              label: 'Labels',
              order: 3
            }
        }
    ]
);
