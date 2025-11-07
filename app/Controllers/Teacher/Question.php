<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;

class Question extends BaseController
{
    public function index(): string
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        $examModel = model('ExamModel');

        $data['exams'] = $examModel->select('id, title')
                                    ->orderBy('title', 'ASC')
                                    ->findAll();

        return view('teacher/question/index', $data);
    }

    public function list() {
        $orderBy = strval($this->request->getGet('orderBy'));

        if ($orderBy !== 'text') {
            $orderBy = 'text';
        }

        $direction = $this->request->getGet('direction');

        if ($direction !== 'ASC' || $direction !== 'DESC') {
            $direction = 'ASC';
        }

        $keyword = strval($this->request->getGet('keyword'));

        $exam_id = strval($this->request->getGet('exam_id'));

        $response['page'] = intval($this->request->getGet('page'));

        if ($response['page'] < 1) {
            $response['page'] = 1;
        }

        $limit = 10;

        $offset = $response['page'] > 1 ? ($response['page'] * $limit) - $limit : 0;

        $questionModel = model('QuestionModel');

        $response['records'] = $questionModel->select(
            'id, text, option_a, option_b, option_c, option_d, correct_answer'
        )->where('exam_id', $exam_id);

        if ($keyword !== '') {
            $response['records'] = $response['records']->groupStart()
                                                        ->like('text', $keyword)
                                                        ->orLike('option_a', $keyword)
                                                        ->orLike('option_b', $keyword)
                                                        ->orLike('option_c', $keyword)
                                                        ->orLike('option_d', $keyword)
                                                        ->groupEnd();
        }

        $response['records'] = $response['records']->orderBy($orderBy, $direction)
                                                    ->findAll($limit, $offset);

        if (!empty($response['records'])) {
            foreach ($response['records'] as $key => $value) {
                $offset++;

                $response['records'][$key]['number'] = $offset;
                
                $response['records'][$key]['edit_link'] = url_to(
                    'teacher.questions.edit', $value['id']
                );

                $response['records'][$key]['delete_link'] = url_to(
                    'teacher.questions.delete', $value['id']
                );

                $response['records'][$key]['csrf'] = csrf_field();
            }
        }

        $response['total'] = $questionModel->select('id')
                                ->where('exam_id', $exam_id);

        if ($keyword !== '') {
            $response['total'] = $response['total']->groupStart()
                                                    ->like('text', $keyword)
                                                    ->orLike('option_a', $keyword)
                                                    ->orLike('option_b', $keyword)
                                                    ->orLike('option_c', $keyword)
                                                    ->orLike('option_d', $keyword)
                                                    ->groupEnd();
        }
            
        $response['total'] = $response['total']->countAllResults();

        $totalPages = intval(ceil($response['total'] / $limit));

        $previousPage = $response['page'] - 1;

        $nextPage = $response['page'] + 1;

        $response['pageItems'] = [];

        if (!empty($response['records'])) {
            $totalPages = intval(ceil($response['total'] / $limit));
    
            $previousPage = $response['page'] - 1;
    
            $nextPage = $response['page'] + 1;
    
            $response['pageItems'] = [];
    
            if ($response['page'] < 2) {
                $response['pageItems'][] = [
                    'text' => '1',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $response['page'],
                    'active' => 1
                ];
    
                for ($i=$nextPage; $i <= $totalPages; $i++) { 
                    if (count($response['pageItems']) > 5) {
                        break;
                    }
    
                    if ($i === $nextPage || $i === $nextPage + 1) {
                        $response['pageItems'][] = [
                            'text' => $i,
                            'link' => url_to('admin.exams.index') . '?halaman=' . $i,
                        ];
                    } else {
                        $response['pageItems'][] = [
                            'text' => $i,
                            'link' => url_to('admin.exams.index') . '?halaman=' . $i,
                            'secondary' => 1,
                        ];
                    }
                }
                
                if ($totalPages > 1) {
                    $response['pageItems'][] = [
                        'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                        'link' => url_to('admin.exams.index') . '?halaman=' . $response['page'] + 1,
                        'next' => 1
                    ];
        
                    $response['pageItems'][] = [
                        'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                        'link' => url_to('admin.exams.index') . '?halaman=' . $totalPages,
                        'last' => 1
                    ];
                }

                $response['pageItems'] = $this->addParametersToPageItems(
                    $response['pageItems'], $keyword,
                    $orderBy, $direction
                );
            } else if ($response['page'] === 2) {
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'first' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'previous' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => '1',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                ];
    
                $response['pageItems'][] = [
                    'text' => '2',
                    'link' => url_to('admin.exams.index') . '?halaman=2',
                    'active' => 1,
                ];
    
                for ($i=$nextPage; $i <= $totalPages; $i++) { 
                    if (count($response['pageItems']) > 6) {
                        break;
                    }
    
                    if ($i === $nextPage) {
                        $response['pageItems'][] = [
                            'text' => $i,
                            'link' => url_to('admin.exams.index') . '?halaman=' . $i,
                        ];
                    } else {
                        $response['pageItems'][] = [
                            'text' => $i,
                            'link' => url_to('admin.exams.index') . '?halaman=' . $i,
                            'secondary' => 1,
                        ];
                    }
    
                }
    
                if ($response['page'] !== $totalPages) {
                     $response['pageItems'][] = [
                        'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                        'link' => url_to('admin.exams.index') . '?halaman=' . $response['page'] + 1,
                        'next' => 1
                    ];
    
                    $response['pageItems'][] = [
                        'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                        'link' => url_to('admin.exams.index') . '?halaman=' . $totalPages,
                        'last' => 1
                    ];
                }
    
                $response['pageItems'] = $this->addParametersToPageItems(
                    $response['pageItems'], $keyword,
                    $orderBy, $direction
                );
            } else if ($response['page'] === $totalPages - 1) {
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'first' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                    'previous' => 1,
                ];
    
                $secondBeforePage = $response['page'] - 2;
    
                $response['pageItems'][] = [
                    'text' => $secondBeforePage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $secondBeforePage,
                    'secondary' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => $previousPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                ];
    
                $response['pageItems'][] = [
                    'text' => $response['page'],
                    'link' => url_to('admin.exams.index') . '?halaman=' . $response['page'],
                    'active' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => $nextPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $nextPage,
                ];
    
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $nextPage,
                    'next' => 1
                ];
    
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $totalPages,
                    'last' => 1
                ];
    
               $response['pageItems'] = $this->addParametersToPageItems(
                    $response['pageItems'], $keyword,
                    $orderBy, $direction
                );
            } else if ($response['page'] === $totalPages) {
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'first' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                    'previous' => 1,
                ];
    
                $secondBeforePage = $response['page'] - 2;
    
                $response['pageItems'][] = [
                    'text' => $secondBeforePage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $secondBeforePage,
                    'secondary' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => $previousPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                ];
    
                $response['pageItems'][] = [
                    'text' => $response['page'],
                    'link' => url_to('admin.exams.index') . '?halaman=' . $response['page'],
                    'active' => 1,
                ];
    
                $response['pageItems'] = $this->addParametersToPageItems(
                    $response['pageItems'], $keyword,
                    $orderBy, $direction
                );
            } else {
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=1',
                    'first' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                    'previous' => 1,
                ];
    
                $secondBeforePage = $response['page'] - 2;
    
                $response['pageItems'][] = [
                    'text' => $secondBeforePage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $secondBeforePage,
                    'secondary' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => $previousPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $previousPage,
                ];
    
                $response['pageItems'][] = [
                    'text' => $response['page'],
                    'link' => url_to('admin.exams.index') . '?halaman=' . $response['page'],
                    'active' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => $nextPage,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $nextPage,
                ];
    
                $secondAfter = $response['page'] + 2;
    
                $response['pageItems'][] = [
                    'text' => $secondAfter,
                    'link' => url_to('admin.exams.index') . '?halaman=' . $secondAfter,
                    'secondary' => 1,
                ];
    
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $nextPage,
                    'next' => 1
                ];
    
                $response['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                    'link' => url_to('admin.exams.index') . '?halaman=' . $totalPages,
                    'last' => 1
                ];
    
                $response['pageItems'] = $this->addParametersToPageItems(
                    $response['pageItems'], $keyword,
                    $orderBy, $direction
                );
            }
        }

        return $this->response
                    ->setJSON($response);
    }

    private function addParametersToPageItems($pageItems, $keyword, $orderBy, $direction): array
    {
        if ($keyword !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['keyword'] = $keyword;
            }
        }

        if ($orderBy !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['orderBy'] = $orderBy;
            }
        }

        if ($direction !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['direction'] = $direction;
            }
        }

        return $pageItems;
    }

    public function create() {
        $examId = $this->request->getGet('exam_id');

        $examModel = model('examModel');

        $data['exam'] = $examModel->select('title')
                                    ->limit(1)
                                    ->find($examId);

        if ($data['exam'] === null) {
            return redirect('teacher.questions.index')
                    ->with('error', 'Ujian tidak ditemukan.');
        }

        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('teacher/question/create', $data);
    }

    public function store() {
        $examId = $this->request->getGet('exam_id');

        $examModel = model('examModel');

        $exam = $examModel->select('title')
                                    ->limit(1)
                                    ->find($examId);

        if ($exam === null) {
            return redirect('teacher.questions.index')
                    ->with('error', 'Ujian tidak ditemukan.');
        }

        $validationRules = [
            'id' => [
                'label' => 'Id',
                'rules' => [
                    'required', 'string',
                    'regex_match[/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/]',
                    'is_unique[questions.id]'
                ]
            ],
            'text' => [
                'label' => 'Teks Pertanyaan',
                'rules' => [
                    'required', 'string',
                    'max_length[65535]'
                ]
            ],
            'option_a' => [
                'label' => 'Pilihan A',
                'rules' => [
                    'required', 'string',
                    'max_length[65535]'
                ]
            ],
            'option_b' => [
                'label' => 'Pilihan B',
                'rules' => [
                    'required', 'string',
                    'max_length[65535]'
                ]
            ],
            'option_c' => [
                'label' => 'Pilihan C',
                'rules' => [
                    'required', 'string',
                    'max_length[65535]'
                ]
            ],
            'option_d' => [
                'label' => 'Pilihan D',
                'rules' => [
                    'required', 'string',
                    'max_length[65535]'
                ]
            ],
            'correct_answer' => [
                'label' => 'Jawaban Benar',
                'rules' => [
                    'required',
                    'in_list[a,b,c,d]'
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

        $input['exam_id'] = $examId;

        $questionModel = model('QuestionModel');

        $questionModel->insert($input);

        return redirect()
                ->back()
                ->with('success', 'Berhasil menambah soal.');
    }

    public function edit($id)
    {
        $questionModel = model('QuestionModel');

        $data['record'] = $questionModel->select('questions.*, exams.title')
                                        ->join('exams', 'exams.id = questions.exam_id', 'inner')
                                        ->limit(1)
                                        ->find($id);

        if ($data['record'] === null) {
            return redirect('teacher.questions.index')
                    ->with('error', 'Soal tidak ditemukan.');
        }

        return view('teacher.question.edit', $data);
    }

    public function delete($id)
    {
        $questionModel = model('QuestionModel');

        $record = $questionModel->limit(1)
                                ->find($id);

        if ($record === null) {
            return redirect('teacher.questions.index')
                    ->with('error', 'Soal tidak ditemukan.');
        }
        
        return 'test';
    }
}
