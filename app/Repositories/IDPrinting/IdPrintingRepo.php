<?php

namespace App\Repositories\IDPrinting;

use App\IdSetup;
use App\Request as IDRequests;
use Illuminate\Http\Request;
use App\Repositories\IDPrinting\IdPrintingInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\Kto12\GradeLevel;
use App\Libraries\Branch;
use App\Mail\IdRequestMail;
use App\Mail\IDRequestStudentMail;
use App\Services\Students\Student;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\RequestSetup;

class IdPrintingRepo implements IdPrintingInterface
{

    const SUCESS_STATUS_CODE = 200;
    const ERROR_STATUS_CODE = 403;
    protected $idSetup;

    public function __construct(IdSetup $idSetup)
    {
        $this->idSetup = $idSetup;
    }

    public function all(Request $request)
    {
        $search = isset($request->search) ? $request->search : '';
        $id_setups = IdSetup::when($search, function($query) use ($search) {
            $query->where('branch_code', 'LIKE', '%' . $search . '%');
        })->orderBy('branch_code', 'ASC');
        $per_page = isset($request->per_page) ? $request->per_page : 10;
        $skip = isset($request->page) ? ($request->page - 1) * $per_page : 0;
        $count = $id_setups->count();

        if (isset($request->branchs) && count($request->branchs) !== 0) {
            $id_setups->whereIn('branch_code', $request->branchs)->get();
        }

        $id_setups = $id_setups->skip($skip)->take($per_page)->get();
        $id_setups->map(function ($setups) {
            $branch = Branch::get($setups->branch_code, false, false, true);
            $setups['campus'] = Branch::get($setups->branch_code, false, false, false)[0]->text;
            if ($setups['type'] == 1) {
                $setups['grade_name'] = GradeLevel::on($branch[0])->where('grade_level_id', $setups->grade_level_id)->first()->toArray();
            }
            return $setups;
        });
        $data = [
            'datas' => $id_setups,
            'total' => $count,
            'page' => isset($request->page) ? $request->page : 1,
            'per_page' => $per_page,
        ];
        return response()->json($data, self::SUCESS_STATUS_CODE);
    }

    public function show(int $id)
    {
        if ($response = $this->items->find($id)) {
            $data = ['message' => 'Success', 'Items' => $response];
            $statusCode = self::SUCESS_STATUS_CODE;
        } else {
            $data = ['message' => 'User Not Found'];
            $statusCode = self::ERROR_STATUS_CODE;
        }
        return response()->json($data, $statusCode);
    }

    public function delete(Request $request)
    {
        $params = $request->all();
        if (isset($params['id']) && $params['id'] !== '' && $params['id'] !== null) {
            $delete = $this->idSetup->where('id', $request->id)->delete();
            if ($delete) {
                return response()->json(['setup deleted' => 'successfully deleted'], 200);
            } else {
                return response()->json(['error' => 'something went wrong please try again'], 403);
            }
        } else {
            return response()->json(['setup does not exist' => 'please try again'], 404);
        }
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $disk = Storage::disk('gcs');
        if (isset($params['id_front_path']) && $params['id_front_path'] != null && $params['id_front_path'] != 'null' && 
            isset($params['id_back_path']) && $params['id_back_path'] != null && $params['id_back_path'] != 'null'){
            $front = $params['id_front_path'][0]->getClientOriginalName();
            $front_name = $this->GenerateRandom();
            $back = $params['id_back_path'][0]->getClientOriginalName();
            $back_name = $this->GenerateRandom();
            $back_content = $params['back_content'][0]->getClientOriginalName();
            $back_content_name = $this->GenerateRandom();
            if ($front) {
                $front_name .= ('_' . str_replace(' ', '_', $front_name));
                $disk->put('id_templates/' . $front_name, file_get_contents($params['id_front_path'][0]));
            }
            if ($back) {
                $back_name .= ('_' . str_replace(' ', '_', $back_name));
                $disk->put('id_templates/' . $back_name, file_get_contents($params['id_back_path'][0]));
            }
            if ($back_content) {
                $back_content_name .= ('_' . str_replace(' ', '_', $back_content_name));
                $disk->put('id_templates/' . $back_content_name, file_get_contents($params['back_content'][0]));
            }
            $branch_codes = explode(',',$params['branch_code']);
            foreach ($branch_codes as $branch) {
                $branchs = Branch::get($branch, false, false, true);
                $gr_level = '';
                if (isset($params['grade_level_id']) && $params['grade_level_id'] !== "") {
                    $level = GradeLevel::on($branchs[0])->where('grade_name', $params['grade_level_id'])->first();
                    $gr_level = $level->grade_level_id;
                }
                $formData = [
                    'uuid'                   => Str::uuid(),
                    'grade_level_id'         => $gr_level,
                    'type'                   => $params['type'],
                    'branch_code'            => $branch,
                    'id_front_path'          => env('GOOGLE_CLOUD_STORAGE_API_URI').'/'.env('BUCKET_NAME').'/id_templates/' .$front_name,
                    'id_back_path'           => env('GOOGLE_CLOUD_STORAGE_API_URI').'/'.env('BUCKET_NAME').'/id_templates/' .$back_name,
                    'back_content'           => env('GOOGLE_CLOUD_STORAGE_API_URI').'/'.env('BUCKET_NAME').'/id_templates/' .$back_content_name,
                ];
                $data = $this->idSetup->create($formData);
            }
            $data = ['message' => 'Created Successfully'];
            $statusCode = self::SUCESS_STATUS_CODE;
        } else {
           if(isset($params['id']) && $params['id'] !== null) {
               $setup = $this->idSetup->where('id', $params['id'])->first();
               if (isset($params['id_front_path']) && $params['id_front_path'] !== null) {
                    $front = $params['id_front_path'][0]->getClientOriginalName();
                    $front_name = $this->GenerateRandom();
                    if ($front) {
                        $front_name .= ('_' . str_replace(' ', '_', $front_name));
                        $disk->put('id_templates/' . $front_name, file_get_contents($params['id_front_path'][0]));
                    }
                    $front_name = env('GOOGLE_CLOUD_STORAGE_API_URI').'/'.env('BUCKET_NAME').'/id_templates/' .$front_name;
               } else  {
                    $front_name = $setup->id_front_path;
               }

               if (isset($params['id_back_path']) && $params['id_back_path'] !== null) {
                $back = $params['id_back_path'][0]->getClientOriginalName();
                $back_name = $this->GenerateRandom();
                if ($back) {
                    $back_name .= ('_' . str_replace(' ', '_', $back_name));
                    $disk->put('id_templates/' . $back_name, file_get_contents($params['id_back_path'][0]));
                }
                $back_name = env('GOOGLE_CLOUD_STORAGE_API_URI').'/'.env('BUCKET_NAME').'/id_templates/' .$back_name;
                } else  {
                    $back_name = $setup->id_back_path;
                }

                if (isset($params['back_content']) && $params['back_content'] !== null) {
                    $back_content = $params['back_content'][0]->getClientOriginalName();
                    $back_content_name = $this->GenerateRandom();
                    if ($back_content) {
                        $back_content_name .= ('_' . str_replace(' ', '_', $back_content_name));
                        $disk->put('id_templates/' . $back_content_name, file_get_contents($params['back_content'][0]));
                    }
                    $back_content_name = env('GOOGLE_CLOUD_STORAGE_API_URI').'/'.env('BUCKET_NAME').'/id_templates/' .$back_content_name;
                    } else  {
                        $back_content_name = $setup->back_content;
                    }
                    $branch_codes = explode(',',$params['branch_code']);
                    foreach ($branch_codes as $branch) {
                        $branchs = Branch::get($branch, false, false, true);
                        $gr_level = "";
                        if (isset($params['grade_level_id']) && $params['grade_level_id'] !== "") {
                            $level = GradeLevel::on($branchs[0])->where('grade_name', $params['grade_level_id'])->first();
                            $gr_level = $level->grade_level_id;
                        }
                        $formData = [
                            'uuid'                   => Str::uuid(),
                            'grade_level_id'         => $gr_level ,
                            'type'                   => $params['type'],
                            'branch_code'            => $branch,
                            'id_front_path'          => $front_name,
                            'id_back_path'           => $back_name,
                            'back_content'           => $back_content_name,
                        ];
                        $data = $this->idSetup->where('id', $params['id'])->update($formData);
                    }
                    $data = ['message' => 'Updated Successfully'];
                    $statusCode = self::SUCESS_STATUS_CODE;
           }
        }
        return response()->json($data, $statusCode);
    }
    public function update(Request $request)
    {
        $params = $request->all();
        if (isset($params['id']) && $params['id'] !== '' && $params['id'] !== null) {
           
        } else {
            return response()->json(['request setup does not exist' => 'please try again'], 404);
        }
    }

