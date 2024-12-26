import RequestUtility from "@utils/RequestUtility.js";
import { checkOverlap} from "@utils/helpers.js";
import { useStore } from 'vuex';

class UserUtility {

    static getCurrentUser() {
        const store = useStore();
        const requestData = {
            type: 'get',
            url: 'users/current',
            success: (res) => {
                console.log("User from backend: ", res.data.data);
                store.commit('setUser', res.data.data);
            },
            error: (res) => {
                console.error('Failed to get current user:', res);
            }
        };
        RequestUtility.sendRequest(requestData);
    }

    static getAllAppUsers() {
        this.getAppUsers(['client_admin', 'client_employee', 'internal_admin', 'internal_employee']);
    }

    static getClientAppUsers(client_id, show_id) {
        this.getAppUsers(['client_admin', 'client_employee'], client_id, show_id);
    }

    static getAppUsers(roles, client_id, show_id) {
        const request_data = {
            type: 'get',
            url: 'app-users',
            data: {roles, client_id, show_id},
            success: function (res) {
                console.log("App Users: ", res.data);
                this.$store.commit('setUsers', res.data)
            },
            error: function (res) {
                console.error('Failed to fetch users: ', res);
            }
        };
        RequestUtility.sendRequest(request_data);
    }

    static isInternalAdmin(user) {
        return user.roles.includes('internal_admin');
    }

    static isClientAdmin(user) {
        return user.roles.includes('client_admin');
    }

    static isClient(user) {
        return checkOverlap(user.roles, ['client_admin', 'client_employee']);
    }

    static isInternal(user) {
        return checkOverlap(user.roles, ['administrator', 'internal_admin', 'internal_employee']);
    }
}

export default UserUtility;