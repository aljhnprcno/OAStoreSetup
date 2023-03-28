<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\Branch;
use App\Libraries\SharedFunctions;
use App\Providers\RouteServiceProvider;
use App\Services\Employees\Employee;
use App\Services\Students\Student;
use App\Services\Users\Acl;
use App\Services\Users\BranchUser;
use App\Services\Users\Permission;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        if (Auth::check()){
            return redirect(url(env('FOLDER_NAME') . '/home'));
        }
        $data['css'] = ['login'];
        $data['js']  = [];
        return view('auth.login', $data);
    }

    public function branch_login($user_type, $branch_code, $user_id)
    {
        Auth::logout();
        Session::flush();

        $permissions = [];
        $branch = Branch::get($branch_code, false, false, false);
        if ($user_type == 'employee') {
            $employee = Employee::on($branch[0]->platforms[0]['id']);
            $employee_user = $employee->where(DB::raw('md5(id)'), $user_id)->first();
            if ($employee_user) {
                $branch_account = BranchUser::where('parent_id', $employee_user->id)
                    ->where('parent_type', \App\Services\Employees\Employee::class)
                    ->where('branch_code', $branch[0]->id)
                    ->first();
                if ($branch_account) {
                    $model_perimissions = DB::table('model_has_permissions')
                        ->where('model_id', $branch_account->id)
                        ->get();
                    foreach($model_perimissions as $m_permissions) {
                        $permissions[] = Permission::where('id', $m_permissions->permission_id)
                            ->pluck('name')->first();
                    }
                } else {
                    $branch_account = BranchUser::create([
                        'parent_id' => $employee_user->id,
                        'parent_type' => \App\Services\Employees\Employee::class,
                        'branch_code' => $branch[0]->id
                    ]);
                    $branch_account->givePermissionTo([
                        Acl::PERMISSION_INVENTORY_SUPPLIES_REQUEST_CREATOR,
                        Acl::PERMISSION_INVENTORY_WAYBILL_VIEWER,
                        Acl::PERMISSION_INVENTORY_ISSUANCE_VIEWER
                    ]);
                    $permissions[] = Acl::PERMISSION_INVENTORY_SUPPLIES_REQUEST_CREATOR;
                    $permissions[] = Acl::PERMISSION_INVENTORY_ISSUANCE_VIEWER;
                    $permissions[] = Acl::PERMISSION_INVENTORY_WAYBILL_VIEWER;
                }
                Session::put('user_info', SharedFunctions::get_auth($employee_user->id, $branch[0]->id, \App\Services\Employees\Employee::class));
                Session::put('branch_code', $branch[0]->id);
                Session::put('permissions', $permissions);
                Session::put('user_id', $employee_user->id);
                Session::put('referer', $request->server('HTTP_REFERER'));
                $rs = SharedFunctions::success_msg('Logged in successfully!');
                $rs['redirect'] = env('FOLDER_NAME') . '/home';
            }
        }
        else if ($user_type == 'student') {
            $student = Student::on($branch[0]->platforms[0]['id']);
            $student_user = $student->where(DB::raw('md5(id)'), $user_id)->first();
            if ($student_user) {
                $branch_account = BranchUser::where('parent_id', $student_user->id)
                    ->where('parent_type', \App\Services\Students\Student::class)
                    ->where('branch_code', $branch[0]->id)
                    ->first();
                if ($branch_account) {
                    $model_perimissions = DB::table('model_has_permissions')
                        ->where('model_id', $branch_account->id)
                        ->get();
                    foreach($model_perimissions as $m_permissions) {
                        $permissions[] = Permission::where('id', $m_permissions->permission_id)
                            ->pluck('name')->first();
                    }
                } else {
                    $branch_account = BranchUser::create([
                        'parent_id' => $student_user->id,
                        'parent_type' => \App\Services\Students\Student::class,
                        'branch_code' => $branch[0]->id
                    ]);
                    // $branch_account->givePermissionTo([Acl::PERMISSION_VIEW_LIBRARY]);
                    // $permissions[] = Acl::PERMISSION_VIEW_LIBRARY;
                }
                Session::put('user_info', SharedFunctions::get_auth($student_user->id, $branch[0]->id, \App\Services\Students\Student::class));
                Session::put('branch_code', $branch[0]->id);
                Session::put('permissions', $permissions);
                Session::put('user_id', $student_user->id);
                $rs = SharedFunctions::success_msg('Logged in successfully!');
                $rs['redirect'] = '/dashboard';
            }
        }
        return redirect($rs['redirect']);
    }

    public function multi_login(Request $request)
    {
        $rs = SharedFunctions::prompt_msg('Invalid credentials!');
        $this->validate($request, [
            'email' => 'required|max:120',
            'password' => 'required|max:255'
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $permissions = Auth::user()->getAllPermissions()
                ->pluck('name')->toArray();
            Session::put('user_info', SharedFunctions::get_auth(Auth::user()->id, null, \App\Services\Users\User::class));
            Session::put('branch_code', null);
            Session::put('permissions', $permissions);
            Session::put('user_id', Auth::user()->id);
            Session::put('referer', $request->server('HTTP_REFERER'));
            $rs = SharedFunctions::success_msg('Logged in successfully!');
            $rs['redirect'] = env('FOLDER_NAME') . '/home';
        } else {
            $permissions = [];
            $branches = Branch::get("", false, false, false);
            foreach($branches as $branch) {
                $employee = Employee::on($branch->platforms[0]['id']);
                $employee_user = $employee->where('email', $request->email)
                    ->orWhere('employee_id', $request->email)
                    ->where('password', md5($request->password))
                    ->first();
                if ($employee_user) {
                    $branch_account = BranchUser::where('parent_id', $employee_user->id)
                        ->where('parent_type', \App\Services\Employees\Employee::class)
                        ->where('branch_code', $branch->id)
                        ->first();
                    if ($branch_account) {
                        $model_perimissions = DB::table('model_has_permissions')
                            ->where('model_id', $branch_account->id)
                            ->get();
                        foreach($model_perimissions as $m_permissions) {
                            $permissions[] = Permission::where('id', $m_permissions->permission_id)
                                ->pluck('name')->first();
                        }
                    } else {
                        $branch_account = BranchUser::create([
                            'parent_id' => $employee_user->id,
                            'parent_type' => \App\Services\Employees\Employee::class,
                            'branch_code' => $branch->id
                        ]);
                    }
                    Session::put('user_info', SharedFunctions::get_auth($employee_user->id, $branch->id, \App\Services\Employees\Employee::class));
                    Session::put('branch_code', $branch->id);
                    Session::put('permissions', $permissions);
                    Session::put('user_id', $employee_user->id);
                    Session::put('referer', $request->server('HTTP_REFERER'));
                    $rs = SharedFunctions::success_msg('Logged in successfully!');
                    $rs['redirect'] = env('FOLDER_NAME') . '/dashboard';
                    break;
                }
                else {
                    $student = Student::on($branch->platforms[0]['id']);
                    $student_user = $student->where('email', $request->email)
                        ->orWhere('control_id', $request->email)
                        ->orWhere('student_id', $request->email)
                        ->where('password', md5($request->password))
                        ->first();
                    if ($student_user) {
                        $branch_account = BranchUser::where('parent_id', $student_user->id)
                            ->where('parent_type', \App\Services\Students\Student::class)
                            ->where('branch_code', $branch->id)
                            ->first();
                        if ($branch_account) {
                            $model_perimissions = DB::table('model_has_permissions')
                                ->where('model_id', $branch_account->id)
                                ->get();
                            foreach($model_perimissions as $m_permissions) {
                                $permissions[] = Permission::where('id', $m_permissions->permission_id)
                                    ->pluck('name')->first();
                            }
                        } else {
                            $branch_account = BranchUser::create([
                                'parent_id' => $student_user->id,
                                'parent_type' => \App\Services\Students\Student::class,
                                'branch_code' => $branch->id
                            ]);
                            // $branch_account->givePermissionTo([Acl::PERMISSION_VIEW_LIBRARY]);
                            // $permissions[] = Acl::PERMISSION_VIEW_LIBRARY;
                        }
                        Session::put('user_info', SharedFunctions::get_auth($student_user->id, $branch->id, \App\Services\Students\Student::class));
                        Session::put('branch_code', $branch->id);
                        Session::put('permissions', $permissions);
                        Session::put('user_id', $student_user->id);
                        Session::put('referer', $request->server('HTTP_REFERER'));
                        $rs = SharedFunctions::success_msg('Logged in successfully!');
                        // $rs['redirect'] = '/store/index';
                        $rs['redirect'] = env('FOLDER_NAME') . '/dashboard';
                        break;
                    }
                }
            }
        }
        return response()->json($rs);
    }

    public function login(Request $request)
    {
        $rs = SharedFunctions::default_msg();
        $this->validate($request, [
            'email' => 'required|email|max:100',
            'password' => 'required|max:255'
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $permissions = Auth::user()->getAllPermissions()
                ->pluck('name')->toArray();
            Session::put('user_info', SharedFunctions::get_auth(Auth::user()->id, null, \App\Services\Users\User::class));
            Session::put('branch_code', null);
            Session::put('permissions', $permissions);
            Session::put('user_id', Auth::user()->id);
            Session::put('referer', $request->server('HTTP_REFERER'));

            $rs = SharedFunctions::success_msg();
            $rs['redirect'] = env('FOLDER_NAME') . '/home';
            $rs['text'] = "Logged in successfully!";
        }
        return response()->json($rs);
    }

    public function logout()
    {
        $redirect = env('HOME_URL', 'https://www.spud.edu.ph');
        Auth::logout();
        Session::flush();
        return $redirect;
    }
}
