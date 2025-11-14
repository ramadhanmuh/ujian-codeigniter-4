<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ChangePassword extends BaseController
{
    public function edit(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('admin/change_password', $data);
    }

    public function update()
    {
        $validationRules = [
            'new_password' => [
                'label' => 'Kata Sandi Baru',
                'rules' => [
                    'required', 'string'
                ] 
            ],
            'password_confirmation' => [
                'label' => 'Konfirmasi Kata Sandi',
                'rules' => [
                    'required', 'string',
                    'matches[new_password]'
                ] 
            ],
            'current_password' => [
                'label' => 'Kata Sandi Saat Ini',
                'rules' => [
                    'required', 'string',
                    static function ($value) {
                        $userModel = model('UserModel');

                        $data = $userModel->select('password')
                                            ->where('id', session('user')['id'])
                                            ->first();

                        return password_verify($value, $data['password']);
                    }
                ],
                'errors' => [
                    2 => '{field} harus berisi kata sandi yang digunakan saat ini.'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()
                    ->back()
                    ->with('validationError', $this->validator->getErrors());
        }

        $userModel = model('UserModel');
        
        $userModel->where('id', session('user')['id'])
                    ->set([
                        'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
                    ])
                    ->update();

        return redirect('admin.change-password.edit')
                ->with('success', 'Berhasil mengubah kata sandi.');
    }
}
