<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Application extends BaseController
{
    public function index(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('admin/application/index', $data);
    }

    public function edit(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('admin/application/edit', $data);
    }

    public function update()
    {
        $validationRules = [
            'name' => [
                'label' => 'Nama',
                'rules' => [
                    'required', 'string',
                    'max_length[255]'
                ] 
            ],
            'copyright' => [
                'label' => 'Hak Cipta',
                'rules' => [
                    'required', 'string',
                    'max_length[255]'
                ] 
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()
                    ->back()
                    ->with('validationError', $this->validator->getErrors())
                    ->withInput();
        }

        $applicationModel = model('ApplicationModel');

        $application = $applicationModel->first();

        $applicationModel->where('id', $application['id'])
                            ->set($this->validator->getValidated())
                            ->update();

        return redirect('admin.application.index')
                ->with('success', 'Berhasil mengubah data aplikasi.');
    }
}
