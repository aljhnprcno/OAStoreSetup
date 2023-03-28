<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\RequestSetup\RequestSetupRepo;
use App\Http\Requests\RequestSetupRequest;

class RequestSetupController extends Controller
{
    protected $req_repo;
    
    public function __construct(RequestSetupRepo $req_repo) {
        $this->req_repo = $req_repo;
    }

    function lists() {
        $data['css'] = ['admin.main'];
        $data['js']  = [];
        return view('admin.id_printing.list', $data);
    }

    function request_setup() {
        $data['css'] = ['admin.main'];
        $data['js']  = [];
        return view('admin.id_printing.request_setup', $data);
    }

    public function CreateSetup(RequestSetupRequest $request) {
        return $this->req_repo->store($request);
    }

    public function requestSetupList(Request $request) {
        return $this->req_repo->index($request);
    }

    public function deleteRequestSetup(Request $request) {
        return $this->req_repo->delete($request);
    }

    public function updateRequestSetup(RequestSetupRequest $request) {
        return $this->req_repo->update($request);
    }
    

}
