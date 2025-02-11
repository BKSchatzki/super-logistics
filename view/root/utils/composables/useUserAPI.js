import {computed} from 'vue';
import {useStore} from 'vuex';
import {useRouter} from 'vue-router';
import RequestUtility from "@utils/RequestUtility.js";

export function useUserAPI() {

    const store = useStore();

    const router = useRouter();

    const getCurrentUser = () => {
        const requestData = {
            type: 'get',
            url: 'users/current',
            success: (res) => {
                store.commit('setUser', res.data.data);
            },
            error: (res) => {
                console.log('No User logged in:', res);
                router.push('/');
            }
        };
        RequestUtility.sendRequest(requestData);

        return computed(() => store.state.user);
    }

    const logOut = () => {
        const requestData = {
            type: 'post',
            url: 'users/logout',
            success: (res) => {
                store.commit('setUser', {});
            },
            error: (res) => {
                console.log('Failed to log out:', res);
            }
        };
        RequestUtility.sendRequest(requestData);
    }

    return {getCurrentUser, logOut}
}