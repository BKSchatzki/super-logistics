import DataInput from '@components/data-input/data-input.vue'

registerChildRoute('project_root',
    [
        {
            path: 'input',
            component: DataInput,
            name: 'input',
            meta: {
              label: 'Data Input/Entry',
              order: 1
            }
        }
    ]
);
