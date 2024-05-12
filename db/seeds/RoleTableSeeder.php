<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use SL\Role\Models\Role;
use Carbon\Carbon;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $user = wp_get_current_user();

        if (!Role::count()) {
            Role::insert([
                [
                    'title'       => __( 'Manager', 'super-logistics' ),
                    'slug'        => 'manager',
                    'description' => __( 'Manager is a person who manages the project.', 'super-logistics' ),
                    'status'      => 1,
                    'created_by'  => $user->ID,
                    'updated_by'  => $user->ID,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ],
                [
                    'title'       => __( 'Co-Worker', 'super-logistics' ),
                    'slug'        => 'co_worker',
                    'description' => __( 'Co-worker is person who works under a project.', 'super-logistics' ),
                    'status'      => 1,
                    'created_by'  => $user->ID,
                    'updated_by'  => $user->ID,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ],
            ]);
        }
    }

}
