<?php

namespace App\Repositories\IDRequests;

use App\Request as IDRequest;
use Illuminate\Http\Request;
use App\Repositories\IDRequests\IDRequestsRepoInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\IdRequestMail;
use App\Mail\ReadyForPickupMail;
use Illuminate\Http\Response;
use PDF;
use App\Services\Students\Student;
use App\Services\Users\BranchUser;
use App\SchoolInfo;
use App\Services\Kto12\GradeLevel;
use App\Libraries\Branch;
use App\IdSetup;
use App\Pickup;
use App\Mail\IDRequestApproveMail;
use \Illuminate\Support\HtmlString;
use App\Services\Employees\Employee;
use App\Services\Hierarchy\Position;
use App\RequestSetup;

class IDRequestsRepo implements IDRequestsRepoInterface
{

    const SUCESS_STATUS_CODE = 200;
    const ERROR_STATUS_CODE = 403;
    protected $idRequest;

    public function __construct(IDRequest $idRequest)
    {
        $this->idRequest = $idRequest;
    }

    public function all(Request $request)
    {
        $search = isset($request->search) ? $request->search : '';
        $id_requests = IDRequest::when($search, function($query) use ($search) {
            $query->where('entity_id', 'LIKE', '%' . $search . '%');
        })->orderBy('created_at', 'DESC');

        if (!in_array(3, $request->status)) {
            if (isset($request->status)) {
                $id_requests->whereIn('status', $request->status)->orderBy('created_at','DESC')->get();
            }   
        }

        if (isset($request->date)) {
            $to = date("Y-m-d", strtotime('+1' , strtotime($request->date[0])));
            $from = date("Y-m-d", strtotime('+1' , strtotime($request->date[1])));
            $id_requests->whereDate('created_at', '<=', $from)->whereDate('created_at', '>=', $to)->orderBy('created_at','DESC')->get();
        }   

        if (isset($request->branchs) && count($request->branchs) !== 0) {
            $id_requests->whereIn('branch_code', $request->branchs)->get();
        }

        $per_page = isset($request->per_page) ? $request->per_page : 10;
        $skip = isset($request->page) ? ($request->page - 1) * $per_page : 0;
        $count = $id_requests->count();
        $id_requests = $id_requests->skip($skip)->take($per_page)->get();
        $id_requests->map(function ($setup) {
            $branch = Branch::get($setup->branch_code, false, false, true);
            $setup['campus'] = Branch::get($setup->branch_code, false, false, false)[0]->text;
            if ($setup['type'] == 'Student') {
                $setup['grade_name'] = GradeLevel::on($branch[0])->where('grade_level_id', $setup->grade_name)->first()->toArray();
            } else {
                $employee = Employee::on($branch[0]);
                $user = $employee->where('employee_id', $setup['entity_id'])->first();
                $position_id = 0;
                if ($user->academic_position_id > 0) {
                    $position_id = $user->academic_position_id;
                } else if ($user->nonacademic_position_id > 0) {
                    $position_id = $user->nonacademic_position_id;
                }
                $position_model = Position::on($branch[0]);
                $position = $position_model->where('id', $position_id)->first();
                if ($position) {
                    $setup['position'] = $position['name'];
                } else {
                    $setup['position'] = 'None';
                }
                $pickup = Pickup::where('request_id', $setup['id'])->first();
                $setup['for_pickup'] = 0;
                if ($pickup) {
                    $setup['for_pickup'] = $pickup->is_pickup;
                }
            }
            return $setup;
        });
        $data = [
            'datas' => $id_requests,
            'total' => $count,
            'page' => isset($request->page) ? $request->page : 1,
            'per_page' => $per_page,
        ];
        return response()->json($data, self::SUCESS_STATUS_CODE);
    }

