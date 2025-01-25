import Vuex from 'vuex';

export default new Vuex.createStore({
    state: {
        transactions: [],
        selectedShow: {},
        shows: [],
        clients: [],
        loadedPDF: '',
        users: [],
        user: {},
    },

    mutations: {
        // Super Logistics mutations
        setTransactions(state, transactions) {
            state.transactions = transactions;
        },
        removeTransaction(state, txn_id) {
            const index = state.transactions.findIndex(x => x.id === txn_id);
            state.transactions.splice(index, 1);
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
