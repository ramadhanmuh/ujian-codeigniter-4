<?php

namespace App\Controllers;

class Error extends BaseController
{
    public function show404()
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('error_404', $data);
    }
}
