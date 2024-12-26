import MainMixin from '@/root/mixin.js';

export default {
    mixins: [MainMixin],
    data () {
        return {

        }
    },
    methods: {
        addTransaction(transaction) {
            const formData = this.createFormData({ transaction });
            formData.append('image', transaction.image_path);
            const self = this;
            const request_data = {
                type: 'post',
                url: 'transactions/',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Transaction added:', res);
                },
                error: function (res) {
                    console.error('Failed to add new transaction:', res);
                }
            };
            self.sendRequest(request_data);
        },
        updateTransaction(transaction) {
            const formData = this.createFormData({ transaction });
            formData.append('image', transaction.image_path);
            const self = this;
            const request_data = {
                type: 'post',
                url: 'transactions/update',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Transaction updated:', res);
                },
                error: function (res) {
                    console.error('Failed to update transaction:', res);
                }
            };
            self.sendRequest(request_data);
        },
        removeNote(updateID) {
            const self = this;
            const request_data = {
                type: 'delete',
                url: 'transactions/update/note/' + updateID,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Transaction updated:', res);
                },
                error: function (res) {
                    console.error('Failed to update transaction:', res);
                }
            };
            self.sendRequest(request_data);
        },
        trashTransaction(transaction_id) {
            const self = this;
            const request_data = {
                type: 'delete',
                url: `/transactions/${transaction_id}`,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Transaction updated:', res);
                    self.$store.commit('removeTransaction', transaction_id)
                },
                error: function (res) {
                    console.error('Failed to update transaction:', res);
                }
            };
            self.sendRequest(request_data);
        },
        addNote(note, transaction_id) {
            const self = this;
            const request_data = {
                type: 'post',
                url: 'transactions/notes',
                data: { note, transaction_id },
                success: function (res) {
                    console.log('Transaction updated:', res);

                },
                error: function (res) {
                    console.error('Failed to update transaction:', res);
                }
            };
            self.sendRequest(request_data);
        },
        addShow(formFill) {
            const self = this;
            const formData = this.createFormData(formFill);
            const request_data = {
                type: 'post',
                url: 'shows/',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    self.getRelevantShows();
                },
                error: function (res) {
                    console.error('Failed to add new show:', res);
                }
            };
            self.sendRequest(request_data);
        },
        addClient(formFill) {
            this.addEntity(formFill, 1).then(() => this.getClients());
        },
        addCarrier(formFill) {
            this.addEntity(formFill, 2).then(() => this.getCarriers());
        },
        addShipper(formFill) {
            this.addEntity(formFill, 3).then(() => this.getShippers());
        },
        addExhibitor(formFill) {
            this.addEntity(formFill, 4).then(() => this.getExhibitors());
        },
        addEntity(formFill, type) {
            const self = this;
            const formData = this.createFormData(formFill);
            formData.append('type', type);
            const request_data = {
                type: 'post',
                url: 'entities/',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                },
                error: function (res) {
                    console.error('Failed add new entity to database:', res);
                }
            };
            return self.sendRequest(request_data);
        },
        getRelevantShows() {
            const self = this;
            self.sendRequest({
                type: 'get',
                url: 'shows/relevant',
                success: function(res) {
                    self.$store.commit('setShows', res.data);
                },
                error: function(err) {
                    console.error('Failed to get shows:', err);
                }
            });
        },
        getClients() {
            this.getEntities(1)
            .then(clients => this.$store.commit('setClients', clients));
        },
        getCarriers() {
            this.getEntities(2)
            .then(carriers => this.$store.commit('setCarriers', carriers));
        },
        getShippers() {
            this.getEntities(3)
                .then(shippers => this.$store.commit('setShippers', shippers));
        },
        getExhibitors() {
            this.getEntities(4)
                .then(exhibitors => this.$store.commit('setExhibitors', exhibitors));
        },
        getEntities(type) {
            const self = this;
            const request_data = {
                type: 'get',
                url: 'entities',
                params: {type}
            };
            return new Promise((resolve, reject) => {
                self.sendRequest({
                    ...request_data,
                    success: function(res) {
                        resolve(res.data);
                    },
                    error: function(err) {
                        console.error('Failed to get entities:', err);
                        reject(err);
                    }
                });
            });
        },
        filePathToUrl(filePath) {
            const baseUrl = window.location.protocol + '//' + window.location.hostname + (window.location.port ? ':' + window.location.port : '') + '/';
            const serverRoot = '/var/www/html/';
            const relativePath = filePath.replace(serverRoot, '');
            return baseUrl + relativePath;
        },
        getExtLabel(txn) {
            const formData = this.createFormData({ txn });
            formData.append('image', txn.image_path);
            const self = this;
            self.sendRequest({
                type: 'post',
                url: `/transactions/external/qr`,
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    self.$store.commit('setLoadedPDF', self.getPDFUrl(res.data.pdf));
                },
                error: function(err) {
                    console.error('Failed to get labels:', err);
                }
            });
        }
    }
}
