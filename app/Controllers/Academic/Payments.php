<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Entities\Payment;
use App\Models\Classes;
use App\Models\PaymentSubmission;
use App\Models\Students;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Payments extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Payments List";
        return $this->_renderPage('Academic/Payments/index', $this->data);
    }

    public function view($payment, $class)
    {
        $class = (new Classes())->find($class);
        if(!$class) {
            return redirect()->to(previous_url())->with('error', "Class not found");
        }

        $payment = (new \App\Models\Payments())->find($payment);
        if(!$payment) {
            return redirect()->to(previous_url())->with('error', "Payment entry not found");
        }

        $this->data['class'] = $class;
        $this->data['payment'] = $payment;
        $this->data['site_title'] = "Payments for ".$class->name;
        return $this->_renderPage('Academic/Payments/view', $this->data);
    }

    public function print()
    {
         $this->data['month'] = $_GET['month'];
        return view('Academic/Payments/list/print', $this->data);
    }

    public function pdf()
    {
        $this->data['month'] = $_GET['month'];
        return view('Academic/Payments/list/pdf', $this->data);
    }
    function exportExcel()
    {
        $selected_class =  $_GET['class'];
        $selected_month =  $_GET['month'];
        $model = (new \App\Models\Payments())->where('session', active_session());
        if($selected_class != '' && is_numeric($selected_class)) {
            $model->where('class', $selected_class);
        }
        if($selected_month != '' && is_numeric($selected_month)) {
            //$selected_month = str_pad($selected_month, 2, '0', STR_PAD_LEFT);
            //$model->like('deadline', $selected_month, 'left');
            $model->where('payment_month', $selected_month);
        }
        $model->orderBy('id', 'DESC');
        $payments = $model->findAll();

        $file_name = getSession()->name.' Payment List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $month = $selected_month ? getMonthName($selected_month) :'ALL';
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Payment List\n".$month;
        $sheet->setCellValue("A1","$title");

        //Merge cells
        $sheet->mergeCells('A1:I1');

        $sheet->getStyle("A1")->applyFromArray(
            array(
                'font'=> array('size'=>24,'bold'=>true)
            )
        );

        //Alignment
        $sheet->getStyle("A1")->getAlignment()->setHorizontal("center");

        //adjust dimensions
        $sheet->getColumnDimension("A")->setWidth(30);
        $sheet->getRowDimension("1")->setRowHeight(120);
        $sheet->getColumnDimension("B")->setWidth(20);
        $sheet->getColumnDimension("C")->setWidth(20);
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);

        // column headers
        $sheet->setCellValue('A2', 'Class');
        $sheet->setCellValue('B2', 'Description');
        $sheet->setCellValue('C2', 'Amount');
        $sheet->setCellValue('D2', 'Payment Month');
        $sheet->setCellValue('E2', 'Deadline');

        $count = 3;
        foreach($payments as $row)
        {
            $sheet->setCellValue('A' . $count, $row->class ? $row->class->name : 'All Classes');
            $sheet->setCellValue('B' . $count, $row->description);
            $sheet->setCellValue('C' . $count,  fee_currency($row->amount));
            $sheet->setCellValue('D' . $count, isset($row->payment_month) ? date("F", strtotime('01-' . $row->payment_month . '-2001')) : '-');
            $sheet->setCellValue('E' . $count, $row->deadline);
            $count++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:' . filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }
    public function clear_payment($payment_id, $student_id)
    {
        $payment = (new \App\Models\Payments())->find($payment_id);
        if(!$payment) {
            return redirect()->to(previous_url())->with('error', "Payment not found");
        }

        $submission = new PaymentSubmission();
        $exists = $submission->where('payment', $payment->id)->where('student', $student_id)->get()->getRowObject();

//        if(!$exists) {
//            return redirect()->to(previous_url())->with('error', "Deposit slip does not exist");
//        }
       $student = (new Students())->find($student_id);
        $to_db = array(
            'payment' => $payment_id,
            'student' => $student_id,
            'class' => isset($student->class->id) ? $student->class->id : 0,
            'month' => $payment->payment_month,
            'status' => 1,
            'payment_date' => date('Y-m-d'),
            'reference' => random_string('alnum')
        );
        $db = \Config\Database::connect();
        $builder = $db->table('payments_submission');
        if (!empty($exists)){
            $builder->where('id',$exists->id);
            $builder->update($to_db);
        }else {
            $builder->insert($to_db);
        }

        if($submission->where('payment', $payment->id)->where('student', $student_id)->set(['status' => 1])->update()) {
            return redirect()->to(previous_url())->with('success', "Payment cleared successfully");
        }
        
        return redirect()->to(previous_url())->with('error', "Failed to clear payment");
    }

    public function download_slip($payment_submission)
    {
        $payment = (new \App\Models\PaymentSubmission())->find($payment_submission);
        if($payment && file_exists($payment->slipPath)) {
         return $this->response->download($payment->slipPath, null, true);
        }

        return redirect()->to(previous_url())->with('error', "Deposit slip does not exist");
    }

    public function save()
    {
        $data = $this->request->getPost();
        $entity = new Payment();
        $entity->fill($data);
        $entity->class = $this->request->getPost('class') && is_numeric($this->request->getPost('class')) ? $this->request->getPost('class') : NULL;
        $entity->section = $this->request->getPost('section') && is_numeric($this->request->getPost('section')) ? $this->request->getPost('section') : NULL;
        $model = new \App\Models\Payments();
        if ($model->save($entity)) {
            $return = TRUE;
            $msg = "Entry saved successfully";
            if($this->request->getPost('sms_notification') == 1) {
                //TODO: Send SMS for this notification
                //TODO: Probable a scheduler? The SMSes may be way too much
            }
        } else {
            $return = FALSE;
            $msg = "An error occurred";
        }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }

    public function delete($id)
    {
        $model = new \App\Models\Payments();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Payment deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete payment";
        }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }
}