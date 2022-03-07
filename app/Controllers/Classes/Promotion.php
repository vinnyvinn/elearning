<?php


namespace App\Controllers\Classes;


use App\Controllers\AdminController;
use App\Models\Departure;
use App\Models\StudentArchives;
use App\Models\Students;
use CodeIgniter\Exceptions\PageNotFoundException;

class Promotion extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_renderPage('Classes/Promotion/index', $this->data);
    }

    public function move()
    {
        $this->_renderPage('Classes/Promotion/move', $this->data);
    }


    public function promote()
    {
        if(!$this->request->isAJAX()) throw new PageNotFoundException("Invalid Request Method");

        if($this->request->getPost()){
            $oldSession = $this->request->getPost('oldSession');
            $oldClass = $this->request->getPost('oldClass');
            $oldSection = $this->request->getPost('oldSection');
            $students = $this->request->getPost('student');
            $newSession = $this->request->getPost('newSession');
            $newClass = $this->request->getPost('newClass');
            $newSection = $this->request->getPost('newSection');
            $session = (new \App\Models\Sessions())->find($oldSession);
            $semesters = $session->semesters;

            $return = true;
            $msg = 'Success';
            if (empty($students)) {
                $return = false;
                //$msg = "Please select at least one student";
                $msg = (string) json_encode($students);
            } else {
                if($oldSession != $newSession) {
                    $students_model = new Students();
                    $archives = new StudentArchives();

                    //start here @vinnyvinny
                    foreach ($students as $student) {
//                        $total_marks = [];
//                        $subs_arr = [];
//                        $counter = 0;
//                        $std = (new Students())->find($student);
//                        $subjects = $std->class->subjects;
//                        foreach ($subjects as $subject) {
//                            if ($subject->optional == 0)
//                                $counter++;
//
//                            foreach ($semesters as $item) {
//                                $resultsModel = new \App\Libraries\YearlyResults($std->id, $oldSession);
//                                $result = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subject->id, $std->section->id);
//                                if ($subject->optional == 0) {
//                                    if (isset($total_marks[$item->id])) {
//                                        $total_marks[$item->id] += is_numeric($result) ? $result : 0;
//                                    } else {
//                                        $total_marks[$item->id] = is_numeric($result) ? $result : 0;
//                                    }
//
//                                    if (!isset($subs_arr[$item->id.'.'.$subject->id])){
//                                        $subs_arr[$item->id.'.'.$subject->id] = $result;
//                                    }
//                                }
//                            }
//                        }
//
//                        $counter_fail = 0;
//                        foreach ($subjects as $subject) {
//                            if ($subject->optional == 0) {
//                                if ((($subs_arr[$semesters[0]->id.'' . $subject->id] + $subs_arr[$semesters[1]->id.'.' . $subject->id]) / 2) < $subject->pass_mark) {
//                                    $counter_fail++;
//                                }
//                            }
//                        }
//
//                        $av_score = number_format(($total_marks[$semesters[0]->id] + $total_marks[$semesters[1]->id])/$counter,2);
//                        $data = array('average_mark'=>$av_score,'promoted' => ($counter_fail < 3 && $av_score >= $std->class->pass_mark) ? 1 : 0,'student'=>$student,'old_class'=>$oldClass,'new_class'=>$newClass,
//                            'old_section'=>$oldSection,'new_section'=>$newSection,'old_session'=>$oldSession,'new_session'=>$newSession);
//
//                       $promoted = new \App\Models\Promotion();
//                       $promoted->save($data);

                        //end here @vinnyvinny
                        $sId = $student;
                        $student = $students_model->find($student);
                        if($student) {
                            $old = $student;
                            //remove id
                            unset($old->id);
                            $insert = array('session'=>$newSession,'class'=>$newClass,'section'=>$newSection,'admission_number'=>$student->admission_number,
                                'user_id'=>$student->user_id,'parent'=>$student->parent->id,'admission_date'=>$student->admission_date,'active'=>1);
                            //Set up new student
//                            $db = \Config\Database::connect();
//                            $builder = $db->table('students');
//                            $builder->insert($insert);

                            try {
                                if($insert = $students_model->save($insert) && $archives->insert($old)) {
                                //if ($students_model->save($student)) {
                                    $return = TRUE;
                                    $msg = "Selected Students promoted successfully";
                                } else {
                                    $return = FALSE;
                                    $msg = "Some students were not promoted";
                                }
                            } catch (\ReflectionException $e) {
                                $return = FALSE;
                                $msg = $e->getMessage();
                            }
                        }
                    }
                } else {
                    $return = FALSE;
                    $msg = "Please choose a different session to promote student to";
                }
            }

        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => $return ? 'window.location.reload()' : ''
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }

    public function moveStudent()
    {
       //if(!$this->request->isAJAX()) throw new PageNotFoundException("Invalid Request Method");
        if($this->request->getPost()){
            $oldSession = $this->request->getPost('oldSession');
            $oldClass = $this->request->getPost('oldClass');
            $oldSection = $this->request->getPost('oldSection');
            $students = $this->request->getPost('student');
            $newSession = $this->request->getPost('newSession');
            $newClass = $this->request->getPost('newClass');
            $newSection = $this->request->getPost('newSection');
            $session = (new \App\Models\Sessions())->find($oldSession);
            $semesters = $session->semesters;

            $return = true;
            $msg = 'Success';
            if (empty($students)) {
                $return = false;
                //$msg = "Please select at least one student";
                $msg = (string) json_encode($students);
            } else {
                    $students_model = new Students();
                    //start here @vinnyvinny

                    foreach ($students as $key => $student) {
                            $to_db = array('id'=>$student,'class'=>$newClass,'section'=>$newSection);
                              try {
                                if($students_model->save($to_db)) {
                                    $return = TRUE;
                                    $msg = "Selected Students moved successfully";
                                } else {
                                    $return = FALSE;
                                    $msg = "Some students were not moved";
                                }
                            } catch (\ReflectionException $e) {
                                $return = FALSE;
                                $msg = $e->getMessage();
                            }
                    }
            }

        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => $return ? 'window.location.reload()' : ''
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }
    public function departStudents()
    {
        //   if(!$this->request->isAJAX()) throw new PageNotFoundException("Invalid Request Method");
        if($this->request->getPost()){
            $oldSession = $this->request->getPost('oldSession');
            $students = $this->request->getPost('student');

            $return = true;
            $msg = 'Success';
            if (empty($students)) {
                $return = false;
                //$msg = "Please select at least one student";
                $msg = (string) json_encode($students);
            } else {
                $departure_model = new Departure();
                $student_model = new Students();
                //start here @vinnyvinny
                foreach ($students as $key => $student) {
                    $to_db = array('session'=>$oldSession,'student'=>$student,'type'=>'departure');
                    try {
                        if($departure_model->save($to_db)) {
                            $insert = array('id'=> $student,'active'=>0);
                            $student_model->save($insert);
                            $return = TRUE;
                            $msg = "Selected Students departed successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Some students were not departed";
                        }
                    } catch (\ReflectionException $e) {
                        $return = FALSE;
                        $msg = $e->getMessage();
                    }
                }
            }

        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => $return ? 'window.location.reload()' : ''
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }
}