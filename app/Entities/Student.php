<?php


namespace App\Entities;


use App\Models\Accounting;
use App\Models\AssessmentResults;
use App\Models\Assessments;
use App\Models\AssignmentItems;
use App\Models\Assignments;
use App\Models\AssignmentSubmissions;
use App\Models\AssignmentSubmissionsMarked;
use App\Models\Contacts;
use App\Models\Events;
use App\Models\Exams;
use App\Models\FeeCollection;
use App\Models\Files;
use App\Models\FinalGrade;
use App\Models\Payments;
use App\Models\Requirements;
use App\Models\Sections;
use App\Models\Sessions;
use App\Models\Subjectteachers;
use CodeIgniter\Entity;

class Student extends Entity
{
    public function setOptional($val = '')
    {
        if (!$val || $val != 1 || $val = '') {
            $this->attributes['optional'] = 0;
        }

        $this->attributes['optional'] = 1;

        return $this;
    }

    public function getProfile()
    {
        return (new \App\Models\User())->find($this->attributes['user_id']);
    }

    public function getPic()
    {
       if (!get_option('transcript_photo'.$this->attributes['id'])){
         if ($this->profile->avatar)
             return $this->profile->avatar;
           return base_url('assets/img/default.jpg');
       }
       return base_url('uploads/files/'.get_option('transcript_photo'.$this->attributes['id']));
    }
    public function getParent()
    {
        return (new \App\Models\User())->find($this->attributes['parent']);
    }
    public function getParentid()
    {
        return $this->attributes['parent'];
    }
    public function getPhoto() {
        if(!get_option('letter_photo')) {
            return base_url('uploads/files/'.get_option('letter_photo').''.$this->attributes['id']);
        } else {
            return base_url('assets/img/default.jpg');
        }
        return null;
    }
    public function getContact()
    {
        //return (new \App\Models\User())->find($this->attributes['contact']);
        return (new Contacts())->find($this->attributes['contact']);
    }
    public function getContactid()
    {
        return $this->attributes['contact'];
    }

    public function getClass()
    {
        return (new \App\Models\Classes())->find($this->attributes['class']);
    }

    public function getSession()
    {
        return (new Sessions())->find($this->attributes['session']);
    }

    public function getSection()
    {
        return (new Sections())->find($this->attributes['section']);
    }

    public function getActive()
    {
        if ($this->attributes['active'] == 1) {
            return true;
        }

        return false;
    }

    public function getTeachers()
    {
        return (new Subjectteachers())->where('class_id', $this->attributes['class'])->groupBy("subject_id")->findAll();
    }

    public function getTotalFees()
    {
        $model = new Accounting();
        $total = $model->selectSum('amount', 'total')
            ->where('session', active_session())
            ->groupStart()
            ->where('class', $this->attributes['class'])
            ->orWhere('class', NULL)
            ->orWhere('class', '')
            ->groupEnd()
            ->groupStart()
            ->where('section', $this->attributes['section'])
            ->orWhere('section', NULL)
            ->orWhere('section', '')
            ->groupEnd()
            ->groupStart()
            ->where('student', $this->attributes['id'])
            ->orWhere('student', NULL)
            ->orWhere('student', '')
            ->groupEnd()
            ->orderBy('created_at', 'DESC')
            ->get()->getFirstRow()->total;
        //->getCompiledSelect();

        //dd($total);
        return $total;
    }

    public function getPaidFees()
    {
        $model = new FeeCollection();
        $paid = $model->selectSum('amount', 'paid')
            ->where('session', active_session())
            ->where('student', $this->attributes['id'])
            ->get()->getFirstRow()->paid;

        return $paid;
    }

    public function getFees()
    {
        $model = new Accounting();
        $total = $model->select('*')
            ->where('session', active_session())
            ->groupStart()
            ->where('class', $this->attributes['class'])
            ->orWhere('class', '')
            ->orWhere('class', NULL)
            ->groupEnd()
            ->groupStart()
            ->where('section', $this->attributes['section'])
            ->orWhere('section', '')
            ->orWhere('section', NULL)
            ->groupEnd()
            ->groupStart()
            ->where('student', $this->attributes['id'])
            ->orWhere('student', '')
            ->orWhere('student', NULL)
            ->groupEnd()
            ->orderBy('id', 'DESC')
            ->get()->getResultObject();

        return $total;
    }

