<?php

namespace App\Libraries;

use App\Services\Employees\Employee;
use App\Services\Kto12\GradeLevel;
use App\Services\Students\Student;
use App\Services\Users\BranchUser;
use App\Services\Users\User;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;
use App\SchoolInfo;
use App\Services\Employees\LeaveApprover;
use App\Services\Hierarchy\Department;
use App\Services\Hierarchy\Position;

class SharedFunctions
{
    public static function query_log($builder)
    {
        $query = str_replace(array('?'), array('\'%s\''), $builder->toSql());
        $query = vsprintf($query, $builder->getBindings());
    }

    public static function create_log() {}

    public static function default_msg()
    {
        return [
            'status'    => 0,
            'title'     => "Oops!",
            'text'      => "Something went wrong!",
            'type'      => 'error'
        ];
    }

    public static function success_msg($message = "Successfully!")
    {
        return [
            'status'    => 1,
            'title'     => "Success!",
            'text'      => $message,
            'type'      => 'success'
        ];
    }

    public static function prompt_msg($message = "Invalid!")
    {
        return[
            'status' => 0,
            'title' => 'Oops!',
            'text' => $message,
            'type' => 'error'
        ];
    }

    public static function get_auth($id, $branch_code, $user_type)
    {
        if ($branch_code) {
            $branch = Branch::get($branch_code, false, false, true);
            $branch_name = Branch::get($branch_code, false, false, false)[0]->text;
            if ($user_type == \App\Services\Employees\Employee::class) {
                $employee = Employee::on($branch[0]);
                $user = $employee->where('id', $id)->first();
                $branch_account = BranchUser::where('parent_id', $id)
                    ->where('parent_type', $user_type)
                    ->where('branch_code', $branch_code)
                    ->first();
                if ($branch_account) {
                    $user_type = $branch_account->parent_type;
                }
                
                if ($user->img_src == '' || $user->img_src == null ) {
                    $img = 'assets/img/default.jpg';
                } else {
                    $school_info = SchoolInfo::on($branch[0])->first();
                    $url = $school_info['file_url']. '/' . $school_info['active_disk'].'/School_Files/'.'/1/'. $school_info->folder_name. '/Students/'.$user->id. '/'.$user->img_src;
                    $img = $url;
                }

                $fullname = $user->full_name;
                $fname = $user->firstname;
                $mname = $user->middlename;
                $lname = $user->lastname;
                $ext_name = $user->ext_name;
                $email = $user->email;
            } else if ($user_type == \App\Services\Students\Student::class) {
                $student = Student::on($branch[0]);
                $user = $student->where('id', $id)->first();
                $branch_account = BranchUser::where('parent_id', $id)
                    ->where('parent_type', $user_type)
                    ->where('branch_code', $branch_code)
                    ->first();
                if ($branch_account) {
                    $branch_account['grade_level_info'] = GradeLevel::on($branch[0])
                        ->where('grade_level_id', $user->grade_level_id)->first()->toArray();
                }
                if ($user->img_src == '' || $user->img_src == null ) {
                    $img = 'assets/img/default.jpg';
                } else {
                    $school_info = SchoolInfo::on($branch[0])->first();
                    $url = $school_info['file_url']. '/' . $school_info['active_disk'].'/School_Files'.'/1/'. $school_info->folder_name. '/Students/'.$user->id. '/'.$user->img_src;
                    'https://files.orangeapps.ph/disk001/School_Files/1/school-69/Students/1/1615946503-227.jpg';
                    $img = $url;
                }
                $fullname = $user->full_name;
                $fname = $user->firstname;
                $mname = $user->middlename;
                $lname = $user->lastname;
                $ext_name = $user->ext_name;
                $email = $user->email;
                $student_pic = $user->img_src;
                $img = $img;
            }
        } else {
            $branch = null;
            $branch_name = '';
            $branch_account = null;
            $user = User::where('id', $id)->first();
            $fullname = $user->name;
            $fname = $user->fname;
            $mname = $user->mname;
            $lname = $user->lname;
            $ext_name = $user->ext_name;
            $email = $user->email;
        }
        $position_id = 0;
        if ($user_type == \App\Services\Employees\Employee::class) {
            $id = $user->employee_id;
            $type = 'Employee';
            if ($user->academic_position_id > 0) {
                $position_id = $user->academic_position_id;
            } else if ($user->nonacademic_position_id > 0) {
                $position_id = $user->nonacademic_position_id;
            }
            $position_model = Position::on($branch[0]);
            $position = $position_model->where('id', $position_id)->first();
        } else {
            $id = $user->student_id; 
            $type = 'Student';
        }
        return [
            'branch_name' => $branch_name,
            'branch_account' => $branch_account,
            'name' => $fullname,
            'fname' => $fname,
            'mname' => $mname,
            'lname' => $lname,
            'ext_name' => $ext_name,
            'email' => $email,
            'user_type' => $user_type,
            'pic' => isset($student_pic) ? $student_pic  : "",
            'id' => $id,
            'img' => isset($img) ? $img : "",
            'type' => $type,
            'position' => isset($position->name) ? $position->name : '', 
        ];
    }

