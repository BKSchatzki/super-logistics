export default {
    methods: {
        viewTrailerManifest(trailerNum) {
            const params = new URLSearchParams({trailerNum});
            const self = this;
            const request_data = {
                type: 'get',
                url: `/reports/trailer-manifest?${params.toString()}`,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log('Trailer Manifest loaded:', res.data.pdf);
                    self.$store.commit('setLoadedPDF', self.getPDFUrl(res.data.pdf));
                },
                error: function (res) {
                    console.error('Failed load Trailer Manifest:', res);
                }
            };
            self.sendRequest(request_data);
        },
        viewPalletManifest(palletNum) {
            const params = new URLSearchParams({palletNum});
            const self = this;
            const request_data = {
                type: 'get',
                url: `/reports/pallet-manifest?${params.toString()}`,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log('Pallet Manifest loaded:', res.data.pdf);
                    self.$store.commit('setLoadedPDF', self.getPDFUrl(res.data.pdf));
                },
                error: function (res) {
                    console.error('Failed load Pallet Manifest:', res);
                }
            };
            self.sendRequest(request_data);
        },
        viewShowReport(client_id, show_id, start_date, end_date) {
            const params = new URLSearchParams({client_id, show_id, start_date, end_date});
            const self = this;
            const request_data = {
                type: 'get',
                url: `/reports/show-report?${params.toString()}`,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log('Show Report loaded:', res.data.pdf);
                    self.$store.commit('setLoadedPDF', self.getPDFUrl(res.data.pdf));
                },
                error: function (res) {
                    console.error('Failed load Show Report:', res);
                }
            };
            self.sendRequest(request_data);
        },
        viewShowReportTwo(client_id, show_id, start_date, end_date) {
            const params = new URLSearchParams({client_id, show_id, start_date, end_date});
            const self = this;
            const request_data = {
                type: 'get',
                url: `/reports/show-report-two?${params.toString()}`,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log('Show Report loaded:', res.data.pdf);
                    self.$store.commit('setLoadedPDF', self.getPDFUrl(res.data.pdf));
                },
                error: function (res) {
                    console.error('Failed load Show Report:', res);
                }
            };
            self.sendRequest(request_data);
        },
    }
}
