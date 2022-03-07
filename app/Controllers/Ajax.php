<?php


namespace App\Controllers;


use App\Models\Classes;
use App\Models\Departments;
use App\Models\Departure;
use App\Models\Quarters;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Sessions;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\Subjectteachers;
use CodeIgniter\Exceptions\PageNotFoundException;

class Ajax extends BaseController
{
    public function __construct()
    {
        //parent::__construct();
    }

    //Get class sections
    public function sections($class_id)
    {
        $html = '';
        $model = new Classes();
        $class = $model->find($class_id);
        if($class) {
            $sections = $class->sections();
            if($sections && count($sections) > 0) {
                $html .= '<option value="">Please select a section</option>';
                foreach ($sections as $section) {
                    $html .= '<option value="'.$section->id.'">'.$section->name.'</option>';
                }
            } else {
                $html = '<option value="">No Sections found for the selected class</option>';
            }
        } else {
            $html = '<option value="">Class not found</option>';
        }

        return $this->response->setBody($html);
    }

    public function classSection($section_id)
    {
        echo json_encode((new Sections())->find($section_id)->class->id);
    }

    public function sessionClasses($session)
    {
        $html = '';
        $session = (new Sessions())->find($session);
        if($session) {
            $classes = $session->classes()->findAll();

            if($classes && count($classes) > 0) {
                $html .= '<option value="">Please select a class</option>';
                foreach ($classes as $section) {
                    $html .= '<option value="'.$section->id.'">'.$section->name.'</option>';
                }
            } else {
                $html = '<option value="">No classes found</option>';
            }
        } else {
            $html = '<option value="">Session was not found</option>';
        }

        return $this->response->setBody($html);
    }

    public function sessionSemesters($session)
    {
        $html = '';
        $session = (new Sessions())->find($session);
        if($session) {
            $semesters = $session->semesters;

            if($semesters && count($semesters) > 0) {
                $html .= '<option value="">Please select a semester/term</option>';
                foreach ($semesters as $section) {
                    $html .= '<option value="'.$section->id.'">'.$section->name.'</option>';
                }
            } else {
                $html = '<option value="">No semesters found</option>';
            }
        } else {
            $html = '<option value="">Session was not found</option>';
        }

        return $this->response->setBody($html);
    }

    public function sessionClassStudent($session, $class, $section)
    {
        $model = new Classes();
        $class = $model->find($class);
        if(!$class) {
            throw new PageNotFoundException("Class not found");
        }

        $s = (new Sections())->find($section);
        if(!$s) {
            throw new PageNotFoundException("Class section not found");
        }
        $students = $s->students;

        return view('Classes/Promotion/table', ['students' => $students]);
    }
    public function sectionStudents($section)
    {
        $assignment = $this->request->getPost('assignment');
        $s = (new Sections())->find($section);
        if(!$s) {
            throw new PageNotFoundException("Class section not found");
        }
        $students = $s->students;

        return view('Academic/Assignment/students', ['students' => $students,'assignment'=>$assignment]);
    }

    public function removeSemester()
    {
       set_option('turn_off_semester_2',$this->request->getPost('val'));
       echo  json_encode('success');
    }
    public function getSemester($id)
    {
        $sem = (new Quarters())->find($id);
        echo json_encode($sem->semester->id);
    }

    public function sessionClassStudentMove($session, $class, $section)
    {
       $model = new Classes();
        $class = $model->find($class);
        if(!$class) {
            throw new PageNotFoundException("Class not found");
        }

        $s = (new Sections())->find($section);
        if(!$s) {
            throw new PageNotFoundException("Class section not found");
        }
        $students = $s->students;

        return view('Classes/Promotion/table_move', ['students' => $students]);
    }

    public function sessionClassStudentDepart($session, $class, $section)
    {

        $model = new Classes();
        $class = $model->find($class);
        if(!$class) {
            throw new PageNotFoundException("Class not found");
        }

        $s = (new Sections())->find($section);
        if(!$s) {
            throw new PageNotFoundException("Class section not found");
        }
        $students = $s->students;

        return view('Admin/Students/departure/table', ['students' => $students]);
    }

    public function getDepartures($session)
    {

        $class = $this->request->getPost('class');
            if ($this->request->getPost('session') =='all'){
               $students = $this->students($class);
            }

            else{
                $model = new Departure();
                if ($class !='all')
                  $model->where('class',$class);
                $deps = $model->where('session',$this->request->getPost('session'))->where('type','departure')->groupBy("student")->findAll();

                if ($deps){
                    $stids = array_column($deps,'student');
                    $students = (new \App\Models\Students())->whereIn('id',$stids)->findAll();
                }else{
                    $students =[];
                }
            }
            $this->data['students'] = $students;


        return view('Admin/Students/departure/table_deps', $this->data);
    }

