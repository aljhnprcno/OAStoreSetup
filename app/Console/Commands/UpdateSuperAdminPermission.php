<?php

namespace App\Console\Commands;

use App\Services\Users\Acl;
use App\Services\Users\User;
use Illuminate\Console\Command;

class UpdateSuperAdminPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superadmin:update-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update superadmin permissions';

    public function handle()
    {   
        $admin = User::where('email', 'oaadmin@orangeapps.ph')->first();
        $admin->syncPermissions(Acl::permissions());
        $this->info('Superadmin updated permissions successfully!');
    }
}
