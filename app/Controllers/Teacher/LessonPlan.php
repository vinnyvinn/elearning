<?php


namespace App\Controllers\Teacher;


use App\Controllers\TeacherController;
use App\Models\ClassSubjects;
use App\Models\Sections;
use CodeIgniter\Exceptions\PageNotFoundException;

class LessonPlan extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
     return $this->_renderPage('LessonPlan/index', $this->data);
    }

    public function getLessonPlan()
    {
        $section = $this->request->getPost('section');
        $subject = $this->request->getPost('subject');
        $subject = (new ClassSubjects())->find($subject);
        $section = (new Sections())->find($section);
        if(!$section || !$subject) {
            return new PageNotFoundException('Class or Section not found');
        }
        $data = [
            'section' => $section,
            'subject'   => $subject,
            'month'     => $this->request->getPost('month'),
            'week'      => $this->request->getPost('week')
        ];

        return view('Teacher/LessonPlan/view', $data);
    }

    public function createLessonPlan($section, $subject)
    {
        $section = (new \App\Models\Sections())->find($section);
        $subject = (new ClassSubjects())->find($subject);
        if (!$section || !$subject) {
            $return = FALSE;
            $msg = "Invalid request";
            return $this->response->redirect(previous_url());
        }
        if ($this->request->getPost()) {
            if (true) {
                $to_db = [
                    'session' => active_session(),
                    'class' => $section->class->id,
                    'section' => $section->id,
                    'subject' => $subject->id,
                    'month' => $this->request->getPost('month'),
                    'week' => $this->request->getPost('week'),
                    'unit' => $this->request->getPost('unit'),
                    'duration' => $this->request->getPost('duration'),
                    'objectives' => $this->request->getPost('objective'),
                    'day' => json_encode($this->request->getPost('day')),
                    'intro' => json_encode($this->request->getPost('intro')),
                    'presentation' => json_encode($this->request->getPost('presentation')),
                    'stabilization' => json_encode($this->request->getPost('stabilization')),
                    'evaluation' => json_encode($this->request->getPost('evaluation')),
                ];
                $model = new \App\Models\LessonPlan();
                $id = $this->request->getPost('id');
                if ($id && is_numeric($id)) {
                    $to_db['id'] = $id;
                }
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Lesson plan saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save lesson plan";
                }
            }
            $success = $return;
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $success ? 'success' : 'error',
                    'title' => 'Success',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'window.location = "' . site_url(route_to('teacher.subjects.view', $subject->id, $section->id)) . '"' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                //return $this->response->redirect(site_url(route_to('teacher.subjects.view', $subject->id, $section->id)));
                return $this->response->redirect(site_url(route_to('teacher.subjects.view', $subject->id, $section->id)));
            }
        } else {
            $this->data['subject'] = $subject;
            $this->data['section'] = $section;
            $this->data['month'] = @$month ? $month : '';
            $this->data['week'] = @$week ? $week : '';
            $this->data['title'] = 'Overview';
            $this->data['page'] = 'overview';
            //$this->_renderSection('LessonPlan/create', $this->data);
            $this->_renderPage('LessonPlan/create', $this->data);
        }
    }

    public function updateLessonPlan($section, $subject, $week, $month)
    {
        $section = (new \App\Models\Sections())->find($section);
        $subject = (new ClassSubjects())->find($subject);
        if (!$section || !$subject) {
            $return = FALSE;
            $msg = "Invalid request";
            return $this->response->redirect(previous_url());
        }
        if ($this->request->getPost()) {
            if (true) {
                $to_db = [
                    'session' => active_session(),
                    'class' => $section->class->id,
                    'section' => $section->id,
                    'subject' => $subject->id,
                    'month' => $this->request->getPost('month'),
                    'week' => $this->request->getPost('week'),
                    'unit' => $this->request->getPost('unit'),
                    'duration' => $this->request->getPost('duration'),
                    'objectives' => $this->request->getPost('objective'),
                    'day' => json_encode($this->request->getPost('day')),
                    'intro' => json_encode($this->request->getPost('intro')),
                    'presentation' => json_encode($this->request->getPost('presentation')),
                    'stabilization' => json_encode($this->request->getPost('stabilization')),
                    'evaluation' => json_encode($this->request->getPost('evaluation')),
                ];
                $model = new LessonPlan();
                $id = $this->request->getPost('id');
                if ($id && is_numeric($id)) {
                    $to_db['id'] = $id;
                }
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Lesson plan saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save lesson plan";
                }
            }
            $success = $return;
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $success ? 'success' : 'error',
                    'title' => 'Success',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'window.location = "' . site_url(route_to('teacher.subjects.view', $subject->id, $section->id)) . '"' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(site_url(route_to('teacher.subjects.view', $subject->id, $section->id)));
            }
        } else {
            $this->data['subject'] = $subject;
            $this->data['section'] = $section;
            $this->data['month'] = @$month ? $month : '';
            $this->data['week'] = @$week ? $week : '';
            $this->data['title'] = 'Overview';
            $this->data['page'] = 'overview';
            //$this->_renderSection('LessonPlan/update', $this->data);
            $this->_renderPage('LessonPlan/update', $this->data);
        }
    }

    //Download lesson plan
    public function downloadLessonPlan($section, $subject, $week, $month)
    {
        //return redirect()->to(previous_url())->with('error', "Not implemented");

        //$section = $this->request->getPost('section');
        //$subject = $this->request->getPost('subject');
        $subject = (new ClassSubjects())->find($subject);
        $section = (new Sections())->find($section);
        if(!$section || !$subject) {
            return new PageNotFoundException('Class or Section not found');
        }
        $data = [
            'section' => $section,
            'subject'   => $subject,
            'month'     => $month,
            'week'      => $week
        ];

        $html = view('Academic/LessonPlan/view', $data);
//        $pdf = new Pdf();
//
//        try {
//            $pdf->WriteHTML($html);
//            $pdf->title = "https://www.bennito254.com/";
//            $name = 'Lesson-Plan_'.$subject->name.'-'.time().'.pdf';
//            $pdf->Output($name, Destination::DOWNLOAD);
//        } catch (\Exception $e) {
//            throw new \ErrorException($e->getMessage());
//        }
        ?>
        <html lang="en">
        <head>
            <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
            <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css">
        </head>
        <body style="">
        <?php echo $html; ?>
        <script>
            window.print();
        </script>
        </body>
        </html>
        <?php
    }
}