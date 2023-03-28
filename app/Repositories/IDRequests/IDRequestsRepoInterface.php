<?php
namespace App\Repositories\IDRequests;

use Illuminate\Http\Request;

interface IDRequestsRepoInterface{

    public function all(Request $request);
    public function approve(Request $request);
    public function print(Request $request);
    public function pickup(Request $request);
    #public function update(Request $request);
    #public function getTemplateByStudent(Request $request);
    #public function idRequestMail(Request $request);
}
