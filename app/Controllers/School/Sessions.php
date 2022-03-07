<?php


namespace App\Controllers\School;


use App\Controllers\AdminController;
use App\Entities\Session;
use App\Entities\Subjectteacher;
use App\Models\ClassSubjects;
use App\Models\Departure;
use App\Models\Sections;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\Subjectteachers;
use App\Models\Teachers;
use App\Models\User;

class Sessions extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['sessions'] = (new \App\Models\Sessions())->orderBy('id', 'DESC')->findAll();
        return $this->_renderPage('Admin/Sessions/index', $this->data);
    }

    public function create()
    {
        $s = (new \App\Models\Sessions())->find(2);
      //  var_dump($s->classess);
//        foreach ($s->classess as $cl){
//            var_dump($cl->id);
//            exit();
//        }
//        echo '<pre>';
        if ($this->request->getPost()) {
//            //start test
//            $import_session = $this->request->getPost('import_session');
//            $max_class = (new \App\Models\Classes())->selectMax('weight')->where('session', $import_session)->get()->getRow()->weight;
//            $students = (new Students())->where('session', $import_session)->where('class !=', null)->findAll();
//
//            $classes_arr = array();
//            $departure_class = array();
//
//            foreach ($students as $student) {
//                $class = (new \App\Models\Classes())->find($student->class->id);
//                var_dump($class->weight .' max -> '.$max_class);
//                if ($class->weight != $max_class) {
//                    $next_class = (new \App\Models\Classes())->where('weight =', $class->weight + 1)->where('session', $import_session)->get()->getRow();
//var_dump($next_class->id);
//                    if (isset($next_class->id) && isset($sections_class_arr[$next_class->id]['class']) && isset($sections_class_arr[$next_class->id]['section']))
//                        array_push($classes_arr, array('class' => $sections_class_arr[$next_class->id]['class'], 'student' => $student->id, 'section' => $sections_class_arr[$next_class->id]['section']));
//                } else {
//                    array_push($departure_class, $student->id);
//                }
//            }
//
//            echo '-----------';
//            var_dump($classes_arr);
//            echo "++++++++";
//            var_dump($max_class);
//            var_dump('sess--> '.$import_session);
//            exit();
//
//            //end test
            $session_id = '';
            $entity = new Session();
            $entity->fill($this->request->getPost());
            $model = new \App\Models\Sessions();
            $import_students = $this->request->getPost('students');
            $import_teachers = $this->request->getPost('subject_teachers');

            if ($model->save($entity)) {
                $session_id = $model->getInsertID();
                $return = true;
                do_action('session_created', $model->getInsertID());
                //Import data from a session
                $import_session = $this->request->getPost('import_session');
                if($import_session && is_numeric($import_session)) {
                    $ss = $model->find($import_session);
                    if($ss) {
                        $import_classes = $this->request->getPost('classes');
                        if($import_classes && $import_classes == 1) {
                            $classes = $ss->classess;
                            $class_model = new \App\Models\Classes();
                            $sections_class_arr = array();
                            foreach ($classes as $class) {
//                                if ($import_teachers && $import_teachers == 1) {
//                                    if (($class->teachers) && count($class->teachers) > 0) {
//                                        array_push($teachers_arr, $class->teachers);
//                                    }
//                                }
                                $to_db = [
                                    'name' => $class->name,
                                    'session' => $session_id,
                                    'weight' => $class->weight,
                                    'type' => $class->type,
                                    'grading' => $class->grading,
                                    'pass_mark' => $class->pass_mark
                                ];

                                if ($class_model->insert($to_db)) {
                                    $class_id = $class_model->getInsertID();

                                    //Sections
                                    // $import_sections = $this->request->getPost('sections');
                                    // if ($import_sections && $import_sections == 1) {
                                    $sections_model = new Sections();
                                    $section_id = '';
                                    foreach ($class->sections() as $key => $section) {
                                        $to_db = [
                                            'name' => $section->name,
                                            'class' => $class_id,
                                            'maximum_students' => $section->maximum_students,
                                            'advisor' => $section->advisorid,
                                            'session' => $session_id,
                                        ];
                                        if ($key == 0) {
                                            $sections_model->insert($to_db);
                                            $section_id = $sections_model->getInsertID();
                                        } else {
                                            $sections_model->save($to_db);
                                        }
                                    }
                                    $sections_class_arr[$class->id] = array('class' => $class_id, 'section' => $section_id);

                                    //    }
                                    //Subjects
                                    $import_subjects = $this->request->getPost('subjects');
                                    if ($import_subjects && $import_subjects == 1) {
                                        $subjects_model = new ClassSubjects();
                                        foreach ($class->subjects() as $subject) {
                                            $to_db = [
                                                'subject' => $subject->subject,
                                                'class' => $class_id,
                                                'pass_mark' => $subject->pass_mark,
                                                'optional' => $subject->optional,
                                                'grading' => $subject->grading,
                                                'session' => $session_id,
                                            ];
                                            if ($subjects_model->save($to_db)) {
//                                                $import_teachers = $this->request->getPost('subject_teachers');
//                                                if($import_teachers && $import_teachers == 1 && $import_sections && $import_sections == 1) {
//                                                    $ct_model = new Subjectteachers();
//                                                    $extSub = $ct_model->where(['subject_id', $subject->id, 'class_id' => $class->id])->find();
//                                                    if($extSub) {
//                                                        unset($extSub->id);
//                                                        try {
//                                                            $ct_model->insert($extSub->toArray());
//                                                        } catch (\ReflectionException $e) {
//                                                            //TODO: Catch this exception
//                                                        }
//                                                    }
//                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            //Import Students
                            if (isset($import_students)) {
                                $max_class = (new \App\Models\Classes())->selectMax('weight')->where('session', $import_session)->get()->getRow()->weight;
                                $students = (new Students())->where('session', $import_session)->where('class !=', null)->findAll();
                                $classes_arr = array();
                                $departure_class = array();
                                foreach ($students as $student) {
                                    $class = (new \App\Models\Classes())->find($student->class->id);
                                    if ($class->weight != $max_class) {
                                        $next_class = (new \App\Models\Classes())->where('weight =', $class->weight + 1)->where('session', $import_session)->get()->getRow();
                                        if (isset($next_class->id) && isset($sections_class_arr[$next_class->id]['class']) && isset($sections_class_arr[$next_class->id]['section']))
                                          array_push($classes_arr, array('class' => $sections_class_arr[$next_class->id]['class'], 'student' => $student->id, 'section' => $sections_class_arr[$next_class->id]['section']));
                                    } else {
                                        array_push($departure_class, $student->id);
                                    }
                                }

                                $student_model = new Students();
                                foreach ($classes_arr as $key => $val) {
                                    $student_model->createStudentNew($val['student'], $session_id, $val['section'], $val['class']);
                                }
                                $departure_model = new Departure();
                                foreach ($departure_class as $key => $departure) {
                                    $stud = (new Students())->find($departure);
                                    $to_db = [
                                        'student' => $departure,
                                        'class' => isset($stud->class) ? $stud->class->id : '',
                                        'session' => $import_session
                                    ];
                                    if ( $departure_model->save($to_db)){
                                        $std_data = array('id' => $departure, 'active' => 0);
                                        $student_model->save($std_data);
                                    }
                                }
                            }


                            if ($import_teachers && $import_teachers == 1) {
                              $teachers =  (new \App\Models\Sessions)->find($import_session)->teachers;
                            if (count($teachers) > 0) {

                                $subjects_data = array();
                                foreach ($teachers as $item) {
                                    if ((new Teachers())->find($item->id)) {
                                        $inserted = (new Teachers())->createTeacherNew($item->id, $session_id);
                                        array_push($subjects_data, array('teacher_old' => $item->id, 'teacher_new' => $inserted));
                                    }
                                }

                                $subjects = (new Subjectteachers())->where('session', $import_session)->findAll();
                                foreach ($subjects as $subject) {
                                    foreach ($subjects_data as $sub) {
                                        if ($sub['teacher_old'] == $subject->teacher_id) {
                                            $to_db = array('teacher_id' => $sub['teacher_new'], 'class_id' => $subject->class_id, 'section_id' => $subject->section_id, 'subject_id' => $subject->subject_id, 'session' => $session_id);
                                            $db = \Config\Database::connect();
                                            $builder = $db->table('subject_teacher');
                                            $builder->insert($to_db);
                                        }
                                    }

                                }
                                //Import Semesters
                                $semesters = (new \App\Models\Semesters())->where('session', $import_session)->findAll();
                                $model = new \App\Models\Semesters();
                                foreach ($semesters as $semester) {
                                    $opening_date = date('m/d/Y', strtotime($semester->closing_date . ' + 2 days'));
                                    $to_db = array('session' => $session_id, 'name' => $semester->name, 'opening_date' => date('m/d/Y', strtotime($semester->closing_date . ' + 2 days')), 'closing_date' => date('m/d/Y', strtotime("+4 months", strtotime($opening_date))));
                                    $model->save($to_db);
                                }
                            }
                        }
                        }
                    }

                }
              //  if ($this->request->getPost('session_type') && isset($session_id) && ($this->request->getPost('session_type') == 1 || $this->request->getPost('session_type') == 2)){
                //    return $this->response->redirect(site_url(route_to('admin.school.quarters.create_semester',$session_id)));
                //}
            } else {
                $return = false;
            }
            $msg = $return ? 'Session added successfully' : implode('<br/>', $model->errors());

            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $return ? 'success' : 'error',
                    'title' => $return ? 'Success' : 'Error!',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $return ? 'window.location.reload()' : false
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $return ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
            }
        }

        return $this->response->redirect(site_url(route_to('admin.sessions.index')));
    }

    public function update()
    {
        if ($this->request->getPost()) {
            $entity = new Session();
            $model = new \App\Models\Sessions();
            $entity->id = $this->request->getPost('id');
            $entity->name = $this->request->getPost('name');
            $entity->year = $this->request->getPost('year');
            if ($model->save($entity)) {
                do_action('session_updated', $entity->id);
                $return = true;
            } else {
                $return = false;
            }
            $msg = $return ? 'Session updated successfully' : implode('<br/>', $model->errors());

            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $return ? 'success' : 'error',
                    'title' => $return ? 'Success' : 'Error!',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $return ? 'window.location.reload()' : false
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $return ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
            }
        }
        return $this->response->redirect(site_url(route_to('admin.sessions.index')))->withInput();
    }

    public function delete($id)
    {
        $model = new \App\Models\Sessions();
        if ($session = $model->find($id)) {
            if ($model->delete($session->id)) {
                do_action('session_deleted', $session->id);
                $return = true;
                $msg = "Session deleted successfully";
            } else {
                $return = false;
                $msg = "Failed to delete session";
            }
        } else {
            $return = false;
            $msg = "Session not found";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $t = $return ? 'success' : 'error';
        $this->session->setFlashData($t, $msg);

        return $this->response->redirect(site_url(route_to('admin.sessions.index')))->withInput();
    }

    public function activate($id)
    {
        $model = new \App\Models\Sessions();
        if ($session = $model->find($id)) {
            //reset
            \Config\Database::connect()->table('sessions')->update(['active' => 0]);
            $session->active = 1;
            if ($model->save($this->request->getPost())) {
                do_action('session_activated', $session->id);
                $return = true;
                $msg = "Session activated successfully";
            } else {
                $return = false;
                $msg = "Failed to activate session";
            }
        } else {
            $return = false;
            $msg = "Session not found";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $t = $return ? 'success' : 'error';
        $this->session->setFlashData($t, $msg);

        return $this->response->redirect(site_url(route_to('admin.sessions.index')))->withInput();
    }
}