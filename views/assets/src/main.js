import '@helpers/less/pm-style.less'
import router from '@router/router'
import store from '@store/store'
import Mixin from '@helpers/mixin/mixin'
import App from './App.vue'
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

Vue.mixin(Mixin);

new Vue(SL_Vue);

// fix the admin menu for the slug "vue-app"
menuFix('pm_projects');

//Always load in the bottom of the code
import '@helpers/underscore'


