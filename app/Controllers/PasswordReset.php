<?php

namespace App\Controllers;

use Config\Database;

class PasswordReset extends BaseController
{
    public function index(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        $db = Database::connect();

        $builder = $db->table('password_resets as a');

        $passwordReset = $builder->select('a.token')
                                    ->join('users as b', 'b.id = a.user_id', 'inner')
                                    ->where('a.token', $this->request->getGet('token'))
                                    ->where('b.email', $this->request->getGet('email'))
                                    ->where('a.expires_at >', time())
                                    ->limit(1)
                                    ->get()
                                    ->getRowArray();

        if ($passwordReset === null) {
            return view('error_404.php', $data);
        }

        return view('auth/password_reset', $data);
    }

    public function save()
    {
        $db = Database::connect();

        $builder = $db->table('password_resets as a');

        $passwordReset = $builder->select('a.token, a.user_id')
                                    ->join('users as b', 'b.id = a.user_id', 'inner')
                                    ->where('a.token', $this->request->getGet('token'))
                                    ->where('b.email', $this->request->getGet('email'))
                                    ->where('a.expires_at >', time())
                                    ->limit(1)
                                    ->get()
                                    ->getRowArray();

        if ($passwordReset === null) {
            return redirect()->back();
        }

        $validationRules = [
            'password' => [
                'label' => 'Kata Sandi',
                'rules' => 'required|string' 
            ],
            'password_confirmation' => [
                'label' => 'Konfirmasi Kata Sandi',
                'rules' => 'required|matches[password]'
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect('password-reset.index')
                    ->with('validationError', $this->validator->getErrors());
        }

        $builder = $db->table('password_resets');

        $builder->where('user_id', $passwordReset['user_id'])
                ->delete();

        $userModel = model('UserModel');

        $userModel->where('id', $passwordReset['user_id'])
                    ->set([
                        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
                    ])
                    ->update();

        return redirect('login.view')
                ->with('success', 'Berhasil atur ulang kata sandi.');
    }
}
