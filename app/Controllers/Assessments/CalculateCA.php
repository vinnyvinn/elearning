<?php


namespace App\Controllers\Assessments;


use App\Controllers\AdminController;
use App\Models\AssessmentResults;
use App\Models\Assignments;
use App\Models\CatExamItems;
use App\Models\CatExams;
use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\ClassWorkItems;
use App\Models\QuizItems;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Submissions;

class CalculateCA extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Calculate Continuous Assessments";
    }

    public function index()
    {

        return $this->_renderPage('Academic/Assessments/CalculateCA/index', $this->data);
    }

    public function getCats()
    {
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $semester = $this->request->getPost('semester');

        $delete = $this->request->getPost('delete');
        $class = (new Classes())->find($class);
        if (!$class) {
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }
        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
            return $this->response->setStatusCode(404)->setBody("Subject not found");
        }

        $semester = (new Semesters())->find($semester);
        if(!$semester) {
            return $this->response->setStatusCode(404)->setBody("Semester not found");
        }

        $resModel = new AssessmentResults();
        $existing = $resModel->where('session', active_session())->where('class', $class->id)
            ->where('subject', $subject->id)->where('semester', $semester->id)->findAll();

        if(isset($delete) && $delete == 1) {
            $existing = $resModel->where('session', active_session())->where('class', $class->id)
                ->where('subject', $subject->id)->where('semester', $semester->id)->delete();
            $existing = FALSE;
        }
        if(!empty($existing)) {
            $data = [
                'class' => $class,
                'semester'  => $semester,
                'subject' => $this->request->getPost('subject'),
                'existing'  => $existing
            ];

            return view('Academic/Assessments/CalculateCA/existing', $data);
        }

        $classworks = (new \App\Models\ClassWorkItems())
            ->where('semester', $semester->id)
            ->where('subject', $subject->id)
            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();
        $quizes = (new \App\Models\QuizItems())
            ->where('semester', $semester->id)
            ->where('subject', $subject->id)
            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();
