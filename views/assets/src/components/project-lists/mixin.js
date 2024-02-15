export default {
	data () {
		return {

		}
	},
	computed: {
        ...pm.Vuex.mapState('projectLists',
            {
                projects_view: state => state.projects_view,
            }
        ),

        isFetchProjects () {
        	return this.$store.state.projectLists.isFetchProjects;
        }
    },
	methods: {

        activeClass(view){
            if ( view == this.projects_view ) {
                return view;
            }
        },
        getStages() {
            const self = this;
            const request_data = {
                url: self.base_url + 'pm/v2/stages',
                success: function (res) {
                    self.$store.commit('setStages', res);
                },
                error: function (res) {
                    console.error('Failed to fetch stages:', res);
                }
            };
            self.httpRequest(request_data);
        },

		projects_view_class (){
            return 'grid_view' === this.projects_view ? 'pm-project-grid': 'pm-project-list'
        },

        projectFetchStatus (status) {
        	this.$store.commit( 'projectLists/projectFetchComplete', status );
        }
	}
}