    public function print(Request $request)
    {
        $params = $request->all();
        if (isset($request->branch_code)) {
            $branch = Branch::get($request->branch_code, false, false, true);
            $branch_name = Branch::get($request->branch_code, false, false, false)[0]->text;
            if ($request->user_type == 'Student') {
                $student = Student::on($branch[0]);
                if ($request->request_type == 1) {
                    $entity_id = $request->id;
                } else {
                    $entity_id = $request->entity_id;
                } 
                $user = $student->where('student_id', $entity_id)->first();
                $branch_account = BranchUser::where('parent_id', $user->id)
                    ->where('parent_type', 'App\Services\Students\Student')
                    ->where('branch_code', $request->branch_code)
                    ->first();
                if ($branch_account) {
                    $branch_account['grade_level_info'] = GradeLevel::on($branch[0])
                        ->where('grade_level_id', $user->grade_level_id)->first()->toArray();
                }
                $img = "";
                if ($user->img_src == '' || $user->img_src == null ) {
                    $img = 'assets/img/default-picture.png';
                    if( $user->gender == 'Female' ){
                        $img = 'assets/img/default-picture.png';
                    }
                } else {
                    $school_info = SchoolInfo::on($branch[0])->first();
                    $url = $school_info['file_url']. '/' . $school_info['active_disk'].'/School_Files/'.'/1/'. $school_info->folder_name. '/Students/'.$user->id. '/'.$user->img_src;
                    $img = $url;
                }
                $template =  IdSetup::where(['branch_code' => $params['branch_code'], 'type' => 1, 'grade_level_id' => $branch_account['grade_level_info']['grade_level_id']])->first();
                if (!$template) {
                    $template = [
                        'id_front_path' => 'assets/img/idstudentCS front.jpg',
                        'id_back_path' => 'assets/img/idstudent back black-1 .jpg',
                        'back_content' => 'assets/img/back.webp',
                    ];
                }
                $type = 'Student';
            } else if ($request->user_type == 'Employee') {
                $employee = Employee::on($branch[0]);
                if ($request->request_type == 1) {
                    $entity_id = $request->id;
                } else {
                    $entity_id = $request->entity_id;
                } 
                $user = $employee->where('employee_id', $entity_id)->first();
                $branch_account = BranchUser::where('parent_id', $user['id'])
                    ->where('parent_type', '\App\Services\Employees\Employee')
                    ->where('branch_code', $request->branch_code)
                    ->first();
                if ($branch_account) {
                    $user_type = $branch_account->parent_type;
                }

                $img = "";
                if ($user->img_src == '' || $user->img_src == null ) {
                    $img = 'assets/img/default-picture.png';
                    if( $user->gender == 'Female' ){
                        $img = 'assets/img/default-picture.png';
                    }
                } else {
                    $school_info = SchoolInfo::on($branch[0])->first();
                    $url = $school_info['file_url']. '/' . $school_info['active_disk'].'/School_Files/'.'/1/'. $school_info->folder_name. '/Students/'.$user->id. '/'.$user->img_src;
                    $img = $url;
                }
                $template =  IdSetup::where(['branch_code' => $params['branch_code'], 'type' => 2])->first();
                if (!$template) {
                    $template = [
                        'id_front_path' => 'assets/img/emp_front.jpg',
                        'id_back_path' => 'assets/img/emp_master.jpg',
                        'back_content' => 'assets/img/custom_back.jpg',
                    ];
                }
                $type = 'Employee';
            }
            $data = [
               'fullname' => $user->full_name,
               'id' => $request->user_type == 'Student' ?  $user->student_id : $user->employee_id ,
               'std_pic' => $img,
               'fullname' => $user->full_name,
               'id_front_path' => $template['id_front_path'],
               'id_back_path' => $template['id_back_path'],
               'back_content' => $template['back_content'],
               'campus' => $branch_name,
               'mobile_number' => isset($user->mobile_number) ? $user->mobile_number : "" ,
               'grade_name' => isset($branch_account['grade_level_info']['grade_name']) ? $branch_account['grade_level_info']['grade_name'] : "",
               'type' => $type,
            ];
            $pdf = PDF::loadView('pdf.id-request.request',compact('data'));
            $output = $pdf->output();
        }

    return new Response($output, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' =>  'inline',
                        'filename'=>"'invoice.pdf'"]);

    }

    public function approve(Request $request)
    {
        $params = $request->all();
        if (isset($params['id']) && $params['id'] !== '' && $params['id'] !== null && $params['branch_code'] !== null  && $params['entity_id'] !== null) {
            $approve = $this->idRequest->where('id', $request->id)->update(['status' => 1]);
            if ($approve) {
                $branch = Branch::get($params['branch_code'], false, false, true);
                $student = Student::on($branch[0]);
                if ($params['type'] == 'Student') {
                    $user = $student->where('student_id', $request->entity_id)->first();
                } else {
                    $employee = Employee::on($branch[0]);
                    $user = $employee->where('employee_id', $request->entity_id)->first();
                }
                $subject = 'ID Request Approve!';
                Mail::to($user->email)->send(new IDRequestApproveMail($user, $user->email , $subject));
                return response()->json(['request approved' => 'approved'], 200);
            } else {
                return response()->json(['error' => 'something went wrong please try again'], 403);
            }
        } else {
            return response()->json(['request does not exist' => 'please try again'], 404);
        }
    }


     public function IdRequestMail(Request $request) {
        $branch_name = Branch::get($request->branch_code, false, false, false)[0]->text;
        $setup = RequestSetup::where('branch_code',$branch_name)->first();
        if ($setup)  {
            $subject = 'Physical ID Request';
            Mail::to($setup->email_address)->send(new IdRequestMail($request->data, $setup->email_address , $subject));
        }else {
            return response()->json(['No email' => "Please add email to said branch"], 403);
        }
     }

     public function pickup(Request $request) {
         $params = $request->all();
         if (isset($params['id']) && isset($params['pickup_date'])) {
             $create = Pickup::create([
                'uuid' => Str::uuid(),
                'request_id' => $params['id'],
                'pickup_date' => $params['pickup_date'],
                'is_pickup' => 1,
             ]);
             $pickup = $this->idRequest->where('id', $params['id'])->update(['status' => 2]);
             if ($create) {
                 if ($params['branch_code'] !== "" && isset($params['branch_code'])) {
                    $branch = Branch::get($params['branch_code'], false, false, true);
                    if ($request->user_type == 'Student') {
                        $student = Student::on($branch[0]);
                        $user = $student->where('student_id', $request->entity_id)->first();
                        $subject = 'Your Physical ID is ready for pickup';
                    } else {
                        $employee = Employee::on($branch[0]);
                        $user = $employee->where('employee_id', $request->entity_id)->first();
                        $branch_account = BranchUser::where('parent_id', $user['id'])
                            ->where('parent_type', '\App\Services\Employees\Employee')
                            ->where('branch_code', $request->branch_code)
                            ->first();
                        if ($branch_account) {
                            $user_type = $branch_account->parent_type;
                        }
        
                    }
                    $subject = 'Your ID is ready for pickup!';
                    Mail::to($user->email)->send(new ReadyForPickupMail($user, $user->email , $subject, $request->pickup_date));
                    return response()->json(['data' => 'Success!'], 200);
                 } else {
                    return response()->json(['data' => 'student id or branch code does not exist'], 403);
                 }
             } else {
                return response()->json(['data' => 'request does not exist'], 403);
             }
         } else {
            return response()->json(['data' => 'Some went wrong please try again!'], 403);
         }
     }

    function GenerateRandom()
    {
        $today = date('YmdHi');
        $startDate = date('YmdHi', strtotime('-9 days'));
        $range = $today - $startDate;
        $rand1 = rand(0, $range);
        $rand2 = rand(0, 600000);
        return  $value=($rand1+$rand2);
    }
}
