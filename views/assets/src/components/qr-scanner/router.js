import QrcodeScanner from '@components/qr-scanner/qr-scanner.vue'

registerChildRoute('project_root',
    [
        {
            path: 'scanner',
            component: QrcodeScanner,
            name: 'scanner',
            meta: {
              label: 'Scanner',
              order: 2
            }
        }
    ]
);
