export default{
    data () {
        return {

        }
    },
    methods: {
        updateClient(entity_id, code) {
            const data = this.createFormData({entity_id, code})
            const self = this;
            const request_data = {
                type: 'POST',
                url: self.base_url + 'sl/v1/entities/code',
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
            self.httpRequest(request_data);
        },
        registerUser(code) {
            const data = this.createFormData({code})
            const self = this;
            const request_data = {
                type: 'POST',
                url: self.base_url + 'sl/v1/entities/register',
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
            self.httpRequest(request_data);
        },
    }
}


