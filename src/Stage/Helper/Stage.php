<?php
namespace WeDevs\PM\Stage\Helper;

use WP_REST_Request;

class Stage {
    public static function getAllStages() {
        $db = new \WeDevs\ORM\Eloquent\Database();
        return $db->table("pm_stages")->get();
    }
    public static function getProjectsAtStage(WP_REST_Request $request ) {
        $stage_id = $request->get_params('stage_id');
        $db = new \WeDevs\ORM\Eloquent\Database();
        return $db->table("pm_stage_project")->where('stage_id', $stage_id)->get();
    }
}
