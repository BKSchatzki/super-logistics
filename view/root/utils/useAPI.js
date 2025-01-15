import {computed} from 'vue';
import {useStore} from 'vuex';
import RequestUtility from "@utils/RequestUtility.js";
import {toCapitalCase} from "@utils/helpers.js";

export function useAPI() {
    const store = useStore();

    const get = (topic, params) => {
        const stateSetter = 'set' + toCapitalCase(topic);
        const requestData = {
            type: 'get',
            url: topic,
            params: params,
            success: (res) => {
                store.commit(stateSetter, res.data.data);
            },
            error: (res) => {
                console.error(`Failed to get ${topic}:`, res);
            }
        };

        RequestUtility.sendRequest(requestData)

        return computed(() => store.state[topic]);
    }

    const post = (topic, formData) => {
        return new Promise((resolve, reject) => {
            RequestUtility.sendRequest({
                type: 'post',
                data: formData, // converted to FormData object in sendRequest
                url: topic,
                success: (res) => {
                    get(topic, {});
                    resolve(res.data);
                },
                error: (res) => {
                    console.error(`Failed to post new ${topic}:`, res);
                    reject(res);
                }
            });
        });
    }

    const destroy = (topic, data) => {
        return new Promise((resolve, reject) => {
            RequestUtility.sendRequest({
                type: 'delete',
                data: data, // converted to FormData object in sendRequest
                url: topic,
                success: (res) => {
                    get(topic, {});
                    resolve(res.data);
                },
                error: (res) => {
                    console.error(`Failed to delete ${topic}:`, res);
                    reject(res);
                }
            });
        });
    }

    const update = (topic, data) => {
        return new Promise((resolve, reject) => {
            RequestUtility.sendRequest({
                type: 'patch',
                data: data,
                url: topic,
                success: (res) => {
                    get(topic, {});
                    resolve(res.data);
                },
                error: (res) => {
                    console.error(`Failed to update ${topic}:`, res);
                    reject(res);
                }
            });
        });
    }

    const markInactive = (topic, data) => {
        return new Promise((resolve, reject) => {
            RequestUtility.sendRequest({
                type: 'patch',
                data: data,
                url: topic + '/status',
                success: (res) => {
                    get(topic, {});
                    resolve(res.data);
                },
                error: (res) => {
                    console.error(`Failed to archive ${topic}:`, res);
                    reject(res);
                }
            });
        });
    }

    return {get, post, destroy, update, markInactive}
}