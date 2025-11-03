<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleCheck implements FilterInterface
{
    public function before(RequestInterface $request, $role = null)
    {
        $userSession = session('user');

        $userModel = model('UserModel');

        $selectedColumn = 'id, full_name, username, email, role';

        // Jika pengguna tidak ada session
        if ($userSession === null) {
            $rememberToken = get_cookie('remember_token');

            if ($rememberToken === null) {
                return redirect('login.view')
                        ->with('error', 'Silahkan masuk terlebih dahulu.');
            }

            $userData = $userModel->select($selectedColumn)
                                    ->where('remember_token', $rememberToken)
                                    ->first();

            if ($userData !== null) {
                session()->set('user', $userData);
            }
        } else {
            $userData = $userModel->select($selectedColumn)
                                    ->where('id', $userSession['id'])
                                    ->first();
        }

        if ($userData === null) {
            return redirect('login.view')
                    ->with('error', 'Silahkan masuk terlebih dahulu.');
        }

        // Jika role user tidak termasuk role yang diizinkan
        if (!in_array($userData['role'], $role)) {
            if ($userSession['role'] === 'admin') {
                return redirect('admin.dashboard.index');
            } else if ($userSession['role'] === 'teacher') {
                return redirect('teacher.dashboard.index');
            } else {
                return redirect('student.home.index');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak digunakan
    }
}
