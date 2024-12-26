export default {
    data () {
        return {

        }
    },
    methods: {
        getClientId() {
            const self = this;
            self.sendRequest({
                type: 'get',
                url: 'client',
                success: function(res) {
                    self.$store.commit('setClientId', parseInt(res.data.entity_id));
                },
                error: function(err) {
                    console.error(`Failed to get client id, is user registered?`, err);
                }
            });
        }
    }
}
