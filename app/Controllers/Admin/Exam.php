<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Exam extends BaseController
{
    public function index(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        $data['keyword'] = strval($this->request->getGet('kata_kunci'));

        $data['orderBy'] = strval($this->request->getGet('urut_dengan'));

        $data['direction'] = strval($this->request->getGet('arah_urut'));

        $data['page'] = intval($this->request->getGet('halaman'));

        if ($data['page'] < 1) {
            $data['page'] = 1;
        }

        $limit = 10;

        $data['offset'] = $data['page'] > 1 ?
                        ($data['page'] * $limit) - $limit
                        : 0;

        $examModel = model('ExamModel');

        $data['records'] = $examModel->select('id, title, start_time, end_time');

        if ($data['keyword'] !== '') {
            $data['records'] = $data['records']->like('title', $data['keyword']);
        }

        $orderColumn = ['title', 'start_time', 'end_time'];

        $orderBy = in_array($data['orderBy'], $orderColumn) ?
                    $data['orderBy'] :
                    'title';

        $direction = $data['direction'] === '' ?
                        'ASC' :
                        (
                            $data['direction'] === 'ASC' || $data['direction'] === 'DESC' ?
                                $data['direction'] :
                                'ASC'
                        );

        $data['records'] = $data['records']->orderBy($orderBy, $direction)
                                            ->findAll($limit, $data['offset']);

        $data['totalRecords'] = $examModel->select('id');

        if ($data['keyword'] !== '') {
            $data['totalRecords'] = $data['totalRecords']->like('title', $data['keyword']);
        }

        $data['totalRecords'] = $data['totalRecords']->countAllResults();

        $data['orderOptions'] = [];

        $data['orderOption'][] = [
            'text' => 'Sortir',
            'link' => url_to('admin.exams.index'),
            'active' => $data['orderBy'] === '' ? 1 : 0,
        ];

        $data['orderOption'][] = [
            'text' => 'Judul (A-Z)',
            'link' => url_to('admin.exams.index')
                        . '?urut_dengan=title&arah_urut=ASC',
            'active' => $data['orderBy'] === 'title'
                        && $data['direction'] === 'ASC'
                        ? 1
                        : 0,
        ];

        $data['orderOption'][] = [
            'text' => 'Judul (Z-A)',
            'link' => url_to('admin.exams.index')
                        . '?urut_dengan=title&arah_urut=DESC',
            'active' => $data['orderBy'] === 'title'
                        && $data['direction'] === 'DESC'
                        ? 1
                        : 0,
        ];

        $data['orderOption'][] = [
            'text' => 'Waktu Mulai (A-Z)',
            'link' => url_to('admin.exams.index')
                        . '?urut_dengan=start_time&arah_urut=ASC',
            'active' => $data['orderBy'] === 'start_time'
                        && $data['direction'] === 'ASC'
                        ? 1
                        : 0,
        ];

        $data['orderOption'][] = [
            'text' => 'Waktu Mulai (Z-A)',
            'link' => url_to('admin.exams.index')
                        . '?urut_dengan=start_time&arah_urut=DESC',
            'active' => $data['orderBy'] === 'start_time'
                        && $data['direction'] === 'DESC'
                        ? 1
                        : 0,
        ];

        $data['orderOption'][] = [
            'text' => 'Waktu Selesai (A-Z)',
            'link' => url_to('admin.exams.index')
                        . '?urut_dengan=end_time&arah_urut=ASC',
            'active' => $data['orderBy'] === 'end_time'
                        && $data['direction'] === 'ASC'
                        ? 1
                        : 0,
        ];

        $data['orderOption'][] = [
            'text' => 'Waktu Selesai (Z-A)',
            'link' => url_to('admin.exams.index')
                        . '?urut_dengan=end_time&arah_urut=DESC',
            'active' => $data['orderBy'] === 'end_time'
                        && $data['direction'] === 'DESC'
                        ? 1
                        : 0,
        ];

        if ($data['keyword'] !== '') {
            foreach ($data['orderOption'] as $key => $value) {
                if ($key === 0) {
                    $data['orderOption'][$key]['link'] .= '?kata_kunci='
                                                            . $data['keyword'];
                } else {
                    $data['orderOption'][$key]['link'] .= '&kata_kunci='
                                                            . $data['keyword'];
                }
            }
        }

        if (!empty($data['records'])) {
            $totalPages = intval(ceil($data['totalRecords'] / $limit));
    
            $previousPage = $data['page'] - 1;
    
            $nextPage = $data['page'] + 1;
    
            $data['pageItems'] = [];
    
            if ($data['page'] < 2) {
                $data['pageItems'][] = [
                    'text' => '1',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $data['page'],
                    'active' => 1
                ];
    
                for ($i=$nextPage; $i <= $totalPages; $i++) { 
                    if (count($data['pageItems']) > 5) {
                        break;
                    }
    
                    if ($i === $nextPage || $i === $nextPage + 1) {
                        $data['pageItems'][] = [
                            'text' => $i,
                            'link' => url_to('admin.exams.index') . '?halaman=' . $i,
                        ];
                    } else {
                        $data['pageItems'][] = [
                            'text' => $i,
                            'link' => url_to('admin.exams.index') . '?halaman=' . $i,
                            'secondary' => 1,
                        ];
                    }
                }
                
                if ($totalPages > 1) {
                    $data['pageItems'][] = [
                        'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                        'link' => url_to('admin.exams.index') . '?halaman=' . $data['page'] + 1,
                        'next' => 1
                    ];
        
                    $data['pageItems'][] = [
                        'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                        'link' => url_to('admin.exams.index') . '?halaman=' . $totalPages,
                        'last' => 1
                    ];
                }

                $data['pageItems'] = $this->addParametersToPageItems(
                    $data['pageItems'], $data['keyword'],
                    $data['orderBy'], $data['direction']
                );
            } else if ($data['page'] === 2) {
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'first' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'previous' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => '1',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                ];
    
                $data['pageItems'][] = [
                    'text' => '2',
                    'link' => url_to('admin.exams.index') . '?halaman=2',
                    'active' => 1,
                ];
    
                for ($i=$nextPage; $i <= $totalPages; $i++) { 
                    if (count($data['pageItems']) > 6) {
                        break;
                    }
    
                    if ($i === $nextPage) {
                        $data['pageItems'][] = [
                            'text' => $i,
                            'link' => url_to('admin.exams.index') . '?halaman=' . $i,
                        ];
                    } else {
                        $data['pageItems'][] = [
                            'text' => $i,
                            'link' => url_to('admin.exams.index') . '?halaman=' . $i,
                            'secondary' => 1,
                        ];
                    }
    
                }
    
                if ($data['page'] !== $totalPages) {
                     $data['pageItems'][] = [
                        'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                        'link' => url_to('admin.exams.index') . '?halaman=' . $data['page'] + 1,
                        'next' => 1
                    ];
    
                    $data['pageItems'][] = [
                        'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                        'link' => url_to('admin.exams.index') . '?halaman=' . $totalPages,
                        'last' => 1
                    ];
                }
    
                $data['pageItems'] = $this->addParametersToPageItems(
                    $data['pageItems'], $data['keyword'],
                    $data['orderBy'], $data['direction']
                );
            } else if ($data['page'] === $totalPages - 1) {
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'first' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                    'previous' => 1,
                ];
    
                $secondBeforePage = $data['page'] - 2;
    
                $data['pageItems'][] = [
                    'text' => $secondBeforePage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $secondBeforePage,
                    'secondary' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => $previousPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                ];
    
                $data['pageItems'][] = [
                    'text' => $data['page'],
                    'link' => url_to('admin.exams.index') . '?halaman=' . $data['page'],
                    'active' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => $nextPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $nextPage,
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $nextPage,
                    'next' => 1
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $totalPages,
                    'last' => 1
                ];
    
               $data['pageItems'] = $this->addParametersToPageItems(
                    $data['pageItems'], $data['keyword'],
                    $data['orderBy'], $data['direction']
                );
            } else if ($data['page'] === $totalPages) {
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'first' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                    'previous' => 1,
                ];
    
                $secondBeforePage = $data['page'] - 2;
    
                $data['pageItems'][] = [
                    'text' => $secondBeforePage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $secondBeforePage,
                    'secondary' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => $previousPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                ];
    
                $data['pageItems'][] = [
                    'text' => $data['page'],
                    'link' => url_to('admin.exams.index') . '?halaman=' . $data['page'],
                    'active' => 1,
                ];
    
                $data['pageItems'] = $this->addParametersToPageItems(
                    $data['pageItems'], $data['keyword'],
                    $data['orderBy'], $data['direction']
                );
            } else {
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'first' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                    'previous' => 1,
                ];
    
                $secondBeforePage = $data['page'] - 2;
    
                $data['pageItems'][] = [
                    'text' => $secondBeforePage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $secondBeforePage,
                    'secondary' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => $previousPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                ];
    
                $data['pageItems'][] = [
                    'text' => $data['page'],
                    'link' => url_to('admin.exams.index') . '?halaman=' . $data['page'],
                    'active' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => $nextPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $nextPage,
                ];
    
                $secondAfter = $data['page'] + 2;
    
                $data['pageItems'][] = [
                    'text' => $secondAfter,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $secondAfter,
                    'secondary' => 1,
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $nextPage,
                    'next' => 1
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $totalPages,
                    'last' => 1
                ];
    
                $data['pageItems'] = $this->addParametersToPageItems(
                    $data['pageItems'], $data['keyword'],
                    $data['orderBy'], $data['direction']
                );
            }
        }


        return view('admin/exam/index', $data);
    }

    private function addParametersToPageItems($pageItems, $keyword, $orderBy, $direction): array
    {
        if ($keyword !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['link'] .= '&kata_kunci='
                                                    . $keyword;
            }
        }

        if ($orderBy !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['link'] .= '&urutkan_dengan='
                                                        . $orderBy;
            }
        }

        if ($direction !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['link'] .= '&arah_urut='
                                                        . $direction;
            }
        }

        return $pageItems;
    }

    public function create(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('admin/exam/create', $data);
    }

    public function store()
    {
        $controller = $this;

        $validationRules = [
            'id' => [
                'label' => 'Id',
                'rules' => [
                    'required', 'string',
                    'regex_match[/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/]',
                    'is_unique[exams.id]'
                ]
            ],
            'title' => [
                'label' => 'Judul',
                'rules' => [
                    'required', 'string',
                    'max_length[255]',
                    static function (string $value, array $data, ?string &$error) use ($controller): bool {
                        $examModel = model('ExamModel');

                        $exam = $examModel->select('id')
                                            ->where('slug', $controller->slugify($value))
                                            ->limit(1)
                                            ->first();

                        return $exam === null;
                    }
                ],
                'errors' => [
                    3 => 'Judul tidak bisa dipakai karena sudah dipakai dalam format ramah URL.'
                ]
            ],
            'start_time' => [
                'label' => 'Waktu Mulai',
                'rules' => [
                    'required', 'string',
                    static function ($value) {
                        $format = 'Y-m-d\TH:i';

                        $d = \DateTime::createFromFormat($format, $value);

                        return $d && $d->format($format) === $value;
                    }
                ],
                'errors' => [
                    2 => 'Waktu Mulai tidak berformat dengan benar.'
                ]
            ],
            'start_time' => [
                'label' => 'Waktu Mulai',
                'rules' => [
                    'required', 'string',
                    static function (string $value, array $data, ?string &$error): bool {
                        $format = 'Y-m-d\TH:i';

                        $d = \DateTime::createFromFormat($format, $value);

                        return $d && $d->format($format) === $value;
                    },
                ],
                'errors' => [
                    2 => 'Waktu Mulai tidak berformat dengan benar.',
                ]
            ],
            'end_time' => [
                'label' => 'Waktu Mulai',
                'rules' => [
                    'required', 'string',
                    static function (string $value, array $data, ?string &$error): bool {
                        $format = 'Y-m-d\TH:i';

                        $d = \DateTime::createFromFormat($format, $value);

                        return $d && $d->format($format) === $value;
                    },
                    static function (string $value, array $data, ?string &$error) {
                        $format = 'Y-m-d\TH:i';

                        $d = \DateTime::createFromFormat($format, $data['start_time']);

                        if (!$d || $d->format($format) !== $data['start_time']) {
                            return true;
                        }

                        return strtotime($value) > strtotime($data['start_time']);
                    }
                ],
                'errors' => [
                    2 => 'Waktu Selesai tidak berformat dengan benar.',
                    3 => 'Waktu Selesai harus lebih besar dari Waktu Mulai.',
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

        $input['slug'] = $this->slugify($input['title']);
        $input['start_time'] = strtotime($input['start_time']);
        $input['end_time'] = strtotime($input['end_time']);

        $examModel = model('ExamModel');

        $examModel->insert($input);

        return redirect('admin.exams.create')
                ->with('success', 'Berhasil menambahkan ujian.');
    }

    public function edit($id)
    {
        $examModel = model('ExamModel');

        $data['record'] = $examModel->select('id, title, start_time, end_time')
                                    ->limit(1)
                                    ->find($id);

        if ($data['record'] === null) {
            return redirect()
                    ->back()
                    ->with('error', 'Ujian Tidak Ditemukan.');
        }

        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('admin/exam/edit', $data);
    }

    public function update($id)
    {
        $examModel = model('ExamModel');

        $record = $examModel->select('id, title, start_time, end_time')
                            ->limit(1)
                            ->find($id);

        if ($record === null) {
            return redirect()
                    ->back()
                    ->with('error', 'Ujian Tidak Ditemukan.');
        }

        $controller = $this;

        $validationRules = [
            'title' => [
                'label' => 'Judul',
                'rules' => [
                    'required', 'string',
                    'max_length[255]',
                    static function (string $value, array $data, ?string &$error) use ($controller, $id): bool {
                        $examModel = model('ExamModel');

                        $exam = $examModel->select('id')
                                            ->where('slug', $controller->slugify($value))
                                            ->where('id !=', $id)
                                            ->limit(1)
                                            ->first();

                        return $exam === null;
                    }
                ],
                'errors' => [
                    3 => 'Judul tidak bisa dipakai karena sudah dipakai dalam format ramah URL.'
                ]
            ],
            'start_time' => [
                'label' => 'Waktu Mulai',
                'rules' => [
                    'required', 'string',
                    static function ($value) {
                        $format = 'Y-m-d\TH:i';

                        $d = \DateTime::createFromFormat($format, $value);

                        return $d && $d->format($format) === $value;
                    }
                ],
                'errors' => [
                    2 => 'Waktu Mulai tidak berformat dengan benar.'
                ]
            ],
            'start_time' => [
                'label' => 'Waktu Mulai',
                'rules' => [
                    'required', 'string',
                    static function (string $value, array $data, ?string &$error): bool {
                        $format = 'Y-m-d\TH:i';

                        $d = \DateTime::createFromFormat($format, $value);

                        return $d && $d->format($format) === $value;
                    },
                ],
                'errors' => [
                    2 => 'Waktu Mulai tidak berformat dengan benar.',
                ]
            ],
            'end_time' => [
                'label' => 'Waktu Mulai',
                'rules' => [
                    'required', 'string',
                    static function (string $value, array $data, ?string &$error): bool {
                        $format = 'Y-m-d\TH:i';

                        $d = \DateTime::createFromFormat($format, $value);

                        return $d && $d->format($format) === $value;
                    },
                    static function (string $value, array $data, ?string &$error) {
                        $format = 'Y-m-d\TH:i';

                        $d = \DateTime::createFromFormat($format, $data['start_time']);

                        if (!$d || $d->format($format) !== $data['start_time']) {
                            return true;
                        }

                        return strtotime($value) > strtotime($data['start_time']);
                    }
                ],
                'errors' => [
                    2 => 'Waktu Selesai tidak berformat dengan benar.',
                    3 => 'Waktu Selesai harus lebih besar dari Waktu Mulai.',
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

        $input['slug'] = $this->slugify($input['title']);
        $input['start_time'] = strtotime($input['start_time']);
        $input['end_time'] = strtotime($input['end_time']);

        $examModel->where('id', $id)
                    ->set($input)
                    ->update();

        return redirect('admin.exams.index')
                ->with('success', 'Berhasil mengubah ujian.');
    }

    public function delete($id)
    {
        $examModel = model('ExamModel');

        $record = $examModel->select('id')
                            ->where('id', $id)
                            ->first();

        if ($record === null) {
            return redirect()
                    ->back()
                    ->with('error', 'Ujian tidak ditemukan.');
        }

        $examModel->delete($id);

        return redirect('admin.exams.index')
                ->with('success', 'Berhasil menghapus ujian.');
    }

    private function slugify(string $text): string
    {
         // Ubah ke huruf kecil
        $text = strtolower($text);

        // Ganti karakter non huruf/angka dengan tanda -
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);

        // Hapus tanda - di awal/akhir
        $text = trim($text, '-');

        // Kembalikan hasil
        return $text ?: '';    
    }
}
