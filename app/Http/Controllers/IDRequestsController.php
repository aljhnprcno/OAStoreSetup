<?php

namespace App\Http\Controllers;

use App\Repositories\IDRequests\IDRequestsRepo;
use Illuminate\Http\Request;
use App\Services\Kto12\GradeLevel;
use App\Repositories\IDPrinting\IdPrintingRepo;

class IDRequestsController extends Controller
{
    protected $id_req_repo;
    protected $id_repo;

    public function __construct(IDRequestsRepo $id_req_repo, IdPrintingRepo $id_repo)  {
        $this->id_req_repo = $id_req_repo;
        $this->id_repo = $id_repo;
    }

    public function setup() {
        $data['css'] = ['admin.main'];
        $data['js']  = [];
        return view('admin.id_requests.requests', $data);
    }

    public function getAllIDRequests(Request $request) {
        return $this->id_req_repo->all($request);
    }

    public function approveRequests(Request $request) {
        return $this->id_req_repo->approve($request);
    }

    public function print(Request $request) {
        $template = $this->id_repo->getTemplateByStudent($request);
        return $this->id_req_repo->print($request);
    }

    public function pickup(Request $request) {
        return $this->id_req_repo->pickup($request);
    }


}
