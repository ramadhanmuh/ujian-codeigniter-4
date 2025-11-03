<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Profile extends BaseController
{
    public function index(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        $userModel = model('UserModel');

        $data['profile'] = $userModel->select('full_name, email, username')
                                        ->where('id', session('user')['id'])
                                        ->first();

        return view('admin/profile/index', $data);
    }

    public function edit(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        $userModel = model('UserModel');

        $data['profile'] = $userModel->select('full_name, email, username')
                                        ->where('id', session('user')['id'])
                                        ->first();

        return view('admin/profile/edit', $data);
    }

    public function update()
    {
        $validationRules = [
            'full_name' => [
                'label' => 'Nama Lengkap',
                'rules' => [
                    'required', 'string',
                    'max_length[255]'
                ] 
            ],
            'email' => [
                'label' => 'Email',
                'rules' => [
                    'required', 'valid_email',
                    'max_length[255]',
                    'is_unique[users.email,id,' . session('user')['id'] . ']'
                ] 
            ],
            'username' => [
                'label' => 'Username',
                'rules' => [
                    'required', 'string',
                    'regex_match[/^[a-zA-Z0-9_-]+$/]',
                    'max_length[255]',
                    'is_unique[users.username,id,' . session('user')['id'] . ']'
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
                    ->with('validationError', $this->validator->getErrors())
                    ->withInput();
        }

        $userModel = model('UserModel');
        
        $userModel->where('id', session('user')['id'])
                    ->set([
                        'full_name' => $this->request->getPost('full_name'),
                        'email' => $this->request->getPost('email'),
                        'username' => $this->request->getPost('username'),
                    ])
                    ->update();

        $user = $userModel->select('id, full_name, email, username, role')
                            ->where('id', session('user')['id'])
                            ->first();

        session()->set('user', $user);

        return redirect('admin.profile.index')
                ->with('success', 'Berhasil mengubah profil.');
    }
}
