export default {
  data () {
    return {

    }
  },
  methods: {

    getMaterialOrders() {
      const self = this;
      const request_data = {
        type: 'GET',
        url: self.base_url + 'pm/v2/material-orders',
        success: function (res) {
          // res.data provides a list of the boards associated with the global kanban
          if (res.data !== undefined) {
            console.log("successfully fetched material orders: ", res.data);
            //self.$store.commit("setMaterialOrders", res.data);
          }
        },
        error: function (res) {
          console.error('Failed to fetch global kanban:', res);
        }
      };
      self.httpRequest(request_data);
    },
    getMaterialVendors() {
      const self = this;
      const request_data = {
        type: 'GET',
        url: self.base_url + 'pm/v2/material-orders/vendors',
        success: function (res) {
          // res.data provides a list of the boards associated with the global kanban
          if (res.data !== undefined) {
            console.log("successfully fetched material vendors: ", res.data);
            //self.$store.commit("setMaterialVendors", res.data);
          }
        },
        error: function (res) {
          console.error('Failed to fetch global kanban:', res);
        }
      };
      self.httpRequest(request_data);
    }
  }
}
