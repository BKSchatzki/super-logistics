import '@components/transaction-lookup/router';
import '@components/transaction-form/router';
import '@components/labels/router';
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
