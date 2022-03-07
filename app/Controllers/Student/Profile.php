<?php


namespace App\Controllers\Student;


use App\Controllers\StudentController;
use App\Models\Payments;
use App\Models\PaymentSubmission;
use App\Models\Students;

class Profile extends StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
     if (isMobile())
      return view('Student/Home/index_mobile', $this->data);
      return $this->_renderPage('Home/index', $this->data);
    }

    public function dashboard()
    {
        return $this->_renderPage('Home/index', $this->data);
    }

    public function calendar()
    {
        return $this->_renderPage('Home/calendar', $this->data);
    }

    public function requirements()
    {
        $month = date('m');

        return $this->_renderPage('Home/requirements', $this->data);
    }

    public function payments()
    {
        $month = date('m');

        return $this->_renderPage('Home/payments', $this->data);
    }

    public function attendance()
    {

        $this->data['site_title'] = "Attendance";
        return $this->_renderPage('Attendance/index', $this->data);
    }

    public function upload_slip($payment)
    {
        $return = FALSE;
        $msg = "Invalid request";

        if ($this->request->getPost()) {
            //DO upload
            $file = $this->request->getFile('slip');
            $student = $this->student->id;
            $payment_model = new Payments();
            $info = $payment_model->find($payment);
            if($file && $file->isValid() && !$file->hasMoved() && is_numeric($student) && !empty($info)) {
                $name = $file->getRandomName();
                if($file->move(FCPATH.'uploads/deposit-slips', $name)) {
                    $to_db = [
                        'deposit_slip'  => $name,
                        'payment'       => $info->id,
                        'month'         => @$info->payment_month,
                        'class'         => @$info->class->id,
                        'student'       => $student
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
                } else {
                    $return = FALSE;
                    $msg = "Failed to upload file";
                }
            } else {
                $return = FALSE;
                $msg = "Invalid student file type";
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

    public function download_slip($id)
    {
        $slip = (new PaymentSubmission())->find($id);

        if($slip && file_exists($slip->slipPath)) {
            return $this->response->download($slip->slipPath, null, true);
        }

        return redirect()->to(previous_url())->with('error', "Deposit slip does not exist");
    }
}