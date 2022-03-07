<?php


namespace App\Controllers\Academic;


use App\Models\Classes;
use App\Models\ExamResults;
use App\Models\Exams;

class QuarterRanking extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Quarter Ranking";
    }

    public function index()
    {

        return $this->_renderPage('Academic/Ranking/quarter/index', $this->data);
    }

    public function analysis()
    {
        return $this->_renderPage('Academic/Ranking/quarter/analysis/index', $this->data);
    }

    public function get()
    {

        $class = $this->request->getPost('class');
        $section = $this->request->getPost('section');
        $quarter = $this->request->getPost('quarter');

        $this->data['class'] = $class;
        $this->data['section'] = $section;
        $this->data['quarter'] = $quarter;

        $class = (new Classes())->find($class);
//        if ($quarter =='yearly_average'){
//            $class = (new Classes())->find($class);
//            if ($class->type=='kg')
//                return view('Academic/Ranking/quarter/average_kg', $this->data);
//                return view('Academic/Ranking/quarter/average', $this->data);
//        }
        if (substr($quarter, 0, 4 ) === "sem_"){
            $this->data['semester'] = explode("sem_",$quarter)[1];
            return view('Academic/Ranking/quarter/get_sem', $this->data);
        }
        if ($class->type=='kg')
        return view('Academic/Ranking/quarter/get_kg', $this->data);
        return view('Academic/Ranking/quarter/get', $this->data);

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


        return view('Academic/Ranking/quarter/analysis/get', $this->data);
    }


}