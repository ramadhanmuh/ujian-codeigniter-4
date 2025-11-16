<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;

class ExamResult extends BaseController
{
    public function index()
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

        $examResultModel = model('ExamResultModel');

        $data['records'] = $examResultModel->select('exam_results.score, exams.title, exams.start_time, exams.end_time')
                                            ->join('exams', 'exams.id = exam_results.exam_id', 'inner')
                                            ->where('exam_results.user_id', session('user')['id']);

        if ($data['keyword'] !== '') {
            $data['records'] = $data['records']->like('exams.title', $data['keyword']);
        }

        $data['records'] = $data['records']->orderBy('exams.start_time', 'desc')
                                            ->findAll($limit, $data['offset']);

        $data['totalRecords'] = $examResultModel->select('exam_results.id')
                                                ->join('exams', 'exams.id = exam_results.exam_id', 'inner')
                                                ->where('exam_results.user_id', session('user')['id']);

        if ($data['keyword'] !== '') {
            $data['totalRecords'] = $data['totalRecords']->like('exams.title', $data['keyword']);
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
                $data['pageItems'], $data['keyword']
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
                $data['pageItems'], $data['keyword']
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
                $data['pageItems'], $data['keyword']
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
                $data['pageItems'], $data['keyword']
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
                $data['pageItems'], $data['keyword']
            );
        }

        return view('student/exam_result', $data);
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
}
