<template>
    <div>
        <a href="#" id="pm-create-project" @click.prevent="is_project_popup_active()" class="pm-btn pm-btn-primary pm-btn-uppercase">
            <i class="pm-icon flaticon-plus" aria-hidden="true"></i>&nbsp;&nbsp;{{ __( 'New Project', 'wedevs-project-manager') }}
        </a>
        <a href="#" id="pm-create-project" @click.prevent="is_project_popup_active()" class="pm-btn pm-btn-primary pm-btn-uppercase">
            <i class="pm-icon flaticon-plus" aria-hidden="true"></i>&nbsp;&nbsp;New Customer
        </a>
<!--        <a href="#" id="pm-create-global-task" @click.prevent="is_task_popup_active()" class="pm-btn pm-btn-primary pm-btn-uppercase">-->
<!--            <i class="pm-icon flaticon-plus" aria-hidden="true"></i>&nbsp;&nbsp;New Task-->
<!--        </a>-->
    </div>
</template>

<script>
    var project_btn = {
        methods: {
            is_project_popup_active () {
                jQuery('#pm-project-dialog').dialog( "open" );
                //this.$store.commit('is_popup_active', {is_active: true});
            },
            is_task_popup_active () {
                jQuery('#pm-global-task-dialog').dialog( "open" );
                //this.$store.commit('is_popup_active', {is_active: true});
            }
        }
    }

    export default project_btn;
</script>