    public function getExams($semester = false)
    {
        $model = new Exams();
        $ex = $model->where('session', active_session())
            ->groupStart()
                ->where('class', NULL)
                ->orWhere('class', $this->attributes['class'])
                ->orWhere('class', '')
            ->groupEnd()
            ->groupStart()
                ->where('section', NULL)
                ->orWhere('section', '')
                ->orWhere('section', $this->attributes['section'])
            ->groupEnd()
            ->orderBy('created_at', 'DESC');

        if($semester) {
            $ex->where('semester', $semester);
        }

        $ex = $ex->findAll();

        return $ex;
    }

    public function getCats()
    {
        $model = new Exams();
        $ex = $model->where('session', active_session())
            ->where('class', $this->attributes['class'])
            ->groupStart()
            ->where('section', NULL)
            ->orWhere('section', '')
            ->orWhere('section', $this->attributes['section'])
            ->groupEnd()
            ->findAll();

        return $ex;
    }

    public function getAssignments()
    {
        $model = new Assignments();
        $ass = $model->where('class', $this->attributes['class'])
            //->where('section', $this->attributes['section'])
            ->findAll();

//        return $this->attributes['section'];
        return $ass;
    }
    public function getWrittenAssignments()
    {
        $model = new AssignmentItems();
        $model->where('published',1);
        $ass = $model->where('class', $this->attributes['class'])
            ->findAll();
        return $ass;
    }
    public function getAssignmentsw()
    {
        $model = new Assignments();
        $ass = $model->where('class', $this->attributes['class'])
            //->where('section', $this->attributes['section'])
            ->findAll();

//        return $this->attributes['section'];
        return $ass;
    }

    public function getFeePayment()
    {
       return (new FeeCollection())->where('student', $this->attributes['id'])->orderBy('id', 'DESC')->get()->getResultObject();
    }

    public function getEvents()
    {
        $event_month = date('m');
        $events = (new \App\Models\Events())
//                        ->like('starting_date', '-'.$event_month.'-', 'both')
//                        ->orLike('ending_date', '-'.$event_month.'-', 'both')
//                        ->orLike('starting_date', '/'.$event_month.'/', 'both')
//                        ->orLike('ending_date', '/'.$event_month.'/', 'both')
                        ->orderBy('id', 'DESC')->findAll();
        return $events;
    }

    public function getRank()
    {
        //$rank = 1;
        try {
            $builder = (new FinalGrade());
            $res = $builder->select('*, SUM(score) as total')
                //->where('student_id', $this->attributes['id'])
                ->where('student', $this->attributes['id'])
                ->where('class', $this->attributes['class'])
                ->where('section', $this->attributes['section'])
                ->where('session', active_session())
                //->where('month', date('m'))
                ->findAll();
            if($res) {
                //Sort students by total
                usort($res, function($a, $b) {
                    return $a['total'] - $b['total'];
                });

                array_reverse($res, false);

                $key = array_search($this->attributes['id'], array_column($res, 'student'));

                $rank = $key+1;
                return $rank.'<sup class="text-white">'.ordinal($rank).'</sup>';
            }
        } catch (\Exception $e) {
            \Config\Services::logger()->log('1', $e->getMessage());
        }

        return '-';
    }

    public function getAssScore()
    {
        $sum = (new AssessmentResults())->where('student', $this->attributes['id'])->selectSum('score', 'sum_score')->get()->getLastRow()->sum_score;
        $class = $this->getClass();
        if($class) {
            $subjects = count($class->subjects);
        } else {
            $subjects = 1;
        }

        $score = $sum/$subjects;
        return number_format($score, 2);
    }

