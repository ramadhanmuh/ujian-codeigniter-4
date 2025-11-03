<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class User extends BaseController
{
    public function index(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        $data['keyword'] = strval($this->request->getGet('kata_kunci'));

        $data['page'] = intval($this->request->getGet('halaman'));

        if ($data['page'] < 1) {
            $data['page'] = 1;
        }

        $limit = 10;

        $data['offset'] = $data['page'] > 1 ?
                        ($data['page'] * $limit) - $limit
                        : 0;

        $userModel = model('UserModel');

        $data['records'] = $userModel->select('id, full_name, email, username, role');

        if ($data['keyword'] !== '') {
            $data['records'] = $data['records']->like('full_name', $data['keyword'])
                                                ->orLike('email', $data['keyword'])
                                                ->orLike('username', $data['keyword'])
                                                ->orLike('role', $data['keyword']);
        }

        $data['records'] = $data['records']->orderBy('full_name', 'asc')
                                            ->findAll($limit, $data['offset']);

        $data['totalRecords'] = $userModel->select('id');

        if ($data['keyword'] !== '') {
            $data['totalRecords'] = $data['totalRecords']->like('full_name', $data['keyword'])
                                                ->orLike('email', $data['keyword'])
                                                ->orLike('username', $data['keyword'])
                                                ->orLike('role', $data['keyword']);
        }

        $data['totalRecords'] = $data['totalRecords']->countAllResults();

        $totalPages = intval(ceil($data['totalRecords'] / $limit));

        $previousPage = $data['page'] - 1;

        $nextPage = $data['page'] + 1;

        $data['pageItems'] = [];

        if ($data['page'] < 2) {
            $data['pageItems'][] = [
                'text' => '1',
                'link' => url_to('admin.users.index') . '?halaman=' . $data['page'],
                'active' => 1
            ];

            for ($i=$nextPage; $i <= $totalPages; $i++) { 
                if (count($data['pageItems']) > 5) {
                    break;
                }

                if ($i === $nextPage || $i === $nextPage + 1) {
                    $data['pageItems'][] = [
                        'text' => $i,
                        'link' => url_to('admin.users.index') . '?halaman=' . $i,
                    ];
                } else {
                    $data['pageItems'][] = [
                        'text' => $i,
                        'link' => url_to('admin.users.index') . '?halaman=' . $i,
                        'secondary' => 1,
                    ];
                }
            }

            if ($totalPages > 1) {
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                    'link' => url_to('admin.users.index') . '?halaman=' . $data['page'] + 1,
                    'next' => 1
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                    'link' => url_to('admin.users.index') . '?halaman=' . $totalPages,
                    'last' => 1
                ];
            }

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword']
            );
        } else if ($data['page'] === 2) {
            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                'link' => url_to('admin.users.index') . '?halaman=1',
                'first' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                'link' => url_to('admin.users.index') . '?halaman=1',
                'previous' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '1',
                'link' => url_to('admin.users.index') . '?halaman=1',
            ];

            $data['pageItems'][] = [
                'text' => '2',
                'link' => url_to('admin.users.index') . '?halaman=2',
                'active' => 1,
            ];

            for ($i=$nextPage; $i <= $totalPages; $i++) { 
                if (count($data['pageItems']) > 6) {
                    break;
                }

                if ($i === $nextPage) {
                    $data['pageItems'][] = [
                        'text' => $i,
                        'link' => url_to('admin.users.index') . '?halaman=' . $i,
                    ];
                } else {
                    $data['pageItems'][] = [
                        'text' => $i,
                        'link' => url_to('admin.users.index') . '?halaman=' . $i,
                        'secondary' => 1,
                    ];
                }

            }

            if ($data['page'] !== $totalPages) {
                 $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                    'link' => url_to('admin.users.index') . '?halaman=' . $data['page'] + 1,
                    'next' => 1
                ];

                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                    'link' => url_to('admin.users.index') . '?halaman=' . $totalPages,
                    'last' => 1
                ];
            }

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword']
            );
        } else if ($data['page'] === $totalPages - 1) {
            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                'link' => url_to('admin.users.index') . '?halaman=1',
                'first' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                'link' => url_to('admin.users.index') . '?halaman=' . $previousPage,
                'previous' => 1,
            ];

            $secondBeforePage = $data['page'] - 2;

            $data['pageItems'][] = [
                'text' => $secondBeforePage,
                'link' => url_to('admin.users.index') . '?halaman=' . $secondBeforePage,
                'secondary' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $previousPage,
                'link' => url_to('admin.users.index') . '?halaman=' . $previousPage,
            ];

            $data['pageItems'][] = [
                'text' => $data['page'],
                'link' => url_to('admin.users.index') . '?halaman=' . $data['page'],
                'active' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $nextPage,
                'link' => url_to('admin.users.index') . '?halaman=' . $nextPage,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                'link' => url_to('admin.users.index') . '?halaman=' . $nextPage,
                'next' => 1
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                'link' => url_to('admin.users.index') . '?halaman=' . $totalPages,
                'last' => 1
            ];

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword']
            );
        } else if ($data['page'] === $totalPages) {
            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                'link' => url_to('admin.users.index') . '?halaman=1',
                'first' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                'link' => url_to('admin.users.index') . '?halaman=' . $previousPage,
                'previous' => 1,
            ];

            $secondBeforePage = $data['page'] - 2;

            $data['pageItems'][] = [
                'text' => $secondBeforePage,
                'link' => url_to('admin.users.index') . '?halaman=' . $secondBeforePage,
                'secondary' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $previousPage,
                'link' => url_to('admin.users.index') . '?halaman=' . $previousPage,
            ];

            $data['pageItems'][] = [
                'text' => $data['page'],
                'link' => url_to('admin.users.index') . '?halaman=' . $data['page'],
                'active' => 1,
            ];

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword']
            );
        } else {
            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                'link' => url_to('admin.users.index') . '?halaman=1',
                'first' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                'link' => url_to('admin.users.index') . '?halaman=' . $previousPage,
                'previous' => 1,
            ];

            $secondBeforePage = $data['page'] - 2;

            $data['pageItems'][] = [
                'text' => $secondBeforePage,
                'link' => url_to('admin.users.index') . '?halaman=' . $secondBeforePage,
                'secondary' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $previousPage,
                'link' => url_to('admin.users.index') . '?halaman=' . $previousPage,
            ];

            $data['pageItems'][] = [
                'text' => $data['page'],
                'link' => url_to('admin.users.index') . '?halaman=' . $data['page'],
                'active' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $nextPage,
                'link' => url_to('admin.users.index') . '?halaman=' . $nextPage,
            ];

            $secondAfter = $data['page'] + 2;

            $data['pageItems'][] = [
                'text' => $secondAfter,
                'link' => url_to('admin.users.index') . '?halaman=' . $secondAfter,
                'secondary' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                'link' => url_to('admin.users.index') . '?halaman=' . $nextPage,
                'next' => 1
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                'link' => url_to('admin.users.index') . '?halaman=' . $totalPages,
                'last' => 1
            ];

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword']
            );
        }

        return view('admin/user/index', $data);
    }

    private function addParameterToPageItems($pageItems, $keyword): array
    {
        if ($keyword !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['link'] .= '&kata_kunci='
                                                    . $keyword;
            }
        }

        return $pageItems;
    }

    public function create(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('admin/user/create', $data);
    }

    public function store()
    {
        $validationRules = [
            'id' => [
                'label' => 'Id',
                'rules' => [
                    'required', 'string',
                    'regex_match[/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/]',
                    'is_unique[users.id]'
                ]
            ],
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
                    'is_unique[users.email]'
                ]
            ],
            'username' => [
                'label' => 'Username',
                'rules' => [
                    'required', 'string',
                    'regex_match[/^[a-zA-Z0-9_-]+$/]',
                    'max_length[255]',
                    'is_unique[users.username]'
                ]
            ],
            'password' => [
                'label' => 'Kata Sandi',
                'rules' => [
                    'required', 'string',
                ]
            ],
            'role' => [
                'label' => 'Peran',
                'rules' => [
                    'required',
                    'in_list[admin,teacher,student]'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()
                    ->back()
                    ->with('validationError', $this->validator->getErrors())
                    ->withInput();
        }

        $input = $this->validator->getValidated();

        $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);

        $userModel = model('UserModel');

        $userModel->insert($input);

        return redirect('admin.users.create')
                ->with('success', 'Berhasil menambahkan pengguna.');
    }

    public function edit($id)
    {
        $userModel = model('UserModel');

        $data['record'] = $userModel->select('id, full_name, email, username, role')
                                    ->where('id', $id)
                                    ->where('id !=', session('user')['id'])
                                    ->first();

        if ($data['record'] === null) {
            return redirect()
                    ->back()
                    ->with('error', 'Pengguna Tidak Ditemukan.');
        }

        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('admin/user/edit', $data);
    }

    public function update($id)
    {
        $userModel = model('UserModel');

        $record= $userModel->select('id, full_name, email, username, role')
                            ->where('id', $id)
                            ->where('id !=', session('user')['id'])
                            ->first();

        if ($record === null) {
            return redirect('admin.users.index')
                    ->with('error', 'Pengguna tidak ditemukan.');
        }

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
                    'is_unique[users.email,id,'. $id .']',
                ]
            ],
            'username' => [
                'label' => 'Username',
                'rules' => [
                    'required', 'string',
                    'regex_match[/^[a-zA-Z0-9_-]+$/]',
                    'max_length[255]',
                    'is_unique[users.username,id,' . $id . ']'
                ]
            ],
            'password' => [
                'label' => 'Kata Sandi',
                'rules' => [
                    'permit_empty', 'string',
                ]
            ],
            'role' => [
                'label' => 'Peran',
                'rules' => [
                    'required',
                    'in_list[admin,teacher,student]'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()
                    ->back()
                    ->with('validationError', $this->validator->getErrors())
                    ->withInput();
        }

        $input = $this->validator->getValidated();

        if ($input['password'] === '') {
            unset($input['password']);
        } else {
            $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        $userModel = model('UserModel');

        $userModel->where('id', $id)
                    ->set($input)
                    ->update();

        return redirect('admin.users.index')
                ->with('success', 'Berhasil mengubah pengguna.');
    }

    public function delete($id)
    {
        $userModel = model('UserModel');

        $record = $userModel->select('id, full_name, email, username, role')
                            ->where('id', $id)
                            ->where('id !=', session('user')['id'])
                            ->first();

        if ($record === null) {
            return redirect()
                    ->back()
                    ->with('error', 'Pengguna tidak ditemukan.');
        }

        $userModel->delete($id);

        return redirect('admin.users.index')
                ->with('success', 'Berhasil menghapus pengguna.');
    }
}