    public function students($class)
    {
        $model = new Departments();
        if ($class !="all")
            $model->where("class",$class);
        $deps = $model->select('student')->where('type','departure')->findAll();

        if ($deps){
            $stids = array_column($deps,'student');
            return (new Students())->whereIn('id',$stids)->findAll();
        }
        return [];
    }

    public function sessionClassStudentFeeCollection($session, $class, $section)
    {
        $model = new Classes();
        $class = $model->find($class);
        if(!$class) {
            throw new PageNotFoundException("Class not found");
        }

        $s = (new Sections())->find($section);
        if(!$s) {
            throw new PageNotFoundException("Class section not found");
        }

        $students = $s->students;

        return view('Admin/Accounting/students', ['students' => $students]);
    }

    public function feeInformation($session, $class, $section)
    {
        $model = new Classes();
        $class = $model->find($class);
        if(!$class) {
            throw new PageNotFoundException("Class not found");
        }

        $s = (new Sections())->find($section);
        if(!$s) {
            //throw new PageNotFoundException("Class section not found");
            $students = $class->students();
        } else {
            $students = $s->students;
        }

        return view('Admin/Accounting/history_table', ['students' => $students]);
    }

    public function subjects($class)
    {
        $html = '';
        $model = new Classes();
        $class = $model->find($class);
        if($class) {
            $sections = $class->subjects();
            if($sections && count($sections) > 0) {
                $html .= '<option value="">Please select a subject</option>';
                foreach ($sections as $section) {
                    $html .= '<option value="'.$section->id.'">'.$section->name.'</option>';
                }
            } else {
                $html = '<option value="">No Subjects found for the selected class</option>';
            }
        } else {
            $html = '<option value="">Class not found</option>';
        }

        return $this->response->setBody($html);
    }
    public function subjectsNew($class)
    {
        $html = '';
        $model = new Classes();
        $class = $model->find($class);
        if($class) {
            $sections = $class->subjects();
            if($sections && count($sections) > 0) {
                $html .= '<option value="">Please select a subject</option>';
                $html .= '<option value="all">view all</option>';
                foreach ($sections as $section) {
                    $html .= '<option value="'.$section->id.'">'.$section->name.'</option>';
                }
            } else {
                $html = '<option value="">No Subjects found for the selected class</option>';
            }
        } else {
            $html = '<option value="">Class not found</option>';
        }

        return $this->response->setBody($html);
    }
    public function subjectsTeacher($class,$teacher)
    {
        $html = '';
        $subjects = (new Subjectteachers())->where("teacher_id",$teacher)->where('class_id',$class)->groupBy('subject_id')->findAll();

        $model = new Classes();
        $class = $model->find($class);
        if($class) {
            //$sections = $class->subjects();
            if($subjects && count($subjects) > 0) {
                $html .= '<option value="">Please select a subject</option>';
                foreach ($subjects as $section) {
                    $html .= '<option value="'.$section->subject_id.'">'.$section->subject->name.'</option>';
                }
            } else {
                $html = '<option value="">No Subjects found for the selected class</option>';
            }
        } else {
            $html = '<option value="">Class not found</option>';
        }

        return $this->response->setBody($html);
    }

    public function subjectsSection($section,$teacher)
    {
        $html = '';
        if($section) {
            $subjects = (new Subjectteachers())->where('teacher_id',$teacher)->where('section_id',$section)->groupBy('subject_id')->findAll();
            if($subjects && count($subjects) > 0) {
                $html .= '<option value="">Please select a subject</option>';
                foreach ($subjects as $sub) {
                    $html .= '<option value="'.$sub->subject->id.'">'.$sub->subject->name.'</option>';
                }
            } else {
                $html = '<option value="">No Subjects found for the selected class</option>';
            }
        } else {
            $html = '<option value="">Class not found</option>';
        }

        return $this->response->setBody($html);
    }

    public function classSubjects($section)
    {
        $html = '';
        $model = new Classes();
        $class = (new Sections())->find($section)->class->id;
        $class = $model->find($class);
        if($class) {
            $sections = $class->subjects();
            if($sections && count($sections) > 0) {
                $html .= '<option value="">Please select a subject</option>';
                foreach ($sections as $section) {
                    $html .= '<option value="'.$section->id.'">'.$section->name.'</option>';
                }
            } else {
                $html = '<option value="">No Subjects found for the selected class</option>';
            }
        } else {
            $html = '<option value="">Class not found</option>';
        }

        return $this->response->setBody($html);
    }
}