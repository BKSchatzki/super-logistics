export default {
    methods: {
        viewTrailerManifest(trailerNum) {
            const params = new URLSearchParams({trailerNum});
            const self = this;
            const request_data = {
                type: 'GET',
                url: self.base_url + `sl/v1/reports/trailer-manifest?${params.toString()}`,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Trailer Manifest Route Works:', res);
                },
                error: function (res) {
                    console.error('Failed to update transaction:', res);
                }
            };
            self.httpRequest(request_data);
        },
        viewPalletManifest(palletNum) {
            const params = new URLSearchParams({palletNum});
            const self = this;
            const request_data = {
                type: 'GET',
                url: self.base_url + `sl/v1/reports/pallet-manifest?${params.toString()}`,
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
        },
    }
}
