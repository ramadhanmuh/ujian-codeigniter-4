<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        $builder = $db->table('applications');

        $data = [
            'id' => 'a7e40b01-6da2-458c-8d88-2474689de0dd',
            'name' => 'Ujian',
            'copyright' => 'Ujian 2025'
        ];

        $builder->insert($data);

        $builder = $db->table('users');

        $data = [
            'id' => 'b0bf204d-9621-498a-aae5-3a834d509419',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => password_hash('admin', PASSWORD_DEFAULT),
            'role' => 'admin'
        ];

        $builder->insert($data);

        return view('welcome_message');
    }
}