    public function getAssRank()
    {
        //$rank = 1;
        $builder = (new AssessmentResults());
        $class_students = (new \App\Models\Classes())->where('id', $this->attributes['class'])->countAllResults(true);

        try {
            $res = $builder->select('*, SUM(score) as total')
                //->where('student', $this->attributes['id'])
                ->where('class', $this->attributes['class'])
                ->where('section', $this->attributes['section'])
                ->where('session', active_session())
                ->get()->getResultArray();

            if($res) {
                usort($res, function($a, $b) {
                    return $a['total'] - $b['total'];
                });

                $key = array_search($this->attributes['id'], array_column($res, 'student'));

                $rank = $key+1;
                //$rank = $res->rank;
                return $rank.'<sup class="">'.ordinal($rank).'</sup>';
            }
        } catch (\Exception $e) {
            \Config\Services::logger()->critical($e->getMessage());
        }

        return $class_students;
    }

    // Dont use this in non-home school environment
    public function getAnnualRank()
    {
        $builder = (new Assessments());
        $class_students = (new \App\Models\Classes())->where('id', $this->attributes['class'])->countAllResults(true);
        try {
            $res = $builder->select('*, RANK() OVER ( PARTITION BY month ORDER BY total) rank')
                ->groupBy('month')
                ->where('student_id', $this->attributes['id'])
                ->where('class', $this->attributes['class'])
                ->where('section', $this->attributes['section'])
                ->where('session', active_session())
                ->findAll();

            return $res;
        } catch (\Exception $e) {
            \Config\Services::logger()->log('1', $e->getMessage());
        }

        return $class_students;
    }

    public function getSemesterFinalGrades($session = FALSE, $semester = FALSE)
    {
        if(!$semester) return FALSE;
        if(!$session && active_session()) $session = active_session();

        $fgModel = new FinalGrade();
        return $fgModel->where('student', $this->attributes['id'])->where('session', $session)->where('semester', $semester)->findAll();
    }

    public function getSemesterFinalRank($session = FALSE, $semester = FALSE)
    {
        $builder = new FinalGrade();
        $class_students = (new \App\Models\Classes())->where('id', $this->attributes['class'])->countAllResults(true);
        $res = $builder->select('*, SUM(score) as total')
            ->where('student', $this->attributes['id'])
            ->where('class', $this->attributes['class'])
            ->where('section', $this->attributes['section'])
            ->where('session', $session)
            ->where('semester', $semester)
            ->findAll();

        if($res) {
                usort($res, function($a, $b) {
                    return $a['total'] - $b['total'];
                });

                $key = array_search($this->attributes['id'], array_column($res, 'student'));

                $rank = $key+1;
                return $rank;
                
                //return $rank.'<sup class="">'.ordinal($rank).'</sup>';
            }
            
        return $class_students;
    }

    public function getFiles()
    {
        return (new Files())->where('type', 'student')->where('uid', $this->attributes['id'])->findAll();
    }

    //Payments and Requirements
    public function getRequirements()
    {
        $model = new Requirements();
        $ex = $model->where('session', active_session())
            ->groupStart()
            ->where('class', NULL)
            ->orWhere('class', $this->attributes['class'])
            ->orWhere('class', '')
            ->groupEnd()
            ->groupStart()
            ->where('section', NULL)
            ->orWhere('section', '')
            ->orWhere('section', $this->attributes['section'])
            ->groupEnd()
            ->orderBy('id', 'DESC')
            ->findAll();

        return $ex;
    }

    public function getPayments()
    {
        $model = new Payments();
        $ex = $model->where('session', active_session())
            ->groupStart()
            ->where('class', NULL)
            ->orWhere('class', $this->attributes['class'])
            ->orWhere('class', '')
            ->groupEnd()
            ->groupStart()
            ->where('section', NULL)
            ->orWhere('section', '')
            ->orWhere('section', $this->attributes['section'])
            ->groupEnd()
            ->orderBy('id', 'DESC')
            ->findAll();

        return $ex;
    }

    public function submission($assignment)
    {
     return (new AssignmentSubmissions())->where('student_id',$this->attributes['id'])->where('assignment_id',$assignment)->first();
    }

    public function marks_scored($assignment)
    {
        if($submission = $this->submission($assignment)){

          $scores = (new AssignmentSubmissionsMarked())->where('submission_id',$submission->id)->where('student_id',$this->attributes['id'])->first();
          if ($scores)
              return $scores->scored;
        }
        return '-';
    }
}