    public static function path_to_uploaded_file($path, $public = false)
    {
        $name = File::name($path);
        $extension = File::extension($path);
        $original_name = $name.'.'.$extension;
        $mime_type = File::mimeType($path);
        $size = File::size($path);
        $error = false;
        $test = $public;
        $object = new UploadedFile($path, $original_name, $mime_type, $size, $error, $test);
        return $object;
    }

    public static function get_branch_user($id, $branch) {
        $branch_account = BranchUser::where('parent_id', $id)
                                    ->where('parent_type', \App\Services\Employees\Employee::class)
                                    ->where('branch_code', $branch)
                                    ->first();
        return $branch_account;
    }

    public static function get_branch_user_by_id($uuid) {
        $branch_account = BranchUser::where('id', $uuid)
                                    ->first();
        return $branch_account;
    }

    public static function get_current_user() {
        $user_id = Session::get('user_id');
        $emp_branch = Session::get('branch_code');
        $branch = Branch::get($emp_branch, false, false, true);
        if (Session::get('user_info')['user_type'] == \App\Services\Users\User::class) {
            $type = 'admin';
            $entity = User::find($user_id);
            $entity_name = $entity->fname . ' ' . $entity->lname;
        } else if (Session::get('user_info')['user_type'] == \App\Services\Employees\Employee::class) {
            $type = 'employee';
            $entity = Employee::on($branch[0])->where('id', $user_id)->first();
            $entity_name = $entity->firstname . ' ' . $entity->lastname;
        } else if (Session::get('user_info')['user_type'] == \App\Services\Students\Student::class) {
            $type = 'student';
            $entity = Student::on($branch[0])->where('id', $user_id)->first();
            $entity_name = $entity->firstname . ' ' . $entity->lastname;
        }
        $data = [
            'user_id' => $user_id,
            'entity' => $entity,
            'emp_branch' => $emp_branch,
            'entity_name' => $entity_name,
            'type' => $type,
        ];
        return $data;
    }

    public static function get_quantity_column($branch_code) {
        $col = 'available_quantity';
        if ($branch_code === 'an'){
            $col = 'an_available_quantity';
        } else if ($branch_code === 'fv'){
            $col = 'fv_available_quantity';
        } else if ($branch_code === 'gh'){
            $col = 'gh_available_quantity';
        } else if ($branch_code === 'lp'){
            $col = 'lp_available_quantity';
        } else if ($branch_code === 'sa'){
            $col = 'sa_available_quantity';
        } else {
            $col = 'available_quantity';
        }
        return $col;
    }

    public static function get_branch_user_by_request($request) {
        $branch_user = null;
        $entities = [];
        try {
            if ($request->type == 'student'){
                $branch_user = BranchUser::where('parent_id', $request->entity_id)
                    ->where('parent_type', \App\Services\Students\Student::class)
                    ->where('branch_code', $request->branch_code)
                    ->first();
                $branch = Branch::get($branch_user->branch_code, false, false, true);
                $entities = Student::on($branch[0])->where('id', $branch_user->parent_id)->get();
            } else if ($request->type == 'employee'){
                $branch_user = BranchUser::where('parent_id', $request->entity_id)
                    ->where('parent_type', \App\Services\Employees\Employee::class)
                    ->where('branch_code', $request->branch_code)
                    ->first();
                $branch = Branch::get($branch_user->branch_code, false, false, true);
                $entities = Employee::on($branch[0])->where('id', $branch_user->parent_id)->get();
            }
        } catch(Exception $e) {

        }
        return [
            'branch_user' => $branch_user,
            'entities' => $entities,
        ];
    }

    public static function get_school() {
        $branch = Branch::get("", false, false, false);
        $branch_code = $branch[0]->platforms[0]['id'];
        if ($branch_code == 'gh_kto12'){
            $branch_code = 'fv_kto12';
        }
        $db = SchoolInfo::on($branch_code)->first();
        $base = $db->file_url . '/' . $db->active_disk . '/School_Files/1/' . $db->folder_name . '/school_profile/';
        $data = [
            'school_name' => $db->school_name,
            'small_logo' => $base . '' . $db->login_img_src,
            'large_logo' => $base . '' . $db->logo_src,
        ];
        return $data;
    }

}
