<?php

namespace App\Controllers;

class ForgotPassword extends BaseController
{
    public function index(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('auth/forgot_password', $data);
    }

    public function sendResetLink()
    {
        $validationRules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_not_unique[users.email]' 
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect('forgot-password.index')
                    ->with('validationError', $this->validator->getErrors());
        }

        if (
                env('email.fromEmail') === null ||
                env('email.fromName') === null ||
                env('email.protocol') === null ||
                env('email.SMTPHost') === null ||
                env('email.SMTPUser') === null ||
                env('email.SMTPPass') === null ||
                env('email.SMTPPort') === null ||
                env('email.SMTPTimeout') === null ||
                env('email.SMTPCrypto') === null
            ) {
            return redirect('forgot-password.index')
                    ->with('error', 'Aplikasi belum siap mengirim email.');
        }

        $email = $this->request->getPost('email');

        $token = bin2hex(random_bytes(16));

        $userModel = model('UserModel');

        $user = $userModel->select('id, full_name')
                            ->where('email', $email)
                            ->first();

        $db = \Config\Database::connect();

        $builder = $db->table('password_resets');

        $builder->insert([
            'user_id' => $user['id'],
            'token' => $token,
            'expires_at' => time() + 1801
        ]);

        $CIEmail = service('email');

        $CIEmail->setTo($this->request->getPost('email'));

        $CIEmail->setFrom(env('email.fromEmail'), env('email.fromName'));
        $CIEmail->setTo($email);
        $CIEmail->setSubject('Lupa Kata Sandi');
        $CIEmail->setMessage(view('email/forgot_password.php', [
            'full_name' => $user['full_name'],
            'email' => urlencode($email),
            'token' => $token
        ]));

        if (!$CIEmail->send()) {
            return redirect('forgot-password.index')
                    ->with('error', 'Gagal mengirim tautan.');
            // echo $email->printDebugger(['headers', 'subject', 'body']);
            // return;
        }

        return redirect('forgot-password.index')
                ->with('success', 'Berhasil mengirim tautan.');
    }
}
