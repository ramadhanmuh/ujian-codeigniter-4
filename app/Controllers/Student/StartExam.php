<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;

class StartExam extends BaseController
{
    public function index()
    {
        $now = time();

        $examModel = model('ExamModel');
        
        $examData = $examModel->select('id, slug')
                                ->where('start_time <=', $now)
                                ->where('end_time >=', $now)
                                ->first();

        if ($examData === null) {
            return redirect('student.home.index')
                    ->with('info', 'Ujian tidak ditemukan.');
        }

        $examResultModel = model('ExamResultModel');

        $examResultData = $examResultModel->select('id')
                                            ->where('user_id', session('user')['id'])
                                            ->where('exam_id', $examData['id'])
                                            ->first();

        if ($examResultData !== null) {
            return redirect('student.exam-results.index')
                    ->with('info', 'Ujian telah diselesaikan.');
        }

        $questionModel = model('QuestionModel');

        $questionData = $questionModel->select('id')
                                        ->where('exam_id', $examData['id'])
                                        ->orderBy('RAND()')
                                        ->findAll();

        if (empty($questionData)) {
            return redirect('student.home.index')
                    ->with('info', 'Soal ujian tidak ditemukan.');
        }

        $studentQuestionModel = model('StudentQuestionModel');

        $studentQuestionData = $studentQuestionModel
                                ->select('id')
                                ->where('user_id', session('user')['id'])
                                ->where('question_id', $questionData[0]['id'])
                                ->first();
    
        if ($studentQuestionData === null) {
            $studentQuestionData = [];
    
            $number = 1;
    
            foreach ($questionData as $question) {
                $studentQuestionData[] = [
                    'id' => generate_uuid(),
                    'user_id' => session('user')['id'],
                    'question_id' => $question['id'],
                    'number' => $number,
                ];
    
                $number++;
            }
    
            $studentQuestionModel->insertBatch($studentQuestionData);
        }


        return redirect()->route('student.start-exam.create', [
            $examData['slug'], 1
        ]);
    }

    public function create($slug, $number)
    {
        $now = time();

        $questionModel = model('QuestionModel');

        $data['question'] = $questionModel->select('questions.text, questions.option_a, questions.option_b, questions.option_c, questions.option_d, questions.exam_id, exams.title, exams.slug, exams.end_time, student_questions.number, student_questions.selected_option')
                                            ->join('student_questions', 'student_questions.question_id = questions.id', 'inner')
                                            ->join('exams', 'exams.id = questions.exam_id', 'inner')
                                            ->where('exams.slug', $slug)
                                            ->where('student_questions.number', $number)
                                            ->where('student_questions.user_id', session('user')['id'])
                                            ->where('exams.start_time <=', $now)
                                            ->where('exams.end_time >=', $now)
                                            ->first();

        if ($data['question'] === null) {
            return redirect('student.home.index')
                    ->with('error', 'Soal tidak ditemukan.');
        }

        $studentQuestionModel = model('StudentQuestionModel');

        $data['studentQuestionData'] = $studentQuestionModel
                                        ->join('questions', 'questions.id = student_questions.question_id', 'inner')
                                        ->where('student_questions.user_id', session('user')['id'])
                                        ->where('questions.exam_id', $data['question']['exam_id'])
                                        ->where('student_questions.selected_option IS NULL', null, false)
                                        ->findAll();

        if ($data['studentQuestionData'] === null) {
            return redirect('student.home.index')
                    ->with('error', 'Ujian sudah diselesaikan.');
        }

        $examResultModel = model('ExamResultModel');

        $examResultData = $examResultModel->select('id')
                                            ->where('user_id', session('user')['id'])
                                            ->where('exam_id', $data['question']['exam_id'])
                                            ->first();

        if ($examResultData !== null) {
            return redirect('student.exam-results.index')
                    ->with('info', 'Ujian telah diselesaikan.');
        }

        $data['maxNumber'] = $studentQuestionModel->select('student_questions.number')
                                                    ->join('questions', 'questions.id = student_questions.question_id', 'inner')
                                                    ->join('exams', 'exams.id = questions.exam_id', 'inner')
                                                    ->where('exams.slug', $slug)
                                                    ->where('student_questions.user_id', session('user')['id'])
                                                    ->orderBy('student_questions.number', 'DESC')
                                                    ->first();

        $data['maxNumber'] = $data['maxNumber']['number'];

        $applicationModel = model('ApplicationModel');

        $data['application'] = $applicationModel->first();

        return view('student/start_exam/create', $data);
    }

