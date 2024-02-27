<template>
    <div>
        <form action="" method="post" class="pm-form pm-project-form" @submit.prevent="projectFormAction();">

            <div class="item project-name">
                <input type="text" v-model="project.title"  id="project_name" :placeholder="name_of_the_project" size="45" />
            </div>

<!--Category-->
            <div class="pm-form-item item project-category">
                <select v-model="project_category"  id='project_cat' class='chosen-select'>
                    <option value="0">Category</option>
                    <option v-for="category in categories" :value="category.id" :key="category.id" >{{ category.title }}</option>
                </select>
            </div>
<!--Description-->
            <div class="pm-form-item item project-detail">
                <textarea v-model="project_description"  class="pm-project-description" id="" rows="5" :placeholder="details_of_project"></textarea>
            </div>
            <div class="pm-project-form-users-wrap" v-if="selectedUsers.length">
                <div class="pm-form-item pm-project-role" v-if="show_role_field">
                    <table>
                        <tr v-for="projectUser in selectedUsers" :key="projectUser.id">
                            <td>{{ projectUser.display_name }}</td>
                            <td class="user-td">
                                <select  v-model="projectUser.roles.data[0].id" :disabled="!canUserEdit(projectUser)">
                                    <option v-for="role in roles" :value="role.id" :key="role.id" >{{ __(role.title, 'wedevs-project-manager') }}</option>
                                </select>
                            </td>

                            <td>
                                <a @click.prevent="deleteUser(projectUser)" v-if="canUserEdit(projectUser)" href="#" class="pm-del-proj-role pm-assign-del-user">
                                    <span class="dashicons dashicons-trash"></span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
<!--Users/Coworkers to add to project-->
            <div class="pm-form-item item project-users" v-if="show_role_field">
                <input v-pm-users class="pm-project-coworker" type="text" name="user" :placeholder="search_user" size="45">
            </div>

            <pm-do-action hook="pm_project_form" :actionData="project"></pm-do-action>
<!--Notify Co-Workers-->
            <div class="pm-form-item item project-notify">
                <label>
                    <input type="checkbox" v-model="project_notify" name="project_notify" id="project-notify" value="yes" />
                    {{ __( 'Notify Co-Workers', 'wedevs-project-manager') }}
                </label>
            </div>
<!--Submit-->
            <div class="submit">
                <input v-if="project.id" type="submit" name="update_project" id="update_project" class="pm-button pm-primary" :value="update_project">
                <input v-if="!project.id" type="submit" name="add_project" id="add_project" class="pm-button pm-primary" :value="add_new_project">
                <a @click.prevent="closeForm()" class="pm-button pm-secondary project-cancel" href="#">{{ __( 'Close', 'wedevs-project-manager') }}</a>
                <span v-show="show_spinner" class="pm-loading"></span>

            </div>

        </form>
        <div v-pm-user-create-popup-box id="pm-create-user-wrap" class="pm-new-user-wrap" :title="create_new_user">
            <project-new-user-form></project-new-user-form>
        </div>
    </div>
</template>

