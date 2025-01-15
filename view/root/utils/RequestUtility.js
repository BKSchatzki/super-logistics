import axios from "axios";

class RequestUtility {
    static baseURL = localized.baseURL
    static nonce = localized.nonce

    static createFormData(formFill) {
        const formData = new FormData();
        Object.entries(formFill).forEach(([key, value]) => {
            if (typeof value === 'object' && !(value instanceof File)) {
                value = JSON.stringify(value);
            }
            formData.append(key, value);
        });
        return formData;
    }

    static sendRequest(requestInfo) {
        // Set default headers
        requestInfo.headers = requestInfo.headers || {};
        requestInfo.headers['X-WP-Nonce'] = this.nonce;

        if (requestInfo.type === 'post') {
            requestInfo.headers['Content-Type'] = 'multipart/form-data';
            requestInfo.data = this.createFormData(requestInfo.data || {});
        }

        if (requestInfo.type === 'patch') {
            requestInfo.headers['Content-Type'] = 'application/json';
            requestInfo.data = JSON.stringify(requestInfo.data || {});
        }

        // Map the requestInfo properties to Axios configuration
        const axiosConfig = {
            method: requestInfo.type,
            url: this.baseURL + '/' + requestInfo.url,
            headers: requestInfo.headers,
            data: requestInfo.data,
            params: requestInfo.params || {},
        };

        // Create Axios request
        return axios(axiosConfig)
            .then(res => {
                if (requestInfo.success && typeof requestInfo.success === 'function') return requestInfo.success(res);
            })
            .catch(err => {
                if (requestInfo.error && typeof requestInfo.error === 'function') return requestInfo.error(err);
            });
    }
}

export default RequestUtility;