<?php
namespace SL\Pusher\Src\Controllers;

use Reflection;
use WP_REST_Request;
use League\Fractal;
use League\Fractal\Resource\Item as Item;
use League\Fractal\Resource\Collection as Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use SL\Common\Traits\Transformer_Manager;
use SL\Common\Traits\Request_Filter;
use SL\Pusher\Core\Auth\Auth;


class Pusher_Controller {

    use Transformer_Manager, Request_Filter;

    private static $_instance;

    public static function getInstance() {
        if ( !self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function authentication( WP_REST_Request $request ) {

        $channel_name = $request->get_param( 'channel_name' );
        $socket_id    = $request->get_param( 'socket_id' );

        if ( is_user_logged_in() ) {
            $pusher = new Auth();
            echo $pusher->socket_auth( $channel_name, $socket_id );
            exit;

        } else {
          header('', true, 403);
          echo "Forbidden";
          exit;
        }
    }
}


