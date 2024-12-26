import MainMixin from '@/root/mixin.js';

export default {
    data () {
        return {

        }
    },
    mixins: [MainMixin],
    methods: {
        getLabels(trans_id) {
            const self = this;
            console.log("Getting labels");
            self.sendRequest({
                type: 'get',
                url: `/transactions/labels?trans_id=${trans_id}`,
                success: function(res) {
                    self.$store.commit('setLoadedPDF', self.getPDFUrl(res.data.pdf));
                },
                error: function(err) {
                    console.error('Failed to get labels:', err);
                }
            });
        },
        getTransaction(trans_id) {
            const self = this;
            const queryString = this.makeQueryString({id: trans_id});
            self.sendRequest({
                type: 'get',
                url: 'transactions?' + queryString,
                success: function(res) {
                    const typeKey = {
                        3 : "crates",
                        2 : "cartons",
                        4 : "skids",
                        5 : "fiberCases",
                        6 : "carpets",
                        7 : "misc"
                    };
                    let itemsObj = {};
                    for (let item of res.data.items) {
                        const key = typeKey[item.type];
                        itemsObj[key] = item;
                        itemsObj[key].label = self.toNormalCase(key);
                    }
                    res.data.items = itemsObj;
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
            self.sendRequest({
                type: 'get',
                url: 'transactions/search?' + queryString,
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
        trashTransaction(transaction_id) {
            const self = this;
            const request_data = {
                type: 'delete',
                url: `/transactions/${transaction_id}`,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Transaction removed:', res);
                    self.$store.commit('removeTransaction', transaction_id)
                },
                error: function (res) {
                    console.error('Failed to update transaction:', res);
                }
            };
            self.sendRequest(request_data);
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
        filePathToUrl(filePath) {
            const baseUrl = window.location.protocol + '//' + window.location.hostname + (window.location.port ? ':' + window.location.port : '') + '/';
            const serverRoot = '/var/www/html/';
            const relativePath = filePath.replace(serverRoot, '');
            return baseUrl + relativePath;
        },
        postNote(transaction_id, note) {
            const self = this;
            const request_data = {
                type: 'post',
                url: `/transactions/notes`,
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
            self.sendRequest(request_data);
        },
        toNormalCase(str) {
            let result;

            if (str.includes('_')) {
                // Handle snake_case
                result = str.replace(/_/g, ' ');
                result = result.replace(/\b\w/g, l => l.toUpperCase());
            } else {
                // Handle camelCase
                result = str.replace(/([a-z])([A-Z])/g, "$1 $2");
                result = result.charAt(0).toUpperCase() + result.slice(1);
            }

            return result;
        },
        getUsers() {
            const self = this;
            self.sendRequest({
                type: 'get',
                url: 'users',
                success: function(res) {
                    self.$store.commit('setUsers', res.data);
                },
                error: function(err) {
                    console.error('Failed to get users:', err);
                }
            });
        }
    }
}
