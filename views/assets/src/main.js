import 'es6-promise/auto';
import Vue from 'vue';
import Vuex from 'vuex';
import App from './App.vue';
import Mixin from '@helpers/mixin/mixin';
import router from '@router/router';
import store from '@store/store';
import menuFix from '@helpers/menu-fix';
import '@helpers/less/pm-style.less';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import loadComponents from '@helpers/load-components';

Vue.config.devtools = process.env.NODE_ENV !== 'production';

loadComponents(Vue);
Vue.mixin(Mixin);
Vue.use(Vuex);
Vue.use(store);
const SL_Vue = {
    el: `#${SL_Vars.id}`,
    store,
    router,
    render: t => t(App),
}


new Vue(SL_Vue);

// fix the admin menu for the slug "vue-app"
menuFix('pm_projects');

//Always load in the bottom of the code
import '@helpers/underscore'


