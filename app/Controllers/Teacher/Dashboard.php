<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('teacher/dashboard', $data);
    }
}
