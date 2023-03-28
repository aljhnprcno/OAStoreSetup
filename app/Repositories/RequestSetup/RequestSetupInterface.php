<?php
namespace App\Repositories\RequestSetup;

use Illuminate\Http\Request;

interface RequestSetupInterface{

    public function store(Request $request);   
    public function index(Request $request);
    public function delete(Request $request);  
    public function update(Request $request);   
}
