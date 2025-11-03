<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Guest implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userSession = session('user');

        $userModel = model('UserModel');

        if ($userSession === null) {
            $rememberToken = get_cookie('remember_token');

            if ($rememberToken !== null) {
                $userData = $userModel->where('remember_token', $rememberToken)
                                        ->first();
                                    
                if ($userData !== null) {
                    if ($userData['role'] === 'admin') {
                        $response = redirect('admin.dashboard.index');
                    } else if ($userData['role'] === 'teacher') {
                        $response = redirect('teacher.dashboard.index');
                    } else {
                        $response = redirect('student.home.index');
                    }
                } else {
                    $response = true;    
                }
            } else {
                $response = true;
            }
        } else {
            $userData = $userModel->where('id', $userSession['id'])
                                    ->first();

            if ($userData === null) {
                $response = true;
            } else {
                if ($userData['role'] === 'admin') {
                    $response = redirect('admin.dashboard.index');
                } else if ($userData['role'] === 'teacher') {
                    $response = redirect('teacher.dashboard.index');
                } else {
                    $response = redirect('student.home.index');
                }
            }
        }

        if ($response !== true) {
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak digunakan
    }
}
