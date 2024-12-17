import Mixin from '@helpers/mixin/mixin';

export default {
    mixins: [Mixin],
    methods: {
        getAllAppUsers() {
            this.getAppUsers(['client_admin', 'client_employee', 'internal_admin', 'internal_employee']);
        },
        getClientAppUsers(client_id, show_id) {
            this.getAppUsers(['client_admin', 'client_employee'], client_id, show_id);
        },
        getAppUsers(roles, client_id, show_id) {
            const self = this;
            console.log("User Management File Base URL: ", self.base_url);
            const request_data = {
                type: 'GET',
                url: self.base_url + 'sl/v1/app-users',
                data: {roles, client_id, show_id},
                success: function (res) {
                    console.log("App Users: ", res.data);
                    this.$store.commit('setUsers', res.data)
                },
                error: function (res) {
                    console.error('Failed to fetch users: ', res);
                }
            };
            self.httpRequest(request_data);
        }
    }
}
