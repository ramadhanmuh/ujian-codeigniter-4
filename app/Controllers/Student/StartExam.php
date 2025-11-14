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

        $data['question'] = $questionModel->select('questions.text, questions.option_a, questions.option_b, questions.option_c, questions.option_d, exams.title, exams.slug, student_questions.number, student_questions.selected_option')
                                            ->join('student_questions', 'student_questions.question_id = questions.id', 'inner')
                                            ->join('exams', 'exams.id = questions.exam_id', 'inner')
                                            ->where('exams.slug', $slug)
                                            ->where('student_questions.number', $number)
                                            ->where('student_questions.user_id', session('user')['id'])
                                            ->where('exams.start_time <=', $now)
                                            ->where('exams.end_time >=', $now)
                                            ->first();

        if ($data['question'] === null) {
            return redirect()
                    ->back()
                    ->with('error', 'Soal tidak ditemukan.');
        }

        $studentQuestionModel = model('StudentQuestionModel');

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
}
