import TransactionLookup from '@components/transaction-lookup/transaction-lookup.vue'

registerChildRoute('project_root',
    [
        {
            path: 'lookup',
            component: TransactionLookup,
            name: 'lookup',
            meta: {
              label: 'Transaction Lookup',
              order: 2
            }
        }
    ]
);
