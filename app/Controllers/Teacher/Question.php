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

        $page = intval($this->request->getGet('page'));

        if ($page < 1) {
            $page = 1;
        }

        $limit = 10;

        $offset = $page > 1 ? ($page * $limit) - $limit : 0;

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

        return $this->response
                    ->setJSON($response);
    }

    public function create(): string {
        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('teacher/question/create', $data);
    }
}
