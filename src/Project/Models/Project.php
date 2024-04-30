<?php

namespace SL\Project\Models;

use SL\Core\DB_Connection\Model as Eloquent;
use SL\Project\Project_Status;
use SL\Common\Traits\Model_Events;
use SL\Task_List\Models\Task_List;
use SL\Task\Models\Task;
use SL\Discussion_Board\Models\Discussion_Board;
use SL\Milestone\Models\Milestone;
use SL\File\Models\File;
use SL\Comment\Models\Comment;
use SL\Category\Models\Category;
use SL\User\Models\User;
use SL\Activity\Models\Carrier;
use SL\Settings\Models\Settings;
use SL\Common\Models\Meta;
use SL\Role\Models\Role;

class Project extends Eloquent {

	use Project_Status, Model_Events;

	const INCOMPLETE = 0;
	const COMPLETE   = 1;
    const PENDING    = 2;
    const ARCHIVED   = 3;

    protected $table = 'pm_projects';

    protected $fillable = [
		'title',
		'description',
		'status',
		'budget',
		'pay_rate',
		'est_completion_date',
		'color_code',
		'order',
        'projectable_type',
        'completed_at',
		'created_by',
		'updated_by',
    ];

    protected $dates = [
    	'est_completion_date'
    ];

    public function scopeSearch( $query, $search ) { 
        $query->where('title',  'LIKE', '%'.$search.'%' )->orWhere( 'description', 'LIKE', '%'.$search.'%');
    }

    public function categories() {
        return $this->belongsToMany( 'SL\Category\Models\Category',
            pm_tb_prefix() . 'pm_category_project', 'project_id', 'category_id');
    }

    /**
     *  we join pm_roles table with pm_role_user 
     */
    public function assignees() {
        $role_id = Role::where('status', 1)->get(['id'])->toArray();
        $role_id = wp_list_pluck($role_id, 'id');
        
        return $this->belongsToMany( 'SL\User\Models\User',
            pm_tb_prefix() . 'pm_role_user',
            'project_id',
            'user_id'
            )
            ->whereIn( 'role_id', $role_id)
            ->withPivot( 'project_id', 'role_id' );
    }

    public function boardables() {
        return $this->hasMany( 'SL\Common\Models\Boardable', 'boardable_id' )
        ->where( 'boardable_type', 'project' );
    }

    public function task_lists() {
        return $this->hasMany( 'SL\Task_List\Models\Task_List', 'project_id' );
    }

    public function tasks() {
        return $this->hasMany( 'SL\Task\Models\Task', 'project_id' );
    }

    public function discussion_boards() {
        return $this->hasMany( 'SL\Discussion_Board\Models\Discussion_Board', 'project_id' );
    }

    public function milestones() {
        return $this->hasMany( 'SL\Milestone\Models\Milestone', 'project_id' );
    }

    public function count_milestones() {
        return $this->hasMany( 'SL\Milestone\Models\Milestone', 'project_id' )
                    ->count();
    }

    public function files() {
        return $this->hasMany( 'SL\File\Models\File', 'project_id' );
    }

    public function comments() {
        return $this->hasMany( 'SL\Comment\Models\Comment', 'project_id' );
    }

    public function activities() {
        return $this->hasMany( 'SL\Activity\Models\Carrier', 'project_id' );
    }

    public function settings() {
        return $this->hasMany( 'SL\Settings\Models\Settings', 'project_id' );
    }

    public function meta() {
        return $this->hasMany( 'SL\Common\Models\Meta', 'project_id' );
    }

    public function managers() {
        $role_id = Role::where('slug', 'manager')->first()->id;
        return $this->assignees()->where('role_id', $role_id);
    }

    public function co_workers() {
        $role_id = Role::where('slug', 'co_worker')->first()->id;
        return $this->assignees()->where('role_id', $role_id);
    }

    public function getFavouriteAttribute(  ) {
        $user_id = get_current_user_id();
        $favourite = $this->meta()->where('meta_key', '=', 'favourite_project' )
                ->where( 'entity_type', 'project' )
                ->where( 'entity_id', '=', $user_id )->first();
        

        return empty($favourite) ? null: $favourite;
    }

    public function labels() {
        return  apply_filters( 'pm_task_label', $this );
    }

    public function material_orders() {
        return $this->belongsToMany('SL\Materials\Models\MaterialOrder',
        pm_tb_prefix() . 'pm_material_orders_projects', 'project_id', 'order_id');
    }
}
