<?php


namespace App\Controllers\Assessments;


use App\Controllers\AdminController;
use App\Entities\Assessment;

class Assessments extends AdminController
{
    public function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Assessments/index', $this->data);
    }

    public function getAssessmentsAjax()
    {
        $class = $this->request->getPost('class');
        $section = $this->request->getPost('section');
        $subject = $this->request->getPost('subject');
        $month = $this->request->getPost('month');
        $week = $this->request->getPost('week');
        $data = [
            'class'     => $class,
            'section'   => $section,
            'subject'   => $subject,
            'month'     => $month,
            'week'      => $week
        ];

        return view('Assessments/assessment', $data);
    }

    public function save()
    {
        $data = $this->request->getPost('ass');
        $entity = new Assessment();
        $model = new \App\Models\Assessments();
        //print_r($data);
        foreach ($data as $student_id => $value) {
            $entity->fill($value);
            $model->save($entity);
        }

        $resp = [
            'status'    => 'success',
            'message'   => "Assessment results saved successfully",
            'notifyType'    => 'toastr',
            'title'     => 'Success',
            'callback'  => 'getAssessments()'
        ];
        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }
}