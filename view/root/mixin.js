import axios from 'axios';

export default {

    data() {
        return {
            baseURL: localized.baseURL,
            nonce: localized.nonce
        }
    },

    methods: {
        createFormData(formFill) {
            const formData = new FormData();
            Object.entries(formFill).forEach(([key, value]) => {
                if (typeof value === 'object' && !(value instanceof File)) {
                    value = JSON.stringify(value);
                }
                formData.append(key, value);
            });
            return formData;
        },
        getCurrentUser() {
            const self = this;
            const request_data = {
                type: 'get',
                url: 'current-user',
                success: (res) => {
                    // console.log("From request in mixin: ", res.data.data);
                    self.$store.commit('setUser', res.data.data);
                },
                error: (res) => {
                    console.error('Failed to get current user:', res);
                }
            };
            self.sendRequest(request_data);
        },
        sendRequest(requestInfo) {
            // Set default headers
            requestInfo.headers = requestInfo.headers || {};
            requestInfo.headers['X-WP-Nonce'] = this.nonce;

            // Map the requestInfo properties to Axios configuration
            const axiosConfig = {
                method: requestInfo.type,
                url: this.baseURL + '/' + requestInfo.url,
                headers: requestInfo.headers,
                data: requestInfo.data || {},
                params: requestInfo.params || {},
            };

            // Create Axios request
            return axios(axiosConfig)
                .then(res => {
                    if (requestInfo.success && typeof requestInfo.success === 'function') {
                        requestInfo.success(res);
                    }
                })
                .catch(err => {
                    if (requestInfo.error && typeof requestInfo.error === 'function') {
                        requestInfo.error(err);
                    }
                });
        },
    }
};







