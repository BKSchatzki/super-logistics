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
        url: self.base_url + 'pm/v2/materials/orders',
        success: function (res) {
          // res.data provides a list of the boards associated with the global kanban
          if (res.data !== undefined) {
            console.log("successfully fetched material orders: ", res.data);
            //self.$store.commit("setMaterialOrders", res.data);
          }
        },
        error: function (res) {
          console.error('Failed to fetch material orders:', res);
        }
      };
      self.httpRequest(request_data);
    },
    getMaterialVendors() {
      const self = this;
      const request_data = {
        type: 'GET',
        url: self.base_url + 'pm/v2/materials/vendors',
        success: function (res) {
          // res.data provides a list of the boards associated with the global kanban
          if (res.data !== undefined) {
            self.$store.commit("setMaterialVendors", res.data);
          }
        },
        error: function (res) {
          console.error('Failed to fetch material vendors:', res);
        }
      };
      self.httpRequest(request_data);
    },
    addMaterialVendor(dataObj) {
      // dataObj = {name: "vendor name", description: "description", email: "email", phone: "phone", address: "address"}
      const self = this;
      const request_data = {
        type: 'POST',
        url: self.base_url + 'pm/v2/materials/vendors',
        data: dataObj,
        success: function (res) {
          if (res.data !== undefined) {
            console.log("successfully added new vendor: ", res.data);
            self.getMaterialVendors();
          }
        },
        error: function (res) {
          console.error('Failed to add new vendor:', res);
        }
      };
      self.httpRequest(request_data);
    }
  }
}