    public function store($slug, $number)
    {
        $now = time();

        $questionModel = model('QuestionModel');

        $question = $questionModel->select('questions.text, questions.option_a, questions.option_b, questions.option_c, questions.option_d, questions.exam_id, exams.title, exams.slug, exams.end_time, student_questions.number, student_questions.selected_option, student_questions.question_id')
                                    ->join('student_questions', 'student_questions.question_id = questions.id', 'inner')
                                    ->join('exams', 'exams.id = questions.exam_id', 'inner')
                                    ->where('exams.slug', $slug)
                                    ->where('student_questions.number', $number)
                                    ->where('student_questions.user_id', session('user')['id'])
                                    ->where('exams.start_time <=', $now)
                                    ->where('exams.end_time >=', $now)
                                    ->first();

        if ($question === null) {
            return redirect()
                    ->back()
                    ->with('error', 'Soal tidak ditemukan.');
        }

        $examResultModel = model('ExamResultModel');

        $examResultData = $examResultModel->select('id')
                                            ->where('user_id', session('user')['id'])
                                            ->where('exam_id', $question['exam_id'])
                                            ->first();

        if ($examResultData !== null) {
            return redirect('student.exam-results.index')
                    ->with('info', 'Ujian telah diselesaikan.');
        }

        $studentQuestionModel = model('StudentQuestionModel');

        $studentQuestionData = $studentQuestionModel
                                ->select('student_questions.id')
                                ->join('questions', 'questions.id = student_questions.question_id', 'inner')
                                ->where('student_questions.user_id', session('user')['id'])
                                ->where('questions.exam_id', $question['exam_id'])
                                ->where('student_questions.selected_option IS NULL', null, false)
                                ->first();

        if ($studentQuestionData === null) {
            return redirect('student.home.index')
                    ->with('error', 'Ujian sudah diselesaikan.');
        }

        $validationRules = [
            'selected_option' => [
                'label' => 'Opsi Yang Dipilih',
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

        $studentQuestionModel->where('question_id', $question['question_id'])
                            ->where('number', $number)
                            ->limit(1)
                            ->set($this->validator->getValidated())
                            ->update();

        return redirect()->back()
                            ->with('success', 'Berhasil menyimpan jawaban.');
    }

    public function finish($slug) {
        $now = time();

        $examModel = model('ExamModel');

        $examData = $examModel->select('id')
                                ->where('slug', $slug)
                                ->where('start_time <=', $now)
                                ->where('end_time >=', $now)
                                ->first();

        if ($examData === null) {
            return redirect('student.home.index')
                    ->with('error', 'Ujian tidak ditemukan.');
        }

        $examResultModel = model('ExamResultModel');

        $examResultData = $examResultModel->select('id')
                                            ->where('user_id', session('user')['id'])
                                            ->where('exam_id', $examData['id'])
                                            ->first();

        if ($examResultData !== null) {
            return redirect('student.exam-results.index')
                    ->with('info', 'Ujian telah diselesaikan.');
        }

        $validationRules = [
            'id' => [
                'label' => 'Id',
                'rules' => [
                    'required', 'string',
                    'regex_match[/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/]',
                    'is_unique[exam_results.id]'
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()
                    ->back()
                    ->with('validationError', $this->validator->getErrors())
                    ->withInput();
        }

        $questionModel = model('QuestionModel');

        $questionData = $questionModel
                        ->select('questions.id, questions.correct_answer')
                        ->join('exams', 'exams.id = questions.exam_id', 'inner')
                        ->where('slug', $slug)
                        ->findAll();

        $studentQuestionModel = model('StudentQuestionModel');

        $studentQuestionData = $studentQuestionModel
                                ->select('student_questions.question_id, student_questions.selected_option')
                                ->join('questions', 'questions.id = student_questions.question_id', 'inner')
                                ->join('exams', 'exams.id = questions.exam_id', 'inner')
                                ->where('exams.slug', $slug)
                                ->where('student_questions.user_id', session('user')['id'])
                                ->findAll();

        $correctAnswer = 0;

        $questionTotal = count($questionData);

        foreach ($questionData as $question) {
            foreach ($studentQuestionData as $studentQuestion) {
                if ($question['id'] === $studentQuestion['question_id']) {
                    if ($studentQuestion['selected_option'] === null) {
                        continue;
                    }

                    if ($studentQuestion['selected_option'] === $question['correct_answer']) {
                        $correctAnswer++;
                    }
                }
            }
        }

        $score = round(($correctAnswer / $questionTotal) * 100, 2);

        $examResultModel = model('ExamResultModel');

        $examResultModel->insert([
            'id' => $this->request->getPost('id'),
            'user_id' => session('user')['id'],
            'exam_id' => $examData['id'],
            'score' => $score,
        ]);

        return redirect('student.exam-results.index')
                ->with('success', 'Berhasil memproses hasil ujian.');
    }
}
