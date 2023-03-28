<?php

use App\Services\Users\Acl;
use App\Services\Users\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        foreach(Acl::permissions() as $permission) {
            Permission::findOrCreate($permission, 'api');
        }
    }
}
