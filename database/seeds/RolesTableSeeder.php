<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();

        $owner = new Role();
        $owner->name         = 'supervisor';
        $owner->display_name = 'User Supervisor'; // optional
        $owner->save();

        $owner = new Role();
        $owner->name         = 'operator';
        $owner->display_name = 'User Operator'; // optional
        $owner->save();

        $owner = new Role();
        $owner->name         = 'provider';
        $owner->display_name = 'User Provider'; // optional
        $owner->save();

        $owner = new Role();
        $owner->name         = 'user';
        $owner->display_name = 'User User'; // optional
        $owner->save();
    }
}
