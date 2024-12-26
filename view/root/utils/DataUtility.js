import {useStore} from 'vuex';
import RequestUtility from "@utils/RequestUtility.js";

class DataUtility {

    static getData(topic) {
        const store = useStore();
        const stateSetter = 'set' + topic.toUpperCase();
        const requestData = {
            type: 'get',
            url: topic,
            success: (res) => {
                store.commit(stateSetter, res.data.data);
            },
            error: (res) => {
                console.error(`Failed to get ${topic}:`, res);
            }
        };
        RequestUtility.sendRequest(requestData);
    }

    static postData(topic, formData) {
        const requestData = {
            type: 'post',
            data: formData, //converted to FormData object in sendRequest
            url: topic,
            success: () => {
                this.getData(topic)
            },
            error: (res) => {
                console.error(`Failed to post new ${topic}:`, res);
            }
        };
        RequestUtility.sendRequest(requestData);
    }

}

export default DataUtility