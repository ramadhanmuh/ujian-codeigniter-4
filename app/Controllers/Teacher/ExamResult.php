<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;

class ExamResult extends BaseController
{
    public function index()
    {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        $examModel = model('ExamModel');

        $data['exams'] = $examModel->select('id, title')
                                    ->orderBy('title', 'ASC')
                                    ->findAll();

        $data['keyword'] = strval($this->request->getGet('keyword'));

        $data['orderBy'] = strval($this->request->getGet('orderBy'));

        if (in_array($data['orderBy'], ['student', 'exam'])) {
            $orderBy = $data['orderBy'] === 'student' ? 'users.full_name' : 'exams.title';
        } else {
            $orderBy = 'users.full_name';
        }

        $data['direction'] = strval($this->request->getGet('direction'));

        if ($data['direction'] !== 'asc' && $data['direction'] !== 'desc') {
            $direction = 'asc';
        } else {
            $direction = $data['direction'];
        }

        $data['exam_id'] = strval($this->request->getGet('exam_id'));

        $data['page'] = intval($this->request->getGet('halaman'));

        if ($data['page'] < 1) {
            $data['page'] = 1;
        }

        $limit = 10;

        $data['offset'] = $data['page'] > 1 ?
                        ($data['page'] * $limit) - $limit
                        : 0;

        $examResultModel = model('ExamResultModel');

        $data['records'] = $examResultModel->select('exam_results.score, exams.title, users.full_name')
                                            ->join('exams', 'exams.id = exam_results.exam_id', 'inner')
                                            ->join('users', 'users.id = exam_results.user_id', 'inner')
                                            ->where('users.role', 'student');

        if ($data['exam_id'] !== '') {
            $data['records'] = $data['records']->where('exams.id', $data['exam_id']);
        }

        if ($data['keyword'] !== '') {
            $data['records'] = $data['records']
                                ->groupStart()
                                ->like('exams.title', $data['keyword'])
                                ->orLike('users.full_name', $data['keyword'])
                                ->groupEnd();
        }

        $data['records'] = $data['records']->orderBy($orderBy, $direction)
                                            ->findAll($limit, $data['offset']);

        $data['totalRecords'] = $examResultModel->select('exam_results.id')
                                                ->join('exams', 'exams.id = exam_results.exam_id', 'inner')
                                                ->join('users', 'users.id = exam_results.user_id', 'inner')
                                                ->where('users.role', 'student');

        if ($data['exam_id'] !== '') {
            $data['totalRecords'] = $data['totalRecords']->where('exams.id', $data['exam_id']);
        }

        if ($data['keyword'] !== '') {
            $data['totalRecords'] = $data['totalRecords']
                                ->groupStart()
                                ->like('exams.title', $data['keyword'])
                                ->orLike('users.full_name', $data['keyword'])
                                ->groupEnd();
        }

        $data['totalRecords'] = $data['totalRecords']->countAllResults();

        $totalPages = intval(ceil($data['totalRecords'] / $limit));

        $previousPage = $data['page'] - 1;

        $nextPage = $data['page'] + 1;

        $data['pageItems'] = [];

        if ($data['page'] < 2) {
            $data['pageItems'][] = [
                'text' => '1',
                'link' => url_to('student.exam-results.index') . '?halaman=' . $data['page'],
                'active' => 1
            ];

            for ($i=$nextPage; $i <= $totalPages; $i++) { 
                if (count($data['pageItems']) > 5) {
                    break;
                }

                if ($i === $nextPage || $i === $nextPage + 1) {
                    $data['pageItems'][] = [
                        'text' => $i,
                        'link' => url_to('student.exam-results.index') . '?halaman=' . $i,
                    ];
                } else {
                    $data['pageItems'][] = [
                        'text' => $i,
                        'link' => url_to('student.exam-results.index') . '?halaman=' . $i,
                        'secondary' => 1,
                    ];
                }
            }

            if ($totalPages > 1) {
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                    'link' => url_to('student.exam-results.index') . '?halaman=' . $data['page'] + 1,
                    'next' => 1
                ];
    
                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                    'link' => url_to('student.exam-results.index') . '?halaman=' . $totalPages,
                    'last' => 1
                ];
            }

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword'], $data['orderBy'],
                $data['direction'], $data['exam_id']
            );
        } else if ($data['page'] === 2) {
            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=1',
                'first' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=1',
                'previous' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '1',
                'link' => url_to('student.exam-results.index') . '?halaman=1',
            ];

            $data['pageItems'][] = [
                'text' => '2',
                'link' => url_to('student.exam-results.index') . '?halaman=2',
                'active' => 1,
            ];

            for ($i=$nextPage; $i <= $totalPages; $i++) { 
                if (count($data['pageItems']) > 6) {
                    break;
                }

                if ($i === $nextPage) {
                    $data['pageItems'][] = [
                        'text' => $i,
                        'link' => url_to('student.exam-results.index') . '?halaman=' . $i,
                    ];
                } else {
                    $data['pageItems'][] = [
                        'text' => $i,
                        'link' => url_to('student.exam-results.index') . '?halaman=' . $i,
                        'secondary' => 1,
                    ];
                }

            }

            if ($data['page'] !== $totalPages) {
                 $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                    'link' => url_to('student.exam-results.index') . '?halaman=' . $data['page'] + 1,
                    'next' => 1
                ];

                $data['pageItems'][] = [
                    'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                    'link' => url_to('student.exam-results.index') . '?halaman=' . $totalPages,
                    'last' => 1
                ];
            }

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword'], $data['orderBy'],
                $data['direction'], $data['exam_id']
            );
        } else if ($data['page'] === $totalPages - 1) {
            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=1',
                'first' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=' . $previousPage,
                'previous' => 1,
            ];

            $secondBeforePage = $data['page'] - 2;

            $data['pageItems'][] = [
                'text' => $secondBeforePage,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $secondBeforePage,
                'secondary' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $previousPage,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $previousPage,
            ];

            $data['pageItems'][] = [
                'text' => $data['page'],
                'link' => url_to('student.exam-results.index') . '?halaman=' . $data['page'],
                'active' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $nextPage,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $nextPage,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=' . $nextPage,
                'next' => 1
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=' . $totalPages,
                'last' => 1
            ];

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword'], $data['orderBy'],
                $data['direction'], $data['exam_id']
            );
        } else if ($data['page'] === $totalPages) {
            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=1',
                'first' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=' . $previousPage,
                'previous' => 1,
            ];

            $secondBeforePage = $data['page'] - 2;

            $data['pageItems'][] = [
                'text' => $secondBeforePage,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $secondBeforePage,
                'secondary' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $previousPage,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $previousPage,
            ];

            $data['pageItems'][] = [
                'text' => $data['page'],
                'link' => url_to('student.exam-results.index') . '?halaman=' . $data['page'],
                'active' => 1,
            ];

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword'], $data['orderBy'],
                $data['direction'], $data['exam_id']
            );
        } else {
            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-left"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=1',
                'first' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-left"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=' . $previousPage,
                'previous' => 1,
            ];

            $secondBeforePage = $data['page'] - 2;

            $data['pageItems'][] = [
                'text' => $secondBeforePage,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $secondBeforePage,
                'secondary' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $previousPage,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $previousPage,
            ];

            $data['pageItems'][] = [
                'text' => $data['page'],
                'link' => url_to('student.exam-results.index') . '?halaman=' . $data['page'],
                'active' => 1,
            ];

            $data['pageItems'][] = [
                'text' => $nextPage,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $nextPage,
            ];

            $secondAfter = $data['page'] + 2;

            $data['pageItems'][] = [
                'text' => $secondAfter,
                'link' => url_to('student.exam-results.index') . '?halaman=' . $secondAfter,
                'secondary' => 1,
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevron-right"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=' . $nextPage,
                'next' => 1
            ];

            $data['pageItems'][] = [
                'text' => '<i class="tf-icon bx bx-chevrons-right"></i>',
                'link' => url_to('student.exam-results.index') . '?halaman=' . $totalPages,
                'last' => 1
            ];

            $data['pageItems'] = $this->addParameterToPageItems(
                $data['pageItems'], $data['keyword'], $data['orderBy'],
                $data['direction'], $data['exam_id']
            );
        }

        return view('teacher/exam_result', $data);
    }

    private function addParameterToPageItems($pageItems, $keyword, $orderBy, $direction, $exam_id): array
    {
        if ($keyword !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['link'] .= '&keyword='
                                                    . $keyword;
            }
        }

        if ($orderBy !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['link'] .= '&orderBy='
                                                    . $orderBy;
            }
        }

        if ($direction !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['link'] .= '&direction='
                                                    . $direction;
            }
        }

        if ($exam_id !== '') {
            foreach ($pageItems as $key => $value) {
                $pageItems[$key]['link'] .= '&exam_id='
                                                    . $exam_id;
            }
        }

        return $pageItems;
    }
}
