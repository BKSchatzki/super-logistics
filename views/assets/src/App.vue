<template>
    <div class="wedevs-pm-wrap wrap wp-core-ui pm pm-page-wrapper" id="super-logistics">
        <h1 style="display: none;"></h1>
        
        <do-action hook="pm-before-router-view"></do-action>
        <router-view></router-view>
        <do-action hook="addons-component"></do-action>
    </div>
</template>
<style>

</style>
<script>
    import do_action from '@components/common/do-action.vue';

    export default {
        components: {
            'do-action': do_action,
        },
        
        created () {
            this.registerModule();
            jQuery( document ).ajaxComplete(function(event, request, settings) {
                setTimeout(function(){
                    jQuery('a[rel=nofollow]').attr('target','_blank');
                },2000)
            });
        },

        mounted: function () {
            window.addEventListener('keydown', e => {
                let keycode = e.keyCode || e.which;
                if ( keycode === this.shiftkey && !this.otherkey ){
                    this.shiftDown = true;
                    this.otherkey = false;
                } else if (!this.cpressed && this.shiftDown && keycode === this.eKey && !this.otherkey ) {
                    e.preventDefault();
                    this.cpressed = true;
                    this.otherkey = false;
                    this.openTaskForm();
                } else if(this.cpressed && this.shiftDown && keycode === this.eKey ) {
                    e.preventDefault();
                    this.cpressed = false;
                    this.otherkey = false;
                    this.closeTaskForm();
                } else {
                    this.otherkey = true;
                }

                if ( keycode === this.escKey ) {
                    this.epressed = false;
                    this.shiftDown = false;
                    this.otherkey = false;
                    this.closeTaskForm();
                }
            });

            window.addEventListener('keyup', e => {
                let keycode = e.keyCode || e.which;
                this.otherkey = false;
                if ( keycode === this.shiftkey ) {
                    this.shiftDown = false;
                }
            });
        },

        methods: {
            registerModule () {
                let self = this;

                SLModules.forEach(function(module) {
                    let store = require('./components/'+module.path+'/store.js');
                    self.registerStore(module.name, store.default );
                });
            },
            closeTaskForm () {
                this.taskForm  = false;
                this.epressed  = false;
                this.shiftDown = false;
                this.otherkey  = false;
            },
            openTaskForm () {
                this.taskForm = true;
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

