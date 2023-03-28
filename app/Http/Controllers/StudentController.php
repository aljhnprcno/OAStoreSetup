<?php

namespace App\Http\Controllers;

class StudentController extends Controller
{

    public function student_index() {
        $data['css'] = ['student.main', 'student.store'];
        $data['js']  = [];
        return view('student.list', $data);
    }

    public function qrcode() {
        $data['css'] = ['student.main', 'student.store'];
        $data['js']  = [];
        return view('student.qrcode', $data);
    }


}
