<?php

namespace App\Controllers;

class Logout extends BaseController
{
    public function deleteSession()
    {
        session()->remove('user');

        return redirect('login.view')->deleteCookie('remember_token');
    }
}
