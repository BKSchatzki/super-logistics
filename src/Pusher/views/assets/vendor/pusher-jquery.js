;(function($) {

    class SL_Pusher {
        constructor() {
            this.pusher = '';
            this.channel = '';

            this.authentication()
                .registerChannel()
                .registerEvents();
        }

        authentication() {
            if(!SL_Pusher_Vars.pusher_app_key) {
                return this;
            }
            
            this.pusher = new Pusher( SL_Pusher_Vars.pusher_app_key , {
                cluster: SL_Pusher_Vars.pusher_cluster,
                authEndpoint: `${SL_Pusher_Vars.api_base_url}${SL_Pusher_Vars.api_namespace}/user/1/pusher/auth`
            });

            return this;
        }

        registerChannel() {
            if(!this.pusher) {
                return this;
            }
            this.channel = this.pusher.subscribe(SL_Pusher_Vars.channel+'-'+SL_Pusher_Vars.user_id);

            return this;
        }

        registerEvents() {
            if(!this.channel) {
                return this;
            }
            var self = this;

            jQuery.each(SL_Pusher_Vars.events,function( key, event ) {

                self.channel.bind(event, function(data) {
                    let title = typeof data.title == 'undefined' ? '' : data.title;
                    let message = typeof data.message == 'undefined' ? '' : data.message;


                    toastr.info(title, message, {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "500",
                        "hideDuration": "1000",
                        "timeOut": "5000",//Set 0 for push
                        "extendedTimeOut": "1000",//Set 0 for push
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut",
                        "tapToDismiss": false
                    });

                    jQuery('#toast-container').addClass('pm-pro-pusher-notification-wrap');
                });
            });
        }
    }

    var SL_Pusher_Action = new SL_Pusher();

})('jQuery')
