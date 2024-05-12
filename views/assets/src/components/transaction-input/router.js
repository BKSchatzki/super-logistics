import TransactionInput from '@components/transaction-input/transaction-input.vue'

registerChildRoute('project_root',
    [
        {
            path: 'input',
            component: TransactionInput,
            name: 'lookup',
            meta: {
              label: 'Transaction Input',
              order: 1
            }
        }
    ]
);
