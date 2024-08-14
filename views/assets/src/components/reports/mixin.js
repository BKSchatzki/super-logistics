export default {
    methods: {
        viewShowReport() {
            const params = new URLSearchParams({
                startDate: 'value1',
                endDate: 'value2'
            });
            const self = this;
            const request_data = {
                type: 'GET',
                url: self.base_url + `sl/v1/reports/show-report?${params.toString()}`,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Transaction updated:', res);
                },
                error: function (res) {
                    console.error('Failed to update transaction:', res);
                }
            };
            self.httpRequest(request_data);
        }
    }
}
