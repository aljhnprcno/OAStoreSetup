<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Libraries\Branch;
use App\Libraries\SharedFunctions;
use App\Services\AuditTrails\AuditTrail;
use App\Services\Employees\Employee;
use App\Services\Users\BranchUser;
use App\Services\Users\Permission;
use App\Services\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function audit_trail()
    {
        $data['css'] = ['admin.main', 'admin.audit_trail'];
        $data['js']  = [];
        return view('admin.audit_trail', $data);
    }

    public function index()
    {
        $data['css'] = ['admin.main', 'admin.home'];
        $data['js']  = [];
        return view('admin.home', $data);
    }

    public function users()
    {
        $users = User::all();
        $data['css'] = ['admin.main', 'admin.users'];
        $data['js']  = [];
        $data['permissions'] = Permission::pluck('name')->all();
        $data['branches'] = Branch::get('', false, true, false);
        $data['users'] = UserResource::collection($users);
        return view('admin.users', $data);
    }

    public function get_audit_trails(Request $request)
    {
        $query = AuditTrail::whereBetween('created_at', [$request->dates[0].' 00:00:00', $request->dates[1].' 23:59:59'])->get();
        return response()->json($query);
    }

    public function get_users(Request $request)
    {
        $users = UserResource::collection(User::all());
        if ($request->branch) {
            $branch = Branch::get($request->branch, false, false, true);
            $employee = Employee::on($branch[0]);
            $employees = $employee->get()->each(function($item) use($request) {
                $item->permissions = [];
                $item->branch_code = $request->branch;
                $item->branch_account = BranchUser::where('parent_id', $item->id)
                    ->where('branch_code', $request->branch)->first();
                if ($item->branch_account) {
                    $permissions = [];
                    $model_perimissions = DB::table('model_has_permissions')
                        ->where('model_id', $item->branch_account->id)
                        ->get();
                    foreach($model_perimissions as $m_permissions) {
                        $permissions[] = Permission::where('id', $m_permissions->permission_id)
                            ->pluck('name')->first();
                    }
                    $item->permissions = $permissions;
                }
            });
            $users = EmployeeResource::collection($employees);
        }
        return response()->json($users);
    }

    public function delete_user(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $query = User::destroy($request->id);
        if ($query) {
            $rs = SharedFunctions::success_msg("User deleted successfully!");
        }
        return response()->json($rs);
    }

    public function update_permissions(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        if ($request->user_type == \App\Services\Employees\Employee::class) {
            if ($request->branch_account) {
                $branch_account = BranchUser::where('id', $request->branch_account['id'])->first();
                $branch_account->syncPermissions($request->permissions);
                if ($branch_account) {
                    $rs = SharedFunctions::success_msg("Permissions updated successfully!");
                }
            } else {
                $branch_account = BranchUser::create([
                    'parent_id' => $request->id,
                    'parent_type' => \App\Services\Employees\Employee::class,
                    'branch_code' => $request->branch
                ]);
                foreach($request->permissions as $permission) {
                    $branch_account->givePermissionTo($permission);
                }
                if ($branch_account) {
                    $rs = SharedFunctions::success_msg("Permissions updated successfully!");
                }
            }
        } else if (\App\Services\Users\User::class) {
            $user = User::where('id', $request->id)->first();
            $user->syncPermissions($request->permissions);
            if ($user) {
                $rs = SharedFunctions::success_msg("Permissions updated successfully!");
            }
        }
        return response()->json($rs);
    }

    public function add_user(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $query = User::create([
            'fname' => $request->fname,
            'mname' => $request->mname,
            'lname' => $request->lname,
            'ext_name' => $request->ext_name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        if ($query) {
            $rs = SharedFunctions::success_msg("User added successfully!");
        }
        return response()->json($rs);
    }
}