    public function getTemplateByStudent(Request $request) {
       $params = $request->all();
       if ($params['branch_code']) {
            if (isset($params['grade_level_id'])) {
                $data =  IdSetup::where(['branch_code' => $params['branch_code'], 'grade_level_id' => $params['grade_level_id'], 'type' => 1])->get();
            } else {
                $data =  IdSetup::where(['branch_code' => $params['branch_code'], 'type' => 2])->get();
            }
            return response()->json($data, 200);
       } else {
        return response()->json(['message' => 'unable to find branch'], 200);
       }
    }

     public function IdRequestMail(Request $request) {
        $params = $request->all();
        if (isset($params['data'])) {
             $grade_id = isset($params['data']['branch_account']['grade_level_info']['grade_level_id']) ? $params['data']['branch_account']['grade_level_info']['grade_level_id'] : "" ;
             $exist = IDRequests::where(['entity_id' => $request['data']['id'], 'status'=> 0])->first();
             if (!$exist) {
                $create = IDRequests::create([
                    'uuid' => Str::uuid(),
                    'entity_id' => $params['data']['id'],
                    'type' => $params['data']['type'],
                    'grade_name' => $grade_id,
                    'branch_code' => $params['data']['branch_account']['branch_code'],
                    'name' => $params['data']['name'],
                ]);
                if (!$exist) {
                    $setup = RequestSetup::where('branch_code', $params['data']['branch_name'])->first();
                    $subject = 'Physical ID Request';
                    Mail::to($setup->email_address)->send(new IdRequestMail($request->data, $setup->email_address, $subject)); 
                    Mail::to($params['data']['email'])->send(new IDRequestStudentMail($request->data, $params['data'] , $subject));
                    $data =[
                        'message' => 'request success please wait for approval',
                        'code' => 1,
                    ];
                    return response()->json($data, 200);
                 }
             } else {
                 $data =[
                     'message' => 'you have pending request',
                     'code' => 2,
                 ];
                 return response()->json($data, 200);
             }
        } else {
            return response()->json(['requests failed' => 'please try again or contact school'], 403);
        }
     }

     public function downloadQr(Request $request) {
        $params = $request->all();
        $headers    = array('Content-Type' => ['svg']);
        $type       = 'svg';
        $image      = QrCode::format($type)
                     ->size(200)->errorCorrection('H')
                     ->generate($params['student_id']);

        $imageName = $params['student_id'].'.'.$type;

        Storage::disk('public')->put($imageName, $image);
        return response()->download('public/storage/'.$imageName, $imageName, $headers);
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
