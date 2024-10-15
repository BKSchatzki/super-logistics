<template>
    <div class="wedevs-pm-wrap wrap wp-core-ui pm pm-page-wrapper" id="super-logistics">
        <h1 style="display: none;"></h1>
        
        <do-action hook="sl-before-router-view"></do-action>
        <frontend-menu>
          <router-view></router-view>
        </frontend-menu>
        <do-action hook="addons-component"></do-action>
    </div>
</template>

<style>
</style>

<script>
    import do_action from '@components/common/do-action.vue';
    import FrontendMenu from '@components/frontend/Menu.vue';

    export default {
        components: {
            'do-action': do_action,
            FrontendMenu
        },
        
        created () {
            this.registerModule();
            jQuery( document ).ajaxComplete(function(event, request, settings) {
                setTimeout(function(){
                    jQuery('a[rel=nofollow]').attr('target','_blank');
                },2000)
            });
        },

        methods: {
            registerModule () {
                let self = this;

                appModules.forEach(function(module) {
                    let store = require('./components/'+module.path+'/store.js');
                    self.registerStore(module.name, store.default );
                });
            }
        },

        data () {
            return {
                is_pro: SL_Vars.is_pro,
                users: [],
                taskForm: false,
                ctrlDown: false, 
                shiftDown: false, 
                epressed: false, 
                otherkey: false,
                ctrlKey: 17,
                cmdKey: 91,
                cmdKey2: 93,
                eKey: 13,
                escKey: 27,
                shiftkey: 16,
                enterkey: 13,
            }
        }

    }
</script>

<!-- Global style -->
<style>
    #nprogress .bar {
        z-index: 99999;
    }

</style>

