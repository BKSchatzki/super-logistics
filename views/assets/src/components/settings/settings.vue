<script>
import DataMixin from "@components/data-input/mixin";
import SettingsMixin from "@components/settings/mixin";

export default {
  mixins: [DataMixin, SettingsMixin],
  data() {
    return {
      newComboEntityID: '',
      newComboShowID: '',
      newCode: '',
      registerCode: ''
    }
  },
  computed: {
    clients() {
      return this.$store.state.clients;
    },
    shows() {
      return this.$store.state.shows;
    },
    clientCodes() {
      return this.clients.filter(c => c.code);
    },
    user() {
      return this.$store.state.user;
    },
    subscriber() {
      return this.user.roles ? this.user.roles.includes('subscriber') : true;
    }
  },
  methods: {
    update(e) {
      this.updateClient(this.newComboEntityID, this.newComboShowID, this.newCode);
      setTimeout(() => {
        this.getClients();
      }, 2000);
      this.clear();
    },
    clear() {
      this.newComboEntityID = '';
      this.newCode = '';
    }
  },
  created() {
    this.getClients();
    this.getClientCodes();
    this.getCurrentUserRoles();
    this.getRelevantShows();
  },
}
</script>

<template>
<div>
  <h1>Settings</h1>
  <div v-if="subscriber" class="container">
    <div class="row g-3">
      <div class="col-auto">
        <input v-model="registerCode" id="registrationCode" class="form-control" type="text" placeholder="Type your code here"/>
      </div>
      <div class="col-auto">
        <button @click="() => registerUser(registerCode)" class="btn btn-primary">Register</button>
      </div>
    </div>
  </div>
  <div v-if="!subscriber" class="container">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Client</th>
          <th scope="col">Show</th>
          <th scope="col">Code</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="codeCombo in clientCodes">
          <th scope="row" :id="`entity-${codeCombo.id}`">
            {{ codeCombo.name }}
          </th>
          <td>
            <input type="text" v-model="codeCombo.code"/>
          </td>
        </tr>
        <tr>
          <td>
            <select v-model="newComboEntityID">
              <option value="">Select a Client</option>
              <option v-for="entity in clients" :value="entity.id">{{ entity.name }}</option>
            </select>
          </td>
          <td>
            <select v-model="newComboShowID">
              <option value="">Select a Show</option>
              <option v-for="s in shows" :value="s.id">{{ s.name }}</option>
            </select>
          </td>
          <td>
            <input type="text" v-model="newCode" placeholder="New Code"/>
          </td>
          <td>
            <button @click="update" class="btn btn-sm btn-primary">Add/Update Code</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</template>

<style scoped lang="less">

</style>
