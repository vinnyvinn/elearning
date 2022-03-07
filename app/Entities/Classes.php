<?php


namespace App\Entities;


use App\Models\Assignments;
use App\Models\ClassSubjects;
use App\Models\Departure;
use App\Models\Events;
use App\Models\Exams;
use App\Models\Notes;
use App\Models\Payments;
use App\Models\Requirements;
use App\Models\Sections;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\Subjectteachers;
use App\Models\Teachers;
use CodeIgniter\Entity;

class Classes extends Entity
{
    public function getTeachers()
    {
        $teachers_ids = (new Subjectteachers())->select('teacher_id')->where('class_id', $this->attributes['id'])->where('session',active_session())->groupBy('teacher_id')->get()->getResult('array');
        if($teachers_ids) {
            return (new Teachers())->whereIn('id', function() {
                return (new Subjectteachers())->select('teacher_id')->where('class_id', $this->attributes['id'])->where('session',active_session())->groupBy('teacher_id');
            })->findAll();
        }

        return FALSE;
    }

    public function students() {
        $model = (new Students())->where('class', $this->attributes['id'])
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id');
              if (getDepartedIds() && count(getDepartedIds()) > 0)
                  $model->whereNotIn("students.id",getDepartedIds());
            $model->where('students.class !=',null)
            ->orderBy('students.section,users.surname, users.first_name,users.last_name');
            return $model->findAll();
    }

    public function activeStudents() {
        return (new Students())->where('class', $this->attributes['id'])->where('students.active',1)
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id')
            ->where('class !=',null)
            ->orderBy('students.section,users.surname, users.first_name,users.last_name')
            ->findAll();
    }



    public function departureStudents() {
        return (new Departure())->where('class', $this->attributes['id'])->where('students.count',1)
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id')
            ->where('class !=',null)
            ->orderBy('users.surname, users.first_name,users.last_name')
            ->findAll();
    }

    public function getDepartureBoys()
    {
        $counter = 0;
        $students = (new Departure())->where('class', $this->attributes['id'])->where('count',1)->findAll();
     foreach ($students as $departureStudent){
         $student = (new Students())->find($departureStudent->student);
         if ($student->profile->gender =='Male')
           $counter++;
     }
     return $counter;
    }
    public function getDepartureGirls()
    {
        $counter = 0;
        $students = (new Departure())->where('class', $this->attributes['id'])->where('count',1)->findAll();
        foreach ($students as $departureStudent){
            $student = (new Students())->find($departureStudent->student);
            if ($student->profile->gender =='Female')
                $counter++;
        }
        return $counter;
    }

    public function getBoys()
    {
        $counter = 0;
        foreach ($this->students() as $student){
            if ($student->profile->gender =='Male')
                $counter++;
        }
        return $counter;
    }
    public function getGirls()
    {
        $counter = 0;
        foreach ($this->students() as $student){
            if ($student->profile->gender =='Female')
                $counter++;
        }
        return $counter;
    }

    public function getBoysav49andBelow($semester)
    {
        $counter = 0;
        $students_arr = array();
        foreach ($this->students() as $student){
            $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
            $subjects = $student->class->subjects;
            if (count($subjects) > 0) {
                foreach ($subjects as $subject) {
                    $result = $resultsModel->getSemesterTotalResultsPerSubject($semester, $subject->id);

                    if (!empty($result) && is_numeric($result)) {
                        if (!isset($students_arr[$student->id])) {
                            $students_arr[$student->id] = $subject->optional ==0 ? $result : 0;
                        } else {
                            $students_arr[$student->id] += $subject->optional ==0 ? $result : 0;
                        }
                    }
                }
            }
        }
        return $counter;
    }
    public function getStudents()
    {
        return $this->students();
    }

    public function sections() {
        return (new Sections())->where('class', $this->attributes['id'])->findAll();
    }

    public function getSingleSection($id)
    {
        return (new Sections())->find($id);
    }

    public function getSections()
    {
        return $this->sections();
    }

    public function subjects() {
        //return (new Subjects())->where('class', $this->attributes['id'])->findAll();
        return (new ClassSubjects())->where('class', $this->attributes['id'])->findAll();
    }

    public function getSubjects()
    {
        return $this->subjects();
    }

    public function getSubjectsCount()
    {
        $rows =  (new ClassSubjects())->where('class', $this->attributes['id'])->where('optional',0)->where('session',$this->attributes['session'])->findAll();
        return count($rows);
    }

    public function getNotes()
    {
        return (new Notes())->where('class', $this->attributes['id'])
            ->where("is_elibrary",0)
            ->orderBy('id', 'DESC')
            //->get()->getCustomResultObject('App\Entities\Note');
            ->findAll();
    }
    public function getNotese()
    {
        return (new Notes())->where('class', $this->attributes['id'])
            ->where("is_elibrary",1)
            ->orderBy('id', 'DESC')
            //->get()->getCustomResultObject('App\Entities\Note');
            ->findAll();
    }

    public function getAssignments()
    {
        return (new Assignments())->where('class', $this->attributes['id'])
            ->orderBy('id', 'DESC')
            //->get()->getCustomResultObject('App\Entities\Note');
            ->findAll();
    }

    public function getEvents()
    {
        return (new Events())->where('class', $this->attributes['id'])->findAll();
    }

    public function getPayments()
    {
        return (new Payments())->where('class', $this->attributes['id'])
            ->orWhere('class', '')
            ->orWhere('class', NULL)
            ->findAll();
    }
    public function getRequirements()
    {
        return (new Requirements())->where('class', $this->attributes['id'])
            ->orWhere('class', '')
            ->orWhere('class', NULL)
            ->findAll();
    }
}