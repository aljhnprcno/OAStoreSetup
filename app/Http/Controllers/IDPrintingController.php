<?php

namespace App\Http\Controllers;

use App\Repositories\IDPrinting\IdPrintingRepo;
use Illuminate\Http\Request;
use App\Http\Requests\IDSetupRequest;
use App\Libraries\Branch;
use App\Services\Kto12\GradeLevel;

class IDPrintingController extends Controller
{
    protected $id_repo;
    public function __construct(IdPrintingRepo $id_repo)  {
        $this->id_repo = $id_repo;
    }

    function setup() {
        $data['css'] = ['admin.main'];
        $data['js']  = [];
        return view('admin.id_printing.id_setup', $data);
    }

    public function student_index() {
        $data['css'] = ['student.main', 'student.store'];
        $data['js']  = [];
        return view('student.id.home', $data);
    }

    public function createIDSetup(IDSetupRequest $request) {
        return $this->id_repo->store($request);
    }

    public function getIDSetups(Request $request) {
        return $this->id_repo->all($request);
    }

    public function getTemplateByStudent(Request $request) {
        return $this->id_repo->getTemplateByStudent($request);
    }

    public function IdRequestMail(Request $request) {
        return $this->id_repo->IdRequestMail($request);
    }

    public function delete(Request $request) {
        return $this->id_repo->delete($request);
    }

    public function update(Request $request) {
        return $this->id_repo->update($request);
    }

    public function download(Request $request) {
        return $this->id_repo->downloadQr($request);
    }

    public function getBranches() {
        $data = Branch::get("", false, false, false);
        return response()->json($data, 200);
    }

    public function getGradeLevel(Request $request) {
        if ($request->branch_code !== "") {
            foreach ($request->branch_code as $branches) {
                $branch = Branch::get($branches, false, false, true);
                $data = GradeLevel::on($branch[0])->get(['grade_code','grade_name','grade_level_id'])->toArray();
            }
            return response()->json($data, 200);
        } else {
            return response()->json([], 200);
        }
    }

    public function getGradeLevelByStudent(Request $request) {
        if ($request->branch_code !== "") {
            $branch = Branch::get($request->branch_code, false, false, true);
            $data = GradeLevel::on($branch[0])->where('grade_level_id', 3)->get(['grade_code','grade_name','grade_level_id']);
            return response()->json($data, 200);
        } else {
            return response()->json([], 200);
        }
    }

}
