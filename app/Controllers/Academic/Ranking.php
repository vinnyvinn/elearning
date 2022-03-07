<?php


namespace App\Controllers\Academic;


use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\Semesters;
use App\Models\Subjects;

class Ranking extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Semester Ranking";
    }

    public function index()
    {
        return $this->_renderPage('Academic/Ranking/index', $this->data);
    }

    public function analysis()
    {
        return $this->_renderPage('Academic/Ranking/analysis/index', $this->data);
    }

    public function results($class,$semester,$subject)
    {
        $this->data['class'] = (new Classes())->find($class);
        $this->data['semester'] = (new Semesters())->find($semester);
        $this->data['subject'] = (new ClassSubjects())->find($subject);
        $this->data['students'] = $this->data['class']->students;
        return $this->_renderPage('Academic/Ranking/show/index', $this->data);
    }


    public function analysisOthers()
    {
        return $this->_renderPage('Academic/Ranking/analysis/index_others', $this->data);
    }

    public function get()
    {
        $class = $this->request->getPost('class');
        $section = $this->request->getPost('section');
        $semester = $this->request->getPost('semester');

        $this->data['class'] = $class;
        $this->data['section'] = $section;
        $this->data['semester'] = $semester;
        if ($semester =='yearly_average'){
            $class = (new Classes())->find($class);
            if ($class->type=='kg')
                return view('Academic/Ranking/average_kg', $this->data);
                return view('Academic/Ranking/average', $this->data);
        }
        return view('Academic/Ranking/get', $this->data);

    }

    public function semesterPdf($class,$section,$semester)
    {
        $this->data['class'] = $class;
        $this->data['section'] = $section;
        $this->data['semester'] = $semester;
        return view('Academic/Ranking/semester_pdf', $this->data);

    }
    public function semesterPrint($class,$section,$semester)
    {
        $this->data['class'] = $class;
        $this->data['section'] = $section;
        $this->data['semester'] = $semester;
        return view('Academic/Ranking/semester_print', $this->data);

    }
    public function averagePdf($section)
    {
      $this->data['section'] = $section;
      return view('Academic/Ranking/average_pdf', $this->data);
    }
    public function averagePrint($section)
    {
      $this->data['section'] = $section;
      return view('Academic/Ranking/average_print', $this->data);
    }
    public function averageKgPdf($section)
    {
      $this->data['section'] = $section;
      return view('Academic/Ranking/average_kg_pdf', $this->data);
    }

    public function getAnalysis()
    {
        $class = $this->request->getPost('class');
        $semester = $this->request->getPost('semester');
        $male_above_50 = array();
        $male_below_50 = array();
        $female_above_50 = array();
        $female_below_50 = array();

        $cls = (new Classes())->find($class);
        $students = $cls->students;
        $subjects = $cls->subjects;

        foreach ($students as $student){
        $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
        foreach ($subjects as $subject){
         $result = $resultsModel->getSemesterTotalResultsPerSubject($semester, $subject->id);
        if ($result > 50){
         if ($student->profile->gender=='Male'){
              if (isset($male_above_50[$subject->id])){
                  $male_above_50[$subject->id] +=1;
              }else {
                  $male_above_50[$subject->id] = 1;
              }
         }else {
             if (isset($female_above_50[$subject->id])){
                 $female_above_50[$subject->id] +=1;
             }else {
                 $female_above_50[$subject->id] = 1;
             }
         }
        }else {
            if ($student->profile->gender=='Male'){
                if (isset($male_below_50[$subject->id])){
                    $male_below_50[$subject->id] +=1;
                }else {
                    $male_below_50[$subject->id] = 1;
                }
            }else {
                if (isset($female_below_50[$subject->id])){
                    $female_below_50[$subject->id] +=1;
                }else {
                    $female_below_50[$subject->id] = 1;
                }
            }
        }
        }
       }
        $this->data['class'] = $class;
        $this->data['semester'] = $semester;
        $this->data['subjects'] = $subjects;
        $this->data['male_subjects_above_50'] = $male_above_50;
        $this->data['male_subjects_below_50'] = $male_below_50;
        $this->data['female_subjects_above_50'] = $female_above_50;
        $this->data['female_subjects_below_50'] = $female_below_50;


        return view('Academic/Ranking/analysis/get', $this->data);
    }

    public function getAnalysisOthers()
    {
         $this->data['semester'] = $this->request->getPost('semester');
        return view('Academic/Ranking/analysis/get_others', $this->data);
    }


}