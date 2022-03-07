<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;
use App\Libraries\YearlyResults;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\ManualAssessments;
use App\Models\Payments;
use App\Models\Requirements;
use App\Models\Semesters;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\Teachers;
use App\Models\TransportRoutes;

class Dashboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $semesters = getSession()->semesters;
        $classes = getSession()->classes->findAll();

      //  foreach ($semesters as $semester){
         //   echo $semester->id.'kk <br>';
         //$model = new \SemesterScores($semester->id);
        // $model->setScores();
        // $model->setScoresKG();

//            foreach ($classes as $class){
//                $model->setYearlyRanking($class->id);
//            }
       // }


//        $model2 = new \SemesterScores(1);
//      $model2->setYearlyRanking();
       return 'success';

      return $this->_renderPage('Admin/Dashboard/index');
    }

    public function getNotifications()
    {

      echo json_encode(['requirements'=>$this->requirements(),'attendance'=>$this->attendance(),'payments'=>$this->payments(),'exam_result'=>$this->examResult(),'assessment'=>$this->assessment(),'routes'=>$this->routes()]);
    }

    public function requirements()
    {
        $requirements = (new Requirements())->limit(5)->orderBy("created_at","DESC")->get()->getResult();
        $count = (new Requirements())->where('view_status',0)->countAllResults();
        $output = '';
        if (count($requirements) > 0) {
            foreach ($requirements as $requirement) {
                $url = isset($requirement->class)? site_url(route_to('admin.academic.view_requirement', $requirement->id,  $requirement->class)):site_url(route_to('admin.academic.requirements'));
                $output .= '
           <a href="'.$url.'" class="navi-item">
            <div class="navi-link">                
                <div class="navi-text">
                    <div class="font-weight-bold">'.$requirement->item.'</div>
                    <div class="text-muted">'.get_time_ago(strtotime($requirement->created_at)).'</div>
                </div>
            </div>
            </a>
           ';
            }
        }
        else {
            $output.='
            <div class="d-flex flex-center text-center text-muted min-h-200px">All caught up!<br />No new notifications.</div>
          ';
        }
        return ['data'=>$output,'count'=>$count];
    }
    public function attendance()
    {
        $attendance = (new Attendance())->limit(5)->orderBy('date_created','DESC')->groupBy('timestamp')->get()->getResult();
        $count = (new Attendance())->where('view_status',0)->countAllResults();
        $output = '';
        if (count($attendance) > 0) {
            foreach ($attendance as $attend) {
                if ($attend->student)
                $student = (new Students())->find($attend->student);
                else
                $teacher = (new Teachers())->find($attend->teacher);
                $name = isset($student)?$student->class->name.'_'.$student->section->name : $teacher->profile->name;

                $url = site_url(route_to('admin.attendance.show',$attend->id));
                $output .= '
           <a href="'.$url.'" class="navi-item">
            <div class="navi-link">                
                <div class="navi-text">
                    <div class="font-weight-bold">'.$name.'</div>
                    <div class="text-muted">'.get_time_ago(strtotime($attend->date_created)).'</div>
                </div>
            </div>
            </a>
           ';
            }
        }
        else {
            $output.='
            <div class="d-flex flex-center text-center text-muted min-h-200px">All caught up!<br />No new notifications.</div>
          ';
        }
        return ['data'=>$output,'count'=>$count];
    }
    public function payments()
    {
        $payments = (new Payments())->limit(5)->orderBy('created_at','DESC')->get()->getResult();
        $count = (new Payments())->where('view_status',0)->countAllResults();
        $output = '';
        if (count($payments) > 0) {
            foreach ($payments as $payment) {
              //  $url = site_url(route_to('admin.academic.view_payment', $payment->id, $payment->class));
                $output .= '
           <a href="/admin/academic/payment/'.$payment->id.'/class/'.$payment->class.'" class="navi-item">
            <div class="navi-link">                
                <div class="navi-text">
                    <div class="font-weight-bold">'.$payment->description.'</div>
                    <div class="text-muted">'.get_time_ago(strtotime($payment->created_at)).'</div>
                </div>
            </div>
            </a>
           ';
            }
        }
        else {
            $output.='
            <div class="d-flex flex-center text-center text-muted min-h-200px">All caught up!<br />No new notifications.</div>
          ';
        }
        return ['data'=>$output,'count'=>$count];
    }

    public function examResult()
    {
        $exams = (new ExamResults())->limit(5)->orderBy('created_at','DESC')->groupBy('class')->get()->getResult();
        $count = (new ExamResults())->where('view_status',0)->countAllResults();
        $output = '';
        if (count($exams) > 0) {
            foreach ($exams as $exam) {
                $exam_ = (new Exams())->find($exam->exam);
                $class = (new Classes())->find($exam->class);

                $url = site_url(route_to('admin.academic.exam.show.result',$exam->exam,$exam->class));
                $output .= '
           <a href="'.$url.'" class="navi-item">
            <div class="navi-link">                
                <div class="navi-text">
                    <div class="font-weight-bold">'.$exam_->name.'_'.$class->name.'</div>
                    <div class="text-muted">'.get_time_ago(strtotime($exam->created_at)).'</div>
                </div>
            </div>
            </a>
           ';
            }
        }
        else {
            $output.='
            <div class="d-flex flex-center text-center text-muted min-h-200px">All caught up!<br />No new notifications.</div>
          ';
        }
        return ['data'=>$output,'count'=>$count];
    }
    public function assessment()
    {
        $exams = (new ManualAssessments())->limit(5)->orderBy('created_at','DESC')->groupBy('subject')->get()->getResult();
        $count = (new ManualAssessments())->where('view_status',0)->countAllResults();
        $output = '';
        if (count($exams) > 0) {
            foreach ($exams as $exam) {
                $student = (new Students())->find($exam->student);
                if ($student) {
                    $sem = (new Semesters())->find($exam->semester);
                    $sub = (new ClassSubjects())->find($exam->subject);
                    $subject_name = (new Subjects())->find($sub->subject)->name;

                    $url = site_url(route_to('admin.academic.semester_results', $student->class->id, $sem->id, $exam->subject));
                    $output .= '
           <a href="' . $url . '" class="navi-item">
            <div class="navi-link">                
                <div class="navi-text">
                    <div class="font-weight-bold">' . $student->class->name . '_' . $sem->name . '_' . $subject_name . '</div>
                    <div class="text-muted">' . get_time_ago(strtotime($exam->created_at)) . '</div>
                </div>
            </div>
            </a>
           ';
                }
            }
        }
        else {
            $output.='
            <div class="d-flex flex-center text-center text-muted min-h-200px">All caught up!<br />No new notifications.</div>
          ';
        }
        return ['data'=>$output,'count'=>$count];
    }
    public function routes()
    {
        $routes = (new TransportRoutes())->limit(5)->orderBy('created_at','DESC')->get()->getResult();
        $count = (new TransportRoutes())->where('view_status',0)->countAllResults();
        $output = '';
        if (count($routes) > 0) {
            foreach ($routes as $route) {
                $url = site_url(route_to('admin.transport.view', $route->id));
                $output .= '
           <a href="'.$url.'" class="navi-item">
            <div class="navi-link">                
                <div class="navi-text">
                    <div class="font-weight-bold">'.$route->route.'</div>
                    <div class="text-muted">'.get_time_ago(strtotime($route->created_at)).'</div>
                </div>
            </div>
            </a>
           ';
            }
        }
        else {
            $output.='
            <div class="d-flex flex-center text-center text-muted min-h-200px">All caught up!<br />No new notifications.</div>
          ';
        }
        return ['data'=>$output,'count'=>$count];
    }

    public function markRead()
    {
        $type = $this->request->getPost('type');
        $db = \Config\Database::connect();
        $data = array('view_status'=>1);
        if ($type=='requirements'){
         $builder = $db->table('requirements');
         $builder->where('view_status',0);
         $builder->update($data);
        }
        elseif($type=='attendance'){
        $builder = $db->table('attendance');
        $builder->where('view_status',0);
        $builder->update($data);
        }
        elseif($type=='assessment'){
            $builder = $db->table('manual_assessments');
            $builder->where('view_status',0);
            $builder->update($data);
        }
        elseif($type=='routes'){
            $builder = $db->table('transport_routes');
            $builder->where('view_status',0);
            $builder->update($data);
        }
        elseif($type=='payments'){
            $builder = $db->table('payments');
            $builder->where('view_status',0);
            $builder->update($data);
        }
        elseif($type=='exam_results'){
            $builder = $db->table('exam_results');
            $builder->where('view_status',0);
            $builder->update($data);
        }
    }

}

