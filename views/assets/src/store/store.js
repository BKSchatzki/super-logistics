import Vuex from 'vuex';

export default new Vuex.createStore({
    state: {
        transactions: [],
        transaction: {},
        selectedShow: {},
        shows: [],
        carriers: [],
        shippers: [],
        clients: [],
        exhibitors: [],
        loadedPDF: '',
        users: [],
        user: {},
        clientId: 0,
        update: false,
    },

    mutations: {
        // Super Logistics mutations
        setTransactions (state, transactions) {
            state.transactions = transactions;
        },
        removeTransaction(state, transaction_id) {
            const index = state.transactions.findIndex(x => x.id === transaction_id);
            state.transactions.splice(index, 1);
        },
        setTransaction (state, transaction) {
            state.transaction = transaction;
        },
        setClients (state, clients) {
            state.clients = clients;
        },
        setCarriers (state, carriers) {
            state.carriers = carriers;
        },
        setExhibitors (state, exhibitors) {
            state.exhibitors = exhibitors;
        },
        setShippers (state, shippers) {
            state.shippers = shippers;
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
        },
        // Project mutations
        setProjects (state, projects) {
            state.projects = projects.projects;
        },
        setProject (state, project) {
            if (state.projects.findIndex(x => x.id === project.id) === -1) {
                state.projects.push(project);
            }
            state.project = jQuery.extend(true, {}, project);
            state.projectLoaded = true;
        }
    }

});
