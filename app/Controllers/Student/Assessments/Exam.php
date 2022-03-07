<?php


namespace App\Controllers\Student\Assessments;


use App\Models\CatExamItems;
use App\Models\CatExams;
use App\Models\CatExamSubmissions;

class Exam extends \App\Controllers\StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Exams";

        return $this->_renderPage('Assessments/Exams/index', $this->data);
    }

    public function view($id)
    {
        return $this->do($id, $id);

//        $exam = (new CatExamItems())->find($id);
//        if(!$exam) {
//            return redirect()->to(previous_url())->with('error', "Exam not found");
//        }
//        $this->data['site_title'] = "Exams";
//        $this->data['exam'] = $exam;
//
//        return $this->_renderPage('Assessments/Exams/view', $this->data);
    }

    public function do($exam, $item)
    {
        $exam = (new CatExamItems())->find($exam);
        //$item = (new CatExamItems())->find($item);
        if(!$exam) {
            return redirect()->to(previous_url())->with('error', "Exam not found");
        }
        $this->data['site_title'] = "Exams";
        $this->data['exam'] = $exam;
        $this->data['item'] = $exam;

        return $this->_renderPage('Assessments/Exams/do', $this->data);
    }

    public function submit($exam, $item)
    {
        $data = $this->request->getJSON();
        $exam = (new \App\Models\CatExamItems())->find($data->exam);
        //$exam_items = (new CatExamItems())->find($data->item);
        $answers = (array)$data->answers;
        $all_answered = TRUE;
        $correct_questions = 0;

        $return = FALSE;
        $msg = "Unknown error occurred";

        if($exam) {
            $items = $exam->items;
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

            if($all_answered) {
                $total_questions = count($items);
                $out_of = (int) $exam->out_of;
                $mark_per_question = $out_of/$total_questions;
                $score = $mark_per_question*$correct_questions;

                $to_db = [
                    'student_id'    => $this->student->id,
                    'cat_exam'    => $exam->id,
                    'cat_exam_item'    => $exam->id,
                    'subject'       => $exam->subject->id,
                    'answers'       => json_encode($answers),
                    'score'         => $score,
                    'correct_answers'   => $correct_questions,
                    'mark_per_question' => $mark_per_question,
                    'submitted_on'      => time()
                ];
                $model = new CatExamSubmissions();
                if($existing = $model->where('cat_exam', $exam->id)
                    //->where('cat_exam_item', $exam->id)
                    ->where('student_id', $this->student->id)
                    ->where('subject', $exam->subject->id)->get()->getFirstRow('object')
                ) {
                    $to_db['id'] = $existing->id;
                    $return = FALSE;
                    $msg = "You cannot resubmit";
                } else {
                    try {
                        if ($model->save($to_db)) {
                            $return = TRUE;
                            $msg = "exam submitted successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Failed to submit the exam";
                        }
                    } catch (\ReflectionException $e) {
                        $return = FALSE;
                        $msg = $e->getMessage();
                    }
                }
            } else {
                $return = FALSE;
                $msg = "Please answer all questions";
            }
        } else {
            return $this->response->setStatusCode(404)->setBody("Invalid exam");
        }
        $status = $return ? 'success' : 'error';
        $res = [
            'status'    => $status,
            'message'   => $msg,
            'title'     => $return ? 'Success' : 'Error',
            'notifyType'    => 'swal',
            'callbackTime'  => 'onconfirm',
            'callback'  => $return ? 'window.location = "'.site_url(route_to('student.assessments.exams.view', $exam->id)).'"' : '',
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($res));
    }

    public function results($id)
    {
        $exam = (new \App\Models\CatExamItems())->find($id);
        if(!$exam) {
            return redirect()->to(previous_url())->with('error', "Exam not found");
        }

        $this->data['site_title'] = "$exam->name Results";
        $this->data['exam'] = $exam;

        return $this->_renderPage('Assessments/Exams/results', $this->data);
    }
}