//        $exams = (new CatExamItems())
//            ->where('semester', $semester->id)
//            ->where('subject', $subject->id)
//            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();
        $assignments = (new Assignments())
            ->where('semester', $semester->id)
            ->where('class', $class->id)->where('subject', $subject->id)->orderBy('id', 'DESC')->findAll();

        $data = [
            'classwork' => $classworks,
            'quizes' => $quizes,
            //'exams' => $exams,
            'assignments'   => $assignments,
            'class' => $class,
            'semester'  => $semester,
            'subject' => $this->request->getPost('subject')
        ];

        return view('Academic/Assessments/CalculateCA/getCats', $data);
    }

    public function calculate()
    {
        $classworks = $this->request->getPost('classwork');
        $quizes = $this->request->getPost('quizes');
        $exams = $this->request->getPost('exams');
        $assignments = $this->request->getPost('assignment');
        $semester = $this->request->getPost('semester');

        $classwork_model = new \App\Models\ClassWorkItems();
        $quizes_model = new \App\Models\QuizItems();
        //$exams_model = new CatExamItems();
        $assignments_model = new Assignments();
        $subject = $this->request->getPost('subject');
        $to_db = [];

        //Some configs
        $base_numeral = 100; //Out of this value

        $classworkObj = [];
        //$clsItemsModel = new ClassWorkItems();
        $cwsOutOf = 0;
        if(is_array($classworks) && count($classworks) > 0) {
            foreach ($classworks as $classwork) {
                if ($cls = $classwork_model->find($classwork)) {
                    $classworkObj[] = $cls;
                    $out_of = $cls->out_of;
                    $cwsOutOf += $out_of;
                    //echo "Classwork OUT OF: ".$out_of;
                }
            }
        }


        $quizesObj = [];
        //$qzItemsModel = new QuizItems();
        $qzOutOf = 0;
        if(is_array($quizes) && count($quizes)) {
            foreach ($quizes as $quize) {
                if ($qz = $quizes_model->find($quize)) {
                    $quizesObj[] = $qz;
                    $out_of = $qz->out_of;
                    $qzOutOf += $out_of;
                    //echo "Quizes OUT OF: ".$out_of;
                }
            }
        }
//        $examsObj = [];
//        //$exItemsMOdel = new CatExamItems();
//        $exOutOf = 0;
//        if(is_array($exams) && count($exams) > 0) {
//            foreach ($exams as $exam) {
//                if ($ex = $exams_model->find($exam)) {
//                    $examsObj[] = $ex;
//                    $out_of = $exams_model->where('cat_exam', $ex->id)->where('subject', $subject)->selectSum('out_of', 'out_of_sum')->get()->getFirstRow()->out_of_sum;
//                    $exOutOf += $out_of;
//                }
//            }
//        }

        $assObj = [];
        //$exAssModel = new Submissions();
        $assOutOf = 0;
        if(is_array($assignments) && count($assignments) > 0) {
            foreach ($assignments as $assignment) {
                if($x = $assignments_model->find($assignment)) {
                    $assObj[] = $x;
                    $assOutOf += $x->out_of;
                    //echo "Assignments OUT OF: ".$x->out_of;
                }
            }
        }

        $section = $this->request->getPost('class');
        $section = (new Classes())->find($section);
        if (!$section) {
            //no need to continue
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }

        $students = $section->students;
        $return = TRUE;
        $msg = "If you see this message, it means there is something wrong with the system";
        if(is_array($students) && count($students) > 0) {
            foreach ($students as $student) {
                //CWS
                $score = 0;
                foreach ($classworkObj as $item) {
                    //$xItems = (new ClassWorkItems())->where('class_work', $item->id)->where('subject', $subject)->findAll();
                    //if ($xItems) {
                        //foreach ($xItems as $xItem) {
                            if ($sub = $item->getSubmission($student->id)) {
                                $score += $sub->score;
                            }
                        //}
                    //}
                }
                foreach ($quizesObj as $item) {
                    //$qItems = (new QuizItems())->where('quiz', $item->id)->where('subject', $subject)->findAll();
                    //if ($qItems) {
                        //foreach ($qItems as $qItem) {
                            if ($sub = $item->getSubmission($student->id)) {
                                $score += $sub->score;
                            }
                        //}
                    //}
                }
//                foreach ($examsObj as $item) {
//                    //$eItems = (new CatExamItems())->where('cat_exam', $item->id)->where('subject', $subject)->findAll();
//                    //if ($eItems) {
//                        //foreach ($eItems as $eItem) {
//                            if ($sub = $item->getSubmission($student->id)) {
//                                $score += $sub->score;
//                            }
//                        //}
//                    //}
//                }
                //$vv = 0;
                foreach ($assObj as $item) {
                    $aItem = (new Submissions())->where('student_id', $student->id)->where('assignment_id', $item->id)->get()->getRow();
                    if($aItem) {
                        $score += $aItem->marks_awarded;
                        //$vv = $aItem->marks_awarded;
                    }
                }

//                $out_of = $cwsOutOf + $exOutOf + $qzOutOf + $assOutOf;
                $out_of = $cwsOutOf + $qzOutOf + $assOutOf;


                if ($out_of >= $score) {
                    if ($score != 0) {
                        //$score = ($score / $out_of) * $base_numeral;
                    }

                    $the_db = [
                        'session'   => active_session(),
                        'name'  => isset($name) ? $name : 'Assessment-'.date('d.m.Y'),
                        'student' => $student->id,
                        'class' => $student->class->id,
                        'section' => $student->section->id,
                        'subject'   => $subject,
                        'items' => '',
                        'semester'  => $semester,
                        'score' => number_format($score, 2),
                        //'out_of' => $base_numeral
                        'out_of' => $out_of
                    ];

                    $resModel = new AssessmentResults();
                    try {
                        if($existing = $resModel->where('student', $student->id)->where('session', active_session())->where('class', $student->class->id)
                            ->where('subject', $subject)->where('semester', $semester)->get()->getRow()
                        ) {
                            $the_db['id'] = $existing->id;
                            $the_db['semester'] = $semester;
                        }
                        if ($resModel->save($the_db)) {
                            $msg = json_encode([
                                'status'    => 'success',
                                'title'     => 'success',
                                'notifyType' => 'toastr',
                                'callback'  => 'getCATS()',
                                'message'   => 'Calculations done successfully'
                            ]);
                        } else {
                            $msg = "Database error";
                            $return = FALSE;
                        }
                    } catch (\ReflectionException $e) {
                        $msg = $e->getMessage();
                        $return = FALSE;
                    }
                } else {
                    $msg = "Math Problem. Score is greater than the total marks";
                    $return = FALSE;
                }
            }
        } else {
            $return = FALSE;
            $msg = "No students found";
        }

        $code = $return ? 200 : 404;
        return $this->response->setStatusCode($code)
            ->setContentType($code == 200 ? 'application/json' : 'text/plain')
            ->setBody($msg);
    }
}