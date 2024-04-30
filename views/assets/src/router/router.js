import '@components/transaction-lookup/router';
import '@components/transaction-input/router';
import '@components/labels/router';
import Empty from '@components/root/init.vue';

SLRoutes.push({
	  path: '/',
        component:  Empty,
        name: 'project_root',

	  children: getRegisteredChildRoutes('project_root')
});

const router = new pm.VueRouter({
	routes: SLRoutes,
});

router.beforeEach((to, from, next) => {
    pm.NProgress.start();
    next();
});

//Load all components mixin
SLModules.forEach(function(module) {
    let mixin = require('@components/'+module.path+'/mixin.js');
    SLMixin[module.name] = mixin.default;
});


export default router;
