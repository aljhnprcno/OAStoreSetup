<?php

namespace App\Http\Controllers;

use App\ExportedFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    public function get()
    {
        $notifs = [];
        if (Session::get('user_info')['user_type'] == \App\Services\Employees\Employee::class) {
            $notifs = DB::table('notifications')->where('branch_code', Session::get('branch_code'))
                ->where('notifiable_type', \App\Services\Employees\Employee::class)
                ->where('notifiable_id', Session::get('user_id'))
                ->whereNull('read_at')->orderBy('created_at', 'DESC')
                ->limit(10)->get();
        } else if (Session::get('user_info')['user_type'] == \App\Services\Users\User::class) {
            $notifs = DB::table('notifications')->where('notifiable_type', \App\Services\Users\User::class)
                ->where('notifiable_id', Session::get('user_id'))
                ->whereNull('read_at')->orderBy('created_at', 'DESC')
                ->limit(10)->get();
        } else if (Session::get('user_info')['user_type'] == \App\Services\Students\Student::class) {
            $notifs = DB::table('notifications')->where('notifiable_type', \App\Services\Students\Student::class)
                ->where('notifiable_id', Session::get('user_id'))
                ->whereNull('read_at')->orderBy('created_at', 'DESC')
                ->limit(10)->get();
        }
        return response()->json($notifs);
    }

    public function mark_as_read()
    {
        if (Session::get('user_info')['user_type'] == \App\Services\Employees\Employee::class) {
            DB::table('notifications')->where('branch_code', Session::get('branch_code'))
                ->where('notifiable_type', \App\Services\Employees\Employee::class)
                ->where('notifiable_id', Session::get('user_id'))
                ->whereNull('read_at')->orderBy('created_at')
                ->update(['read_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }
        if (Session::get('user_info')['user_type'] == \App\Services\Users\User::class) {
            DB::table('notifications')->where('notifiable_type', \App\Services\Users\User::class)
                ->where('notifiable_id', Session::get('user_id'))
                ->whereNull('read_at')->orderBy('created_at')
                ->update(['read_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }
        return response()->json();
    }

}
