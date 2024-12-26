export default{
    data () {
        return {

        }
    },
    methods: {
        updateClient(entity_id, show_id, code) {
            const data = this.createFormData({entity_id, show_id, code})
            const self = this;
            const request_data = {
                type: 'post',
                url: 'entities/code',
                data: data,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log("Code successfully updated");
                },
                error: function (res) {
                    console.error('Failed to update client: ', res);
                }
            };
            self.sendRequest(request_data);
        },
        registerUser(code) {
            const data = this.createFormData({code})
            const self = this;
            const request_data = {
                type: 'post',
                url: 'entities/register',
                data: data,
                processData: false,
                contentType: false,
                success: function () {
                    console.log("User successfully registered");
                },
                error: function (res) {
                    console.error('Failed to register client: ', res);
                }
            };
            self.sendRequest(request_data);
        },
        getClientCodes() {
            const self = this;
            const request_data = {
                type: 'get',
                url: 'entities/codes',
                success: function (res) {
                    self.$store.commit('setClientCodes', res);
                },
                error: function (res) {
                    console.error('Failed to get client codes: ', res);
                }
            };
            self.sendRequest(request_data);
        }
    }
}


