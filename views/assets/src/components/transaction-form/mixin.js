export default {
    data () {
        return {

        }
    },
    methods: {
        createFormData(formFill) {
            console.log('formFill: ', formFill);
            const formData = new FormData();
            Object.entries(formFill).forEach(([key, value]) => {
                if (typeof value === 'object' && !(value instanceof File)) {
                    value = JSON.stringify(value);
                }
                formData.append(key, value);
            });
            return formData;
        },
        addTransaction(transaction, items) {
            const formData = new FormData();
            formData.append('transaction', JSON.stringify(transaction));
            formData.append('items', JSON.stringify(items));
            console.log("formData: ", formData);
            const self = this;
            const request_data = {
                type: 'POST',
                url: self.base_url + 'sl/v1/transactions/',
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
            self.httpRequest(request_data);
        },
        addShow(formFill) {
            const self = this;
            const formData = this.createFormData(formFill);
            const request_data = {
                type: 'POST',
                url: self.base_url + 'sl/v1/shows/',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Show added:', res);
                },
                error: function (res) {
                    console.error('Failed to add new show:', res);
                }
            };
            self.httpRequest(request_data);
        },
        addClient(formFill) {
            this.addEntity(formFill, 1)
        },
        addCarrier(formFill) {
            this.addEntity(formFill, 2)
        },
        addExhibitor(formFill) {
            this.addEntity(formFill, 3)
        },
        addShipper(formFill) {
            this.addEntity(formFill, 4)
        },
        addEntity(formFill, type) {
            const self = this;
            const formData = this.createFormData(formFill);
            formData.append('type', type);
            const request_data = {
                type: 'POST',
                url: self.base_url + 'sl/v1/entities/',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log('Entity added:', res);
                },
                error: function (res) {
                    console.error('Failed add new entity to database:', res);
                }
            };
            self.httpRequest(request_data);
        },
        getRelevantShows() {
            const self = this;
            self.httpRequest({
                type: 'GET',
                url: self.base_url + 'sl/v1/shows/relevant',
                success: function(res) {
                    console.log("Relevant shows: ", res.data);
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
        getExhibitors() {
            this.getEntities(3)
                .then(exhibitors => this.$store.commit('setExhibitors', exhibitors));
        },
        getShippers() {
            this.getEntities(4)
                .then(shippers => this.$store.commit('setShippers', shippers));
        },
        getEntities(type) {
            const self = this;
            const request_data = {
                type: 'GET',
                url: self.base_url + `sl/v1/entities?type=${type}`,
            };
            return new Promise((resolve, reject) => {
                self.httpRequest({
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
    }
}
