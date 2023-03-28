<?php

namespace App\Console\Commands;

use App\Notifications\BranchNotification;
use App\Notifications\TestNotifications\NotificationOnly;
use App\Notifications\TestNotifications\NotificationWithRoute;
use App\Services\Employees\Employee;
use App\Services\Users\User;
use Illuminate\Console\Command;

class CreateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test notifications';

    public function handle()
    {
        $employees = Employee::on('gh_kto12')->where('id', 1)->get();
        BranchNotification::send($employees, new NotificationWithRoute('gh', 1));
        User::where('email', 'oaadmin@orangeapps.ph')->first()->notify(new NotificationOnly());
        $this->info('Notifications created successfully!');
    }
}
