export default {
    data () {
        return {

        }
    },
    methods: {
        getLabels(trans_id) {
            const self = this;
            self.httpRequest({
                type: 'GET',
                url: self.base_url + `sl/v1/transactions/labels?trans_id=${trans_id}`,
                success: function(res) {
                    self.$store.commit('setLabelPDF', self.getPDFUrl(res.data.pdf));
                },
                error: function(err) {
                    console.error('Failed to get labels:', err);
                }
            });
        },
        getTransaction(trans_id) {
            const self = this;
            const queryString = this.makeQueryString({id: trans_id});
            self.httpRequest({
                type: 'GET',
                url: self.base_url + 'sl/v1/transactions?' + queryString,
                success: function(res) {
                    self.$store.commit('setTransaction', res.data);
                },
                error: function(err) {
                    console.error(`Failed to get transaction ${trans_id}:`, err);
                }
            });
        },
        getTransactions(transInfo) {
            const self = this;
            const queryString = this.makeQueryString(transInfo);
            const typeKey= {
                3 : "crates",
                2 : "cartons",
                4 : "skids",
                5 : "fiberCases",
                6 : "carpets",
                7 : "misc"
            }
            self.httpRequest({
                type: 'GET',
                url: self.base_url + 'sl/v1/transactions/search?' + queryString,
                success: function(res) {
                    for (let t of res.data) {
                        t.items = t.items.reduce((obj, item) => {
                            const key = typeKey[item.type];
                            obj[key] = item;
                            return obj;
                        }, {});
                    }
                    self.$store.commit('setTransactions', res.data);
                },
                error: function(err) {
                    console.error('Failed to get transactions:', err);
                }
            });
        },
        deleteTransaction(transaction_id) {
            const self = this;
            const request_data = {
                type: 'DELETE',
                url: self.base_url + `sl/v1/transactions/${transaction_id}`,
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
        makeQueryString(obj) {
            let queryString = '';
            if (obj === null || obj === undefined) {
                return queryString;
            }
            for (let [key, value] of Object.entries(obj)) {
                queryString += (value && value !== "") ? `${key}=${value}&` : '';
            }
            queryString = queryString.slice(0, -1);
            return queryString;
        },
        getPDFUrl(base64PDF) {
            // Decode the base64 string to binary data
            let binaryData = atob(base64PDF);

            // Convert the binary data to a byte array
            let byteArray = new Uint8Array(binaryData.length);
            for (let i = 0; i < binaryData.length; i++) {
                byteArray[i] = binaryData.charCodeAt(i);
            }

            // Create a blob from the byte array
            let blob = new Blob([byteArray], {type: 'application/pdf'});

            return URL.createObjectURL(blob);
        },
        filePathToUrl(filePath) {
            const baseUrl = window.location.protocol + '//' + window.location.hostname + (window.location.port ? ':' + window.location.port : '') + '/';
            const serverRoot = '/var/www/html/';
            const relativePath = filePath.replace(serverRoot, '');
            return baseUrl + relativePath;
        },
        postNote(transaction_id, note) {
            const self = this;
            const request_data = {
                type: 'POST',
                url: self.base_url + `sl/v1/transactions/notes`,
                data: {
                    transaction_id: transaction_id,
                    note: note
                },
                success: function (res) {
                    console.log('Note added:', res);
                },
                error: function (res) {
                    console.error('Failed to add note:', res);
                }
            };
            self.httpRequest(request_data);
        }
    }
}
