<?php

namespace SL\User\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {
	public function transform( $item ) {
		return [
			'id'              => $item->ID,
			'user_login'      => $item->user_login,
			'user_nicename'   => $item->user_nicename,
			'user_email'      => $item->user_email,
			'user_url'        => $item->user_url,
			'user_registered' => $item->user_registered,
			'user_status'     => $item->user_status,
			'display_name'    => $item->display_name,
			'entities'        => $item->entities,
			'roles'           => $item->roles,
		];
	}
}
