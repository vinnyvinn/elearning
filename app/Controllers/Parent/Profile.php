<?php


namespace App\Controllers\Parent;


use App\Controllers\ParentController;
use App\Models\Payments;
use App\Models\PaymentSubmission;
use App\Models\Requirements;
use App\Models\RequirementSubmission;
use App\Models\Students;

class Profile extends ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Parents Dashboard";
        if (isMobile())
            return view('Parent/Home/index_mobile', $this->data);
        $this->_renderPage('Home/index', $this->data);
    }

    public function calendar()
    {
        return $this->_renderPage('Home/calendar', $this->data);
    }
    public function requirements()
    {

        $this->data['site_title'] = "Requirements";

        if (is_array($this->data['parent']->studentsCurrent) && count($this->data['parent']->studentsCurrent) > 1)
        return $this->_renderPage('Requirements/index_more_students', $this->data);
        return $this->_renderPage('Requirements/index', $this->data);
    }

    public function payments()
    {

        $this->data['site_title'] = "Payments";

        if (is_array($this->data['parent']->studentsCurrent) && count($this->data['parent']->studentsCurrent) > 1)
            return $this->_renderPage('Payments/index_more_students', $this->data);
        return $this->_renderPage('Payments/index', $this->data);
    }

    public function attendance()
    {

        $this->data['site_title'] = "Attendance";

        if (is_array($this->data['parent']->studentsCurrent) && count($this->data['parent']->studentsCurrent) > 1)
            return $this->_renderPage('Attendance/more_students', $this->data);
        return $this->_renderPage('Attendance/index', $this->data);
    }

    public function upload_slip($payment)
    {
        $return = FALSE;
        $msg = "Invalid request";

        if ($this->request->getPost()) {
            //DO upload
            $file = $this->request->getFile('slip');
            $student = $this->request->getPost('student');
            $payment_model = new Payments();
            $info = $payment_model->find($payment);
            $name = "Not Set";
            if($file && $file->isValid() && !$file->hasMoved() && is_numeric($student) && !empty($info)) {
                $name = $file->getRandomName();
                $file->move(FCPATH.'uploads/deposit-slips', $name);

            }

            $to_db = [
                'deposit_slip'  => $name,
                'payment_date'  => date('Y-m-d'),
                'payment'       => $info->id,
                'month'         => @$info->payment_month,
                'class'         => @$info->class->id,
                'student'       => $student,
                'reference'     => trim($this->request->getPost('reference'))?:'Not Set'
            ];
            $model = new PaymentSubmission();
            try {
                $model->save($to_db);
                $return = TRUE;
                $msg = "Uploaded successfully";

            } catch (\ReflectionException $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }
        }

        $status = $return ? 'success' : 'error';

        if ($this->request->isAJAX()) {

            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $status,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        session()->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    //Mark requirement as checked
    public function mark_checked($id)
    {
        if ($this->request->getPost()) {
            $requirement = (new Requirements())->find($id);
            if (!$requirement) {
                return redirect()->to(previous_url())->with('error', "Requirement does not exist");
            }

            $student = (new Students())->find($this->request->getPost('student'));
            $model = new RequirementSubmission();
            $to_db = array(
                'parent_comment' => $this->request->getPost('parent_comment'),
                'parent_check' => 1,
                'student' => $student->id,
                'class' => isset($student->class->id) ? $student->class->id : 0,
                'section' => isset($student->section->id) ? $student->section->id : 0,
                'session' => active_session(),
                'requirement' => $id
            );

            $entry = (new RequirementSubmission())->where('student',$student->id)->where('session',active_session())->where('requirement',$id)->get()->getRow();

            try {
                $db = \Config\Database::connect();
                $builder = $db->table('requirements_submissions');
                if (isset($entry->id)){
                    $builder->where('requirement',$id);
                    $builder->where('student',$student->id);
                    $builder->where('session',active_session());
                    if ($builder->update($to_db)){
                        return redirect()->to(previous_url())->with('success', "Marked successfully");
                    }else {
                        return redirect()->to(previous_url())->with('error', "Something went wrong");
                    }
                }else {
                    if ($model->save($to_db)) {
                        return redirect()->to(previous_url())->with('success', "Marked successfully");
                    } else {
                        return redirect()->to(previous_url())->with('error', "Something went wrong");
                    }
                }
            } catch (\ReflectionException $e) {
                return redirect()->to(previous_url())->with('error', "Something went wrong");
            }
        }
        return redirect()->to(previous_url())->with('error', "Invalid request");
    }

    public function download_slip($id)
    {
        $slip = (new PaymentSubmission())->find($id);

        if($slip && file_exists($slip->slipPath)) {
            return $this->response->download($slip->slipPath, null, true);
        }

        return redirect()->to(previous_url())->with('error', "Deposit slip does not exist");
    }
}