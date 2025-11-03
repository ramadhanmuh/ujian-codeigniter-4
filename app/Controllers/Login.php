<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('auth/login', $data);
    }

    public function authenticate()
    {
        $validationRules = [
            'identity' => [
                'label' => 'Email or Username',
                'rules' => 'required|string' 
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|string'
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect('login.view')
                    ->with('validationError', $this->validator->getErrors());
        }

        $identityValue = $this->request->getPost('identity');

        if (filter_var($identityValue, FILTER_VALIDATE_EMAIL)) {
            $identityColumn = 'email';
        } else {
            $identityColumn = 'username';
        }

        $userModel = model('UserModel');

        $userData = $userModel->select('id, full_name, username, email, password, role')
                            ->where($identityColumn, $identityValue)
                            ->first();

        if ($userData === null) {
            return redirect('login.view')
                    ->with('error', 'Akun tidak ditemukan.');
        }

        if (!password_verify($this->request->getPost('password'), $userData['password'])) {
            return redirect('login.view')
                    ->with('error', 'Akun tidak ditemukan.');
        }

        unset($userData['password']);

        session()->set('user', $userData);

        // Jika remember me dicentang
        if ($this->request->getPost('remember_me') !== null) {
            $token = bin2hex(random_bytes(16));

            $userModel->update($userData['id'], [
                'remember_token' => $token
            ]);

            // Simpan cookie selama 30 hari
            set_cookie(name: 'remember_token', value: $token, expire: 2592000);
        }

        if ($userData['role'] === 'admin') {
            $response = redirect('admin.dashboard.index');
        } elseif ($userData['role'] === 'teacher') {
            $response = redirect('teacher.dashboard.index');
        } else {
            $response = redirect('student.home.index');
        }

        if ($this->request->getPost('remember_me') !== null) {
            $token = bin2hex(random_bytes(16));

            $userModel->update($userData['id'], [
                'remember_token' => $token
            ]);

            // Simpan cookie selama 30 hari
            $response->setCookie(name: 'remember_token', value: $token, expire: 2592000);
        }

        return $response;
    }
}
