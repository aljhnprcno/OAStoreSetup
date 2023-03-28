<?php
namespace App\Repositories\RequestSetup;

use App\Repositories\RequestSetup\RequestSetupInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\RequestSetup;
use App\Libraries\Branch;

class RequestSetupRepo implements RequestSetupInterface{

    const SUCESS_STATUS_CODE = 200;
    const ERROR_STATUS_CODE = 403;
    protected $reqsetup;

    public function __construct(RequestSetup $reqsetup) {
        $this->reqsetup = $reqsetup;
    }

    public function store(Request $request) {
        $input = $request->all();
        $input['uuid'] = Str::uuid();
        $data =  $this->reqsetup->create($input);
        if ($data) {
            $data = ['message'=>'Created Successfully'];
            $statusCode = self::SUCESS_STATUS_CODE;
        } else {
            $data = ['message'=>'Something went wrong'];
            $statusCode = self::ERROR_STATUS_CODE;
        }
        return response()->json($data, $statusCode);
    }

    public function index(Request $request) {
        $search = isset($request->search) ? $request->search : '';
        $request_setup = RequestSetup::when($search, function($query) use ($search) {
            $query->where('branch_code', 'LIKE', '%' . $search . '%');
        })->orderBy('branch_code', 'ASC');
        $per_page = isset($request->per_page) ? $request->per_page : 10;
        $skip = isset($request->page) ? ($request->page - 1) * $per_page : 0;
        $count = $request_setup->count();
        $request_setup = $request_setup->skip($skip)->take($per_page)->get();
        $request_setup->map(function ($request) {
            $setup['campus'] = Branch::get($request->branch_code, false, false, false)[0]->text;
        });
        $data = [
            'datas' => $request_setup,
            'total' => $count,
            'page' => isset($request->page) ? $request->page : 1,
            'per_page' => $per_page,
        ];
        return response()->json($data, self::SUCESS_STATUS_CODE);
    }

    public function delete(Request $request) {
        $params = $request->all();
        if (isset($params['id']) && $params['id'] !== '' && $params['id'] !== null) {
            $delete = RequestSetup::where('id', $request->id)->delete();
            if ($delete) {
                return response()->json(['request setup deleted' => 'successfully deleted'], 200);
            } else {
                return response()->json(['error' => 'something went wrong please try again'], 403);
            }
        } else {
            return response()->json(['request setup does not exist' => 'please try again'], 404);
        }
    }

    public function update(Request $request) {
        $params = $request->all();
        if (isset($params['id']) && $params['id'] !== '' && $params['id'] !== null) {
            $delete = RequestSetup::where('id', $params['id'])->update([
                'branch_code' => $params['branch_code'],
                'email_address' => $params['email_address'],
            ]);
            if ($delete) {
                return response()->json(['request setup deleted' => 'successfully deleted'], 200);
            } else {
                return response()->json(['error' => 'something went wrong please try again'], 403);
            }
        } else {
            return response()->json(['request setup does not exist' => 'please try again'], 404);
        }
    }
}
