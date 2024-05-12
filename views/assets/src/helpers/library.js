__webpack_public_path__ = SL_Vars.dir_url + 'views/assets/js/';

import Fragment from 'vue-fragment'
Vue.use(Fragment.Plugin)

pm.hooks = (wp && wp.hooks) ? wp.hooks : wedevsPMWPHook;

var color           = require('vue-color/src/components/Sketch.vue');
pm.color            = color.default;
pm.Multiselect      = require('vue-multiselect'); 

var commonComp      = require('./global-common-components');
pm.commonComponents = commonComp.default;

Vue.use(VTooltip);

import Mixins from '@helpers/mixin/mixin'
import Settings from '@components/settings/mixin'
// import listpage from '@components/project-task-lists/lists.vue';

appMixin.mixins = Mixins;
appMixin.settings = Settings;





