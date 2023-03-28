<?php

use App\Services\Users\Acl;
use App\Services\Users\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'fname' => 'User',
            'mname' => '',
            'lname' => 'OA',
            'ext_name' => ' - Admin',
            'email' => 'oaadmin@orangeapps.ph',
            'password' => 'oaAdminp@ss*',
        ]);
        $admin->syncPermissions(Acl::permissions());
        $admin->save();
    }
}