<script>
    import project_new_user_form from './project-new-user-form.vue';

    export default {

        props: { //projectFormStatus
            project: {
                type: Object,
                default () {
                    return {};
                }
            }
        },
        data () {
            return {
                project_name: '',
                project_cat: 0,
                project_description: typeof this.project.description == 'undefined' ? '' : this.project.description.content,
                project_notify: false,
                assignees: [],
                show_spinner: false,
                name_of_the_project: 'Client Name',
                details_of_project: 'Some details about the client (optional)',
                search_user: __( 'Search users...', 'wedevs-project-manager'),
                create_new_user: __( 'Create a new user', 'wedevs-project-manager'),
                add_new_project: __( 'Add New Project', 'wedevs-project-manager'),
                update_project: __( 'Update Project', 'wedevs-project-manager'),
                client: __("Client", 'wedevs-project-manager'), // Dont Remove this one its require for Client translation
            }
        },
        components: {
            'project-new-user-form': project_new_user_form,
        },
        created () {
            this.setProjectUser();
        },
        computed: {
            roles () {
                return this.$root.$store.state.roles;
            },

            categories () {
                return this.$root.$store.state.categories;
            },

            selectedUsers () {
                if(!this.project.hasOwnProperty('assignees')) {
                    return this.$store.state.assignees;
                } else {
                    var projects = this.$store.state.projects;
                    var index = projects.findIndex(i => i.id == this.project.id);
                    if (index !== -1) {
                        return projects[index].assignees.data;
                    }
                }
            },

            project_category: {
                get () {
                    if ( this.project.hasOwnProperty('id') ) {
                        if (
                            typeof this.project.categories !== 'undefined'
                            &&
                            this.project.categories.data.length
                        ) {

                            this.project_cat = this.project.categories.data[0].id;

                            return this.project.categories.data[0].id;
                        }
                    }

                    return this.project_cat;
                },

                set (cat) {
                    this.project_cat = cat;
                }
            },

            show_role_field () {
                return typeof PM_BP_Vars !== 'undefined' ? PM_BP_Vars.show_role_field : true;
            },

            getProjectDetails(){
                try {
                    var project = this.$store.state.project ;
                    if(this.$router.currentRoute.fullPath == '/projects/active'){
                        this.project_description = '' ;
                    }else{
                        this.project_description = project.description.content ;
                        return project ;
                    }
                }catch (e) {}
            }

        },

        methods: {

            deleteUser (del_user) {
                if ( !this.canUserEdit(del_user.id) ) {
                    return;
                }

                this.$store.commit(
                    'afterDeleteUserFromProject',
                    {
                        project_id: this.project_id,
                        user_id: del_user.id
                    }
                );
            },
            canUserEdit (user) {

                if (this.has_manage_capability()) {
                    return true;
                }

                if (user.manage_capability) {
                    return false;
                }

                if (this.current_user.data.ID == user.id) {
                    return false;
                }

                return true

            },
            projectFormAction () {
                if ( this.show_spinner ) {
                    return;
                }

                if ( !this.project.title ) {
                    pm.Toastr.error(__('Project title is required.', 'wedevs-project-manager'));
                    return;
                }

                this.show_spinner = true;

                var args = {
                    data: {
                        'title': this.project.title,
                        'categories': this.project_cat ? [this.project_cat]: null,
                        'description': this.project_description,
                        'notify_users': this.project_notify,
                        'assignees': this.formatUsers(this.selectedUsers),
                        'status': this.project.status,
                        'department_id': this.project.department_id
                    }
                }
                
                var self = this;
                if (this.project.hasOwnProperty('id')) {
                    args.data.id = this.project.id;
                    args.callback = function ( res ) {
                        self.show_spinner = false;
                        self.$store.commit('setProjectUsers', res.data.assignees.data);
                        self.closePopper('pm-project-update-wrap');
                        self.$emit('makeFromClose', false);
                    }
                    this.updateProject ( args );


                } else {
                    args.callback = function(res) {
                        // console.log(res.status);
                        // if ( res.status !== 200 ) {
                        //     self.show_spinner = false;
                        //     return;
                        // }
                        self.project.title = '';
                        self.project_cat = 0;
                        self.project.description = ''
                        self.project_notify = [];
                        self.project.status = '';
                        self.show_spinner = false;
                        self.closePopper('pm-project-update-wrap');
                        self.$router.push({
                            name: 'pm_overview',
                            params: {
                                project_id: res.data.id
                            }
                        });
                    }

                    this.newProject(args);
                }
            },
            setProjectUser () {
                if ( this.project.hasOwnProperty('id') ) {
                    this.$root.$store.commit('setSeletedUser', this.project.assignees.data);
                } else {
                    this.$root.$store.commit('resetSelectedUsers');
                }
            },
            closeForm () {
                this.$emit('update:is-active', false);
                this.closePopper();
                if(!this.project.hasOwnProperty('id')) {
                    this.project.title = '';
                    this.project_cat = 0;
                    this.project.description = ''
                    this.project_notify = [];
                    this.project.status = '';
                    this.$store.commit('setSeletedUser', []);
                }
                this.showHideProjectForm(false);
                this.$emit('makeFromClose', false)
            }
        },
        updated () {
            this.getProjectDetails ;
        }
    }

</script>

<style lang="less">
.pm-project-form {
  max-width: 100%; /* Adjust width as necessary */
  padding: 20px;
  background: #f9f9f9; /* Light grey background */
  border-radius: 4px; /* Rounded corners for the form */
  box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Subtle shadow for depth */

  .item, .pm-form-item {
    margin-bottom: 15px; /* Consistent margin for form items */
  }

  input[type="text"],
  select,
  textarea {
    width: 100%; /* Full width */
    padding: 10px;
    border: 1px solid #ddd; /* Light grey border */
    border-radius: 3px;
    box-sizing: border-box; /* Prevents padding from affecting width */
  }

  .submit {
    display: flex; /* Aligns buttons next to each other */
    justify-content: space-between; /* Space between buttons */
    align-items: center; /* Vertical alignment */

    input[type="submit"] {
      padding: 10px 15px;
      background-color: #5C6AC4; /* Primary color for submit button */
      color: #fff; /* White text */
      border: none;
      border-radius: 3px;
      cursor: pointer; /* Hand cursor on hover */
    }

    .project-cancel {
      padding: 10px 15px;
      background-color: #eee; /* Lighter grey for cancel button */
      border: 1px solid #ddd; /* Matching border with inputs */
      border-radius: 3px;
      cursor: pointer;
    }
  }

  .pm-loading {
    display: none; /* Hide loading by default, show via JS if needed */
  }

  /* User roles table adjustments */
  .pm-project-form-users-wrap {
    .pm-project-role {
      .user-td {
        select {
          padding: 5px; /* Reduced padding */
        }
      }
    }
  }
}
</style>
