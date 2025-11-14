<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('student/home', $data);
    }
}
