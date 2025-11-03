<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('admin/dashboard', $data);
    }
}
