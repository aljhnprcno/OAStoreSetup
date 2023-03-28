<?php

namespace App\Http\Controllers;

use App\Libraries\Branch;
use App\Libraries\SharedFunctions;
use App\Services\Employees\AccountingData;
use App\Services\Employees\Dependent;
use App\Services\Employees\EducationalBackgrounds\EducationalBackground;
use App\Services\Employees\EducationalBackgrounds\EducationType;
use App\Services\Employees\Employee;
use App\Services\Employees\EmployeeInfo;
use App\Services\Employees\GovernmentDeduction as GovDeduction;
use App\Services\Employees\HiringInfos\Department;
use App\Services\Employees\HiringInfos\Division;
use App\Services\Employees\HiringInfos\HiringInfo;
use App\Services\Employees\HiringInfos\Position;
use App\Services\Employees\HiringInfos\Section;
use App\Services\Employees\License;
use App\Services\Employees\TrainingAndSeminar;
use App\Services\Employees\WorkExperience;
use App\Services\Religions\Religion;
use App\Services\Users\BranchUser;
use App\Services\Users\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function employees()
    {
        $data['css']      = ['admin.main', 'admin.employees.list'];
        $data['js']       = [];
        $data['branches'] = Branch::get('', false, true, false);
        return view('admin.employees.list', $data);
    }

    public function view_employee($branch_code, $id)
    {
        $branch = Branch::get($branch_code, false, false, true);
        $employee = Employee::on($branch[0]);
        $query = $employee->where('id', $id)->first();
        $query->info = EmployeeInfo::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->first();
        $query->permissions = [];
        $query->branch_account = BranchUser::where('parent_id', $query->id)
            ->where('parent_type', \App\Services\Employees\Employee::class)
            ->where('branch_code', $branch_code)->first();
        if ($query->branch_account) {
            $permissions = [];
            $model_perimissions = DB::table('model_has_permissions')
                ->where('model_id', $query->branch_account->id)
                ->get();
            foreach($model_perimissions as $m_permissions) {
                $permissions[] = Permission::where('id', $m_permissions->permission_id)
                    ->pluck('name')->first();
            }
            $query->permissions = $permissions;
        }
        $query->dependents = Dependent::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->get();
        $query->educational_bgs = EducationalBackground::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->get();
        $query->work_experiences = WorkExperience::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->get();
        $query->license = License::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->first();
        $query->trainings = TrainingAndSeminar::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->get();
        $query->hire_infos = HiringInfo::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->get();
        $query->accounting_data = AccountingData::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->first();
        $query->gov_deductions = GovDeduction::where('employee_id', $id)
            ->where('branch_code', $branch_code)
            ->first();

        $religion = Religion::on($branch[0]);
        $religions = $religion->where('is_active', true)->get();

        $data['css']                      = ['admin.main', 'admin.employees.view'];
        $data['js']                       = [];
        $data['employee']                 = $query;
        $data['employee']['branch_code']  = $branch_code;
        $data['education_types']          = EducationType::all();
        $data['religions']                = $religions;
        $data['positions']                = Position::all();
        $data['divisions']                = Division::all();
        $data['departments']              = Department::all();
        $data['sections']                 = Section::all();
        return view('admin.employees.view', $data);
    }

    public function get_employees(Request $request)
    {
        $branch = Branch::get($request->branch, false, false, true);
        $employee = Employee::on($branch[0]);
        $employees = $employee->get();
        foreach($employees as $emp) {
            $e = EmployeeInfo::where('employee_id', $emp->id)->first();
            if ($e) $emp->picture = $e->picture;
        }
        return response()->json($employees);
    }

    public function getFilteredEmployees(Request $request)
    {
        $branch = Branch::get($request->branch, false, false, true);
        $employee = Employee::on($branch[0]);
        $employees = $employee->select(['id', 'firstname', 'middlename', 'lastname'])
                            ->where('firstname', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $request->name . '%')
                            ->limit(15)
                            ->get();
        return response()->json($employees);
    }

    public function update_info(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        if (EmployeeInfo::where('employee_id', $request->id)->where('branch_code', $request->branch_code)->first()) {
            $query = EmployeeInfo::where('employee_id', $request->id)
                ->where('branch_code', $request->branch_code)
                ->update([
                    'full_name_sss' => $request->data['full_name_sss'],
                    'civil_status' => $request->data['civil_status'],
                    'religion_id' => $request->data['religion_id'],
                    'units_load' => $request->data['units_load'],

                    'home_phone' => $request->data['home_phone'],
                    'email_2' => $request->data['email_2'],
                    'province_address' => $request->data['province_address'],
                    'province_phone' => $request->data['province_phone'],

                    'emergency_person' => $request->data['emergency_person'],
                    'emergency_relationship' => $request->data['emergency_relationship'],
                    'emergency_phone' => $request->data['emergency_phone']
                ]);
        } else {
            $query = EmployeeInfo::create([
                'employee_id' => $request->id,
                'branch_code' => $request->branch_code,
                'full_name_sss' => $request->data['full_name_sss'],
                'civil_status' => $request->data['civil_status'],
                'religion_id' => $request->data['religion_id'],
                'units_load' => $request->data['units_load'],

                'home_phone' => $request->data['home_phone'],
                'email_2' => $request->data['email_2'],
                'province_address' => $request->data['province_address'],
                'province_phone' => $request->data['province_phone'],

                'emergency_person' => $request->data['emergency_person'],
                'emergency_relationship' => $request->data['emergency_relationship'],
                'emergency_phone' => $request->data['emergency_phone']
            ]);
        }

        if ($query) {
            $rs = SharedFunctions::success_msg("Information saved successfully!");
        }
        return response()->json($rs);
    }

    public function update_permissions(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $branch_account = BranchUser::where('parent_id', $request->id)
            ->where('parent_type', \App\Services\Employees\Employee::class)
            ->where('branch_code', $request->branch_code)
            ->first();
        if ($branch_account) {
            $branch_account->syncPermissions($request->permissions);
        } else {
            $branch_account = BranchUser::create([
                'parent_id' => $request->id,
                'parent_type' => \App\Services\Employees\Employee::class,
                'branch_code' => $request->branch_code
            ]);
            foreach($request->permissions as $permission) {
                $branch_account->givePermissionTo($permission);
            }
        }
        return response()->json($rs);
    }
}
