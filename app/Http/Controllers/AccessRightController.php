<?php

namespace App\Http\Controllers;

use App\Libraries\Branch;
use App\Libraries\SharedFunctions;
use App\Services\Employees\Employee;
use App\Services\Employees\EmployeeInfo;
use App\Services\Users\BranchUser;
use App\Services\Users\Permission;
use App\Services\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessRightController extends Controller
{
    public function index() {
        $data['css'] = ['admin.main'];
        $data['js']  = [];
        return view('admin.access_rights.access_rights', $data);
    }

    public function edit_index() {
        $data['css'] = ['admin.main'];
        $data['js']  = [];
        return view('admin.access_rights.edit_permission', $data);
    }

    public function getPermissions() {
        $permissions = Permission::select(['id', 'name'])->get();
        return response()->json(['permissions' => $permissions]);
    }

    public function getPermission(Request $request){
        $unslug = str_replace('_', ' ', $request->slug);
        $employees = [];
        $permission = Permission::where('name', $unslug)->first();
        if ($permission){
            $employee_permissions = DB::table('model_has_permissions')
                            ->where('model_type', \App\Services\Users\BranchUser::class)
                            ->where('permission_id', $permission->id)->get();
            foreach($employee_permissions as $employee) {
                $branch_user = BranchUser::where('id', $employee->model_id)->first();
                $branch = Branch::get($branch_user->branch_code, false, false, true);
                $employee = Employee::on($branch[0])->where('id', $branch_user->parent_id)->select(['id', 'employee_id', 'firstname', 'middlename', 'lastname'])->first();
                $branches = ['gh' => 'Greenhills', 'fv' => 'Fairview', 'sa' => 'Sta. Ana', 'lp' => 'Las Piñas', 'an' => 'Angeles'];
                if (array_key_exists($branch_user->branch_code, $branches)){
                    $employee->branch_name = $branch_user->branch_code ? $branches[$branch_user->branch_code] : '';
                } else {
                    $employee->branch_name = $branch_user->branch_code;
                }
                $employee->uuid = $branch_user->id;
                $employee->branch_code = $branch_user->branch_code;
                array_push($employees, $employee);
            }
        } else {
            $permission = 'not_found';
        }
        return response()->json(['permission' => $permission, 'employees' => $employees]);
    }

    public function addPermission(Request $request) {
        $permission = Permission::find($request->permission);
        foreach($request->user_ids as $id) {
            $branch_account = BranchUser::where('parent_id', $id)
                                        ->where('parent_type', \App\Services\Employees\Employee::class)
                                        ->where('branch_code', $request->branch_code)
                                        ->first();
            if ($branch_account) {
                $branch_account->givePermissionTo($permission);
            } else {
                $branch_account = BranchUser::create([
                    'parent_id' => $id,
                    'parent_type' => \App\Services\Employees\Employee::class,
                    'branch_code' => $request->branch_code
                ]);
                $branch_account->givePermissionTo($permission);
            }
        }
    }

    public function revokePermission(Request $request) {
        $branch_account = SharedFunctions::get_branch_user_by_id($request->uuid);
        $permission = Permission::find($request->permission);
        $branch_account->revokePermissionTo($permission);
        return response()->json(['status' => 'success']);
    }

    public function deletePermission(Request $request) {
        $status = 'not_found';
        $permission = Permission::find($request->id);
        if ($permission){
            $permission->delete();
            $status = 'success';
        }
        return response()->json(['status' => $status]);
    }

}
