<?php
namespace App\Repositories\IDPrinting;

use Illuminate\Http\Request;

interface IdPrintingInterface{

    public function all(Request $request);
    public function show(int $id);
    public function delete(Request $request);
    public function store(Request $request);
    public function update(Request $request);
    public function getTemplateByStudent(Request $request);
    public function idRequestMail(Request $request);
    public function downloadQr(Request $request);
}
