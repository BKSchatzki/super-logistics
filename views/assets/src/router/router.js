import '@components/data-input/router';
import '@components/qr-scanner/router';
import '@components/reports/router';
import '@components/client/router';
import '@components/settings/router';
import '@components/user-management/router';

import Empty from '@components/root/init.vue';

appRoutes.push({
	  path: '/',
        component:  Empty,
        name: 'project_root',

	  children: getRegisteredChildRoutes('project_root')
});

const router = new pm.VueRouter({
	routes: appRoutes,
});

router.beforeEach((to, from, next) => {
    pm.NProgress.start();
    next();
});

//Load all components mixin
appModules.forEach(function(module) {
    let mixin = require('@components/'+module.path+'/mixin.js');
    appMixin[module.name] = mixin.default;
});


export default router;
