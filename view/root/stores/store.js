import Vuex from 'vuex';

export default new Vuex.createStore({
    state: {
        txns: [],
        transaction: {},
        selectedShow: {},
        shows: [],
        carriers: [],
        shippers: [],
        clients: [],
        loadedPDF: '',
        users: [],
        user: {},
        clientId: 0,
        update: false,
    },

    mutations: {
        // Super Logistics mutations
        setTxns(state, txns) {
            state.txns = txns;
        },
        removeTransaction(state, txn_id) {
            const index = state.txns.findIndex(x => x.id === txn_id);
            state.txns.splice(index, 1);
        },
        setTransaction (state, transaction) {
            state.transaction = transaction;
        },
        setClients (state, clients) {
            state.clients = clients;
        },
        setSelectedShow (state, show) {
            state.selectedShow = show;
        },
        setShows (state, shows) {
            state.shows = shows;
        },
        setLoadedPDF (state, loadedPDF) {
            state.loadedPDF = loadedPDF;},
        setUsers (state, users) {
            state.users = users;
        },
        setUser(state, user) {
            state.user = user;
        },
        setClientId(state, clientId) {
            state.clientId = clientId;
        },
        setUpdate(state, update) {
            state.update = update;
        }
    }

});
