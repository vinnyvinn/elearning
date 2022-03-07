<?php


namespace App\Controllers\Student\Assessments;


use App\Models\ClassWorkItems;
use App\Models\ClassWorkSubmissions;

class ClassWork extends \App\Controllers\StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Class Work";
        return $this->_renderPage('Assessments/ClassWork/index', $this->data);
    }

    public function view($id)
    {
        return $this->do($id, $id);
//        $classwork = (new \App\Models\ClassWorkItems())->find($id);
//        if(!$classwork) {
//            return redirect()->to(previous_url());
//        }
//
//        $this->data['title'] = $classwork->name;
//        $this->data['classwork'] = $classwork;
//        $this->data['item'] = $classwork;
//        //dd($this->data['item']);
//
//        return $this->_renderPage('Assessments/ClassWork/view', $this->data);
    }

    public function do($classwork, $item)
    {
        //$item = (new ClassWorkItems())->find($item);
        $classwork = (new \App\Models\ClassWorkItems())->find($classwork);
//        if(!$item || !$classwork) {
//            return redirect()->to(previous_url());
//        }
        $this->data['site_title'] = $classwork->name;

        $this->data['classwork'] = $classwork;
        $this->data['item'] = $classwork;

        return $this->_renderPage('Assessments/ClassWork/do', $this->data);
    }

    public function submit($id, $itemId)
    {
        $data = $this->request->getJSON();
        $classwork = (new \App\Models\ClassWorkItems())->find($data->classwork);
        //$classwork_items = (new ClassWorkItems())->find($data->item);
        $answers = (array)$data->answers;
        $all_answered = TRUE;
        $correct_questions = 0;

        $return = FALSE;
        $msg = "Unknown error occurred";

        if($classwork) {
            $items = $classwork->items;
            foreach ($items as $key=>$item) {
                if(isset($item->corrects)) {
                    $correct = json_decode($item->corrects);

                    //$c_temp = $item->corrects->{$answers[$key]};
                    if($correct[0] == $answers[$key]){
                        $correct_questions++;
                    }
                } else {
                    $all_answered = FALSE;
                }
            }

            $total_questions = count($items);

            $out_of = (int) $classwork->out_of;
            $mark_per_question = $out_of/$total_questions;
            $score = $mark_per_question*$correct_questions;

            //Are we late?
            if($classwork->deadline->addDays(5)->addHours(23)->addMinutes(59)->getTimestamp() < time()) {
                return $this->response->setStatusCode(404)->setBody("Deadline has passed. Please contact your teacher");
            }elseif($classwork->deadline->addHours(23)->addMinutes(59)->getTimestamp() < time()) {
                $score = $score/2;
            }

            $to_db = [
                'student_id'    => $this->student->id,
                'class_work'    => $classwork->id,
                'classwork_item'    => $classwork->id,
                'subject'       => $classwork->subject->id,
                'answers'       => json_encode($answers),
                'score'         => number_format($score, 2),
                'correct_answers'   => $correct_questions,
                'mark_per_question' => $mark_per_question,
                'submitted_on'      => time()
            ];
            $model = new ClassWorkSubmissions();
            if($existing = $model->where('class_work', $classwork->id)
                //->where('classwork_item', $classwork->id)
                ->where('student_id', $this->student->id)
                ->where('subject', $classwork->subject->id)->get()->getFirstRow('object')
            ) {
                $to_db['id'] = $existing->id;
                $return = FALSE;
                $msg = "You cannot resubmit";
            } else {
                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Classwork submitted successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to submit the classwork";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
            }
        } else {
            return $this->response->setStatusCode(404)->setBody("Invalid classwork");
        }
        $status = $return ? 'success' : 'error';
        $res = [
            'status'    => $status,
            'message'   => $msg,
            'title'     => $return ? 'Success' : 'Error',
            'notifyType'    => 'swal',
            'callbackTime'  => 'onconfirm',
            'callback'  => $return ? 'window.location = "'.site_url(route_to('student.assessments.classwork.view', $classwork->id)).'"' : '',
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($res));
    }

    public function results($id)
    {
        $classwork = (new \App\Models\ClassWork())->find($id);
        if(!$classwork) {
            return redirect()->to(previous_url())->with('error', "Classwork not found");
        }

        $this->data['site_title'] = "$classwork->name Results";
        $this->data['classwork'] = $classwork;

        return $this->_renderPage('Assessments/ClassWork/results', $this->data);
    }
}