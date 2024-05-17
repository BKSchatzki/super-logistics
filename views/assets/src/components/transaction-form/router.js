import TransactionForm from '@components/transaction-form/transaction-form.vue'

registerChildRoute('project_root',
    [
        {
            path: 'input',
            component: TransactionForm,
            name: 'input',
            meta: {
              label: 'Data Input/Entry',
              order: 1
            }
        }
    ]
);
