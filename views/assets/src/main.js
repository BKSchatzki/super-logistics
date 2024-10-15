import '@helpers/less/pm-style.less'
import router from '@router/router'
import store from '@store/store'
import Mixin from '@helpers/mixin/mixin'
import App from './App.vue'
import AppPublic from './AppPublic.vue'
import '@directives/directive'
import '@helpers/common-components'
import menuFix from '@helpers/menu-fix';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.pmBus = new Vue();

Vue.config.devtools = true;

const SL_Vue = {
    el: `#${SL_Vars.id}`,
    store,
    router,
    render: t => t(App),
}

const SL_Vue_Public = {
    el: `#${SL_Vars.public_id}`,
    store,
    router,
    render: t => t(AppPublic),
}

Vue.mixin(Mixin);

new Vue(SL_Vue);
new Vue(SL_Vue_Public);

// fix the admin menu for the slug "vue-app"
menuFix('pm_projects');

//Always load in the bottom of the code
import '@helpers/underscore'


