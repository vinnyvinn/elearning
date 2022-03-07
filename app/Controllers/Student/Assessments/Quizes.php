<?php


namespace App\Controllers\Student\Assessments;


use App\Models\QuizItems;
use App\Models\QuizSubmissions;

class Quizes extends \App\Controllers\StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Quizes";

        return $this->_renderPage('Assessments/Quizes/index', $this->data);
    }

    public function view($id)
    {
        return $this->do($id, $id);
//        $quiz = (new \App\Models\QuizItems())->find($id);
//        if(!$quiz) {
//            return redirect()->to(previous_url())->with('error', "Quiz not found");
//        }
//
//        $this->data['quiz'] = $quiz;
//        return $this->_renderPage('Assessments/Quizes/view', $this->data);
    }

    public function do($quiz, $item)
    {
        $quiz = (new \App\Models\QuizItems())->find($quiz);
        if(!$quiz) {
            return redirect()->to(previous_url())->with('error', "Quiz not found");
        }

        $this->data['quiz'] = $quiz;
        $this->data['item'] = $quiz;

        return $this->_renderPage('Assessments/Quizes/do', $this->data);
    }

    public function submit($quiz, $item)
    {
        $data = $this->request->getJSON();
        $quiz = (new \App\Models\QuizItems())->find($data->quiz);
        //$quiz_items = (new QuizItems())->find($data->item);
        $answers = (array)$data->answers;
        $all_answered = TRUE;
        $correct_questions = 0;

        $return = FALSE;
        $msg = "Unknown error occurred";

        if($quiz) {
            $items = $quiz->items;
            foreach ($items as $key=>$item) {
                if(isset($item->corrects)) {
                    $correct = json_decode($item->corrects);

                    if($correct[0] == $answers[$key]){
                        $correct_questions++;
                    }
                } else {
                    $all_answered = FALSE;
                }
            }
            $all_answered = TRUE; // does it matter when time is up?
            if($all_answered) {
                $total_questions = count($items);
                $out_of = (int) $quiz->out_of;
                $mark_per_question = $out_of/$total_questions;
                $score = $mark_per_question*$correct_questions;

                $to_db = [
                    'student_id'    => $this->student->id,
                    'quiz'    => $quiz->id,
                    'quiz_item'    => $quiz->id,
                    'subject'       => $quiz->subject->id,
                    'answers'       => json_encode($answers),
                    'score'         => $score,
                    'correct_answers'   => $correct_questions,
                    'mark_per_question' => $mark_per_question,
                    'submitted_on'      => time()
                ];
                $model = new QuizSubmissions();
                if($existing = $model->where('quiz', $quiz->id)
                    //->where('quiz_item', $quiz->id)
                    ->where('student_id', $this->student->id)
                    ->where('subject', $quiz->subject->id)->get()->getFirstRow('object')
                ) {
                    $to_db['id'] = $existing->id;
                    $return = FALSE;
                    $msg = "You cannot resubmit";
                } else {
                    if($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "quiz submitted successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to submit the quiz";
                    }
                }
            } else {
                $return = FALSE;
                $msg = "Please answer all questions";
            }
        } else {
            return $this->response->setStatusCode(404)->setBody("Invalid quiz");
        }
        $status = $return ? 'success' : 'error';
        $res = [
            'status'    => $status,
            'message'   => $msg,
            'title'     => $return ? 'Success' : 'Error',
            'notifyType'    => 'swal',
            'callbackTime'  => 'onconfirm',
            'callback'  => $return ? 'window.location = "'.site_url(route_to('student.assessments.quizes.view', $quiz->id)).'"' : '',
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($res));
    }

    public function results($id)
    {
        $quiz = (new \App\Models\QuizItems())->find($id);
        if(!$quiz) {
            return redirect()->to(previous_url())->with('error', "Quiz not found");
        }

        $this->data['site_title'] = "$quiz->name Results";
        $this->data['quiz'] = $quiz;

        return $this->_renderPage('Assessments/Quizes/results', $this->data);
    }
}