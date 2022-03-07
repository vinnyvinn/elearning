<?php


namespace App\Controllers\Student;


use App\Controllers\StudentController;
use App\Entities\Submission;
use App\Models\AssignmentItems;
use App\Models\AssignmentSubmissions;
use App\Models\Students;
use App\Models\Submissions;

class Assignments extends StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Assignments/index', $this->data);
    }

    public function submit($id)
    {
        $return = FALSE;
        $msg = "An error occured";
        if($data = $this->request->getPost()) {
            //Do not auto-save. I do not trust the html form and the student
            if($assignment = (new \App\Models\Assignments())->find($this->request->getPost('assignment_id'))) {
                if(strtotime($assignment->deadline) >= time()) {
                    $entity = new Submission();
                    $entity->student_id = $this->data['student']->id;
                    $entity->assignment_id = $assignment->id;
                    $entity->subject_id = $assignment->subject->id;
                    $entity->submitted_at = time();

                    $validation = \Config\Services::validation();
                    $validation->reset();
                    $validation->setRule('assignment', 'Assignment', 'uploaded[assignment]|ext_in[assignment,png,jpg,jpeg,pdf,doc,docx,pptx,xlsx,xls,txt]');
                    if($validation->withRequest($this->request)->run()) {
                        //Upload file
                        $file = $this->request->getFile('assignment');
                        if($file->isValid() && $file->move(FCPATH.'uploads/assignments', $newName = $file->getRandomName())) {
                            $entity->file = $newName;
                            if($ext = $this->request->getPost('id')) {
                                $entity->id = $ext;
                                @unlink((new Submissions())->find($ext)->path);
                            }
                            if($sub = (new Submissions())->save($entity)) {
                                $return = true;
                                $msg = "Assignment submitted successfully";
                            } else {
                                $msg = "Failed to submit assignment";
                            }
                        } else {
                            $msg = $file->getErrorString();
                        }
                    } else {
                        $msg = implode(', ', $validation->getErrors());
                    }
                } else {
                    $msg = "Deadline passed";
                    $msg = strtotime($assignment->deadline).' '.time();
                }
            } else {
                $msg = "Assignment does not exist";
            }

        } else {
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $res = [
                'status'    => $status,
                'message'   => $msg,
                'title'     => $return ? 'Success' : 'Error',
                'notifyType'    => 'toastr',
                'callback'  => $return ? 'window.location.reload()' : '',
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($res));
        }

        return $this->response->redirect(site_url(route_to('student.assignments')));
    }

    public function submitAssignment($student,$assignment)
    {
        $data = array(
          'student' => (new Students())->find($student),
          'assignment' => (new AssignmentItems())->find($assignment)
        );
        return $this->_renderPage('Assignments/submit_written', $data);
    }

    public function saveWr()
    {
        $assignment = (new AssignmentItems())->find($this->request->getPost('assignment'));
        $answers = $this->request->getPost('answer');
        $student = (new Students())->find($this->request->getPost('student'));

        $return = true;
        $msg = "An error occurred";
        $data = array();

        foreach ($assignment->items as $item){
            foreach ($answers as $key => $answer){
                if ($item->question_number == $key && $answer !=''){
                 array_push($data,array('question_number'=>$key,'answer'=>$answer,'instructions'=>$item->instructions,'precautions'=>$item->precautions,'question'=>$item->question,'explanation'=>$item->explanation));
                }
            }
        }
        if (count($assignment->items) !=count($data)){
            $return = false;
            $msg = "Sorry, You have not answered all questions.";
        }

        $model = new AssignmentSubmissions();
        $to_db = array('subject_id'=>$assignment->subject->id,'student_id'=>$student->id,'class_id'=>$student->class->id,'section_id'=>$student->section->id,'assignment_id'=>$assignment->id,'answer'=>json_encode($data),
            'mark_per_question'=>number_format($assignment->out_of/count($answers),1));
        $record = (new AssignmentSubmissions())->where('student_id',$student->id)->where('assignment_id',$assignment->id)->first();
        if ($record)
        $to_db['id'] = $record->id;

       if ($return) {
           if ($model->save($to_db)) {
               $return = true;
               $msg = "Assignment submitted successfully";
           } else {
               $return = false;
               $msg = "Failed to submit assignment";
           }
       }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $res = [
                'status'    => $status,
                'message'   => $msg,
                'title'     => $return ? 'Success' : 'Error',
                'notifyType'    => 'toastr',
                'callback'  => $return ? 'window.location.reload()' : '',
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($res));
        }
        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }
    public function download($id)
    {
        $model = new \App\Models\Assignments();
        $ass = $model->find($id);

        if($ass && file_exists($ass->path)) {
            return $this->response->download($ass->path, null, true);
        }

        return redirect()->back()->with('error', "File no longer exists");
    }

    public function download_submission($id)
    {
        $model = new \App\Models\Assignments();
        $ass = $model->find($id);

        $ass = $ass->isSubmitted($this->student->id);

        if($ass && file_exists($ass->path)) {
            return $this->response->download($ass->path, null, true);
        }

        return redirect()->back()->with('error', "File no longer exists");
    }
}