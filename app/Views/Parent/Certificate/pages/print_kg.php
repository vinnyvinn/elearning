<?php
//$student = (new \App\Models\Students())->find($student);
$section = (new \App\Models\Sections())->find($student->section->id);
$class = (new \App\Models\Classes())->find($student->class->id);
$grading = (new \App\Models\Classes())->find($student->class->id);
$grading = $grading->grading ? json_decode($grading->grading) : [];

//$next_class = (new \App\Models\Classes())->where('weight',$class->weight+1)->where('type',$class->type)->get()->getLastRow();
$semesters = (new \App\Models\Semesters())->where('session',active_session())->findAll();
$student_evaluation = (new \App\Models\StudentEvaluation())->where('student',$student->id)->where('session',active_session())->where('class',$class->id)->where('section',$section->id)->get()->getLastRow();
$saved_evaluations = isset($student_evaluation->remark) ? json_decode($student_evaluation->remark) : [];
$std_id = $student->id;
$conduct = (new \App\Models\YearlyStudentEvaluation())->where('student',$student->id)->where('class',$class->id)->where('section',$section->id)->where('session',active_session())->get()->getLastRow();
$home = (new \App\Models\Homeroom())->where('student',$std_id)->where('class',$class->id)->where('section',$section->id)->where('session',active_session())->get()->getLastRow();
if(isset($session) && is_numeric($session)) {
    $session = (new \App\Models\Sessions())->find($session);
} else {
    $session = getSession();
}
$teacher = (new \App\Models\Teachers())->find($section->advisor->id);
$directors = (new \App\Models\Teachers())->where('session',active_session())->where('is_director',1)->findAll();
$dir = '';
foreach ($directors as $director){
    if ($director->director_classes) {       if (in_array($class->id, json_decode($director->director_classes))) {
            $dir = $director;
        }
    }
}

$first_sem_comm = '';
$second_sem_comm = '';
if (!empty(($home))){
    $first_sem_comm = (new \App\Models\StudentComment())->find($home->first_sem_comment)['description'];
    $second_sem_comm = (new \App\Models\StudentComment())->find($home->second_sem_comment) ? (new \App\Models\StudentComment())->find($home->second_sem_comment)['description'] : '';
}
$promotion = (new \App\Models\Promotion())->where('student',$std_id)->where('old_class',$class->id)->where('old_section',$section->id)->where('old_session',$student->session->id)->get()->getLastRow();

?>
<html lang="en"
<head>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">

    <style>
        hr {
            color: #0000004f;
            margin-top: 5px;
            margin-bottom: 5px
        }
        td{
            padding-top: 3%;
            padding-bottom: 3%;
        }
        .table td, .table th{
            white-space: revert !important;
        }

        /*size: A4 landscape;*/
        /*margin: 2mm 2mm 2mm 2mm !imseportant;*/
        /*padding: 0 !important;*/
        /*size: 400mm 500mm;*/
        /*height: 100%;*/
        @media print {
            @page {
                size: A4 landscape;
                margin-top: 1mm !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-bottom: 0 !important;
                size: 400mm 320mm;
                height: 100%;

            }
            body {
                margin-top: 1mm !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-bottom: 0 !important;
            }
        }
        .cool td,.cool th{
            border: 1px solid !important;
        }

        .hr_report{
            display: block;
            height: 2px !important;
            background: transparent;
            width: 100%;
            border:1px solid #aaa !important;
            margin-top: 18px !important;
        }
        .fmbm{
            font-family: Bookman Old Style !important;
        }
        .fs18{
            font-size: 18px !important;
        }
        .fmcamb{
            font-family: Cambria, Georgia, serif;
        }
        .fs22{
            font-size: 22px !important;
        }
        .fs12{
            font-size: 20px !important;
        }

        .fs14{
         font-size: 20px !important;
        }
        .fs16{
          font-size: 16px !important;
        }
        .card .academic .vinn th{

            overflow-x: hidden;
        }
    </style>
    <title>Yearly Certificate</title>
</head>
<?php
if (empty($grading)):
    ?>
    <h4 class="text-red">Please ensure to enter KG grading scale first</h4>
<?php
else:
?>
<body id="download" style="overflow-x: hidden">
<div id="pannation-project">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div style="border: 10px solid #0680d1;margin-left: -1%;margin-right: 0.5%;max-width: 50.5%!important;height: 1098px !important;" class="col-md-6">
                        <div class="row">
                            <div class="col-md-6" style="height: 220px">
                                <div class="table-responsive" style="margin-top: 5%;">
                                    <table class="table table-bordered table-striped" style="height: 100%">
                                        <thead>
                                        <tr>
                                            <th style="background: lightgrey;border: 1px solid !important;font-size: 20px !important;"><b>Grading Systems </b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0">90 - 100 = A</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0">80 - 89 = B</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0">70 - 79 = C</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0">60 - 69 = D</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0">< 60 = F</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6" style="height: 220px">
                                <div class="table-responsive" style="margin-top: 5%">
                                    <table class="table table-bordered table-striped" style="height: 100%">
                                        <thead>
                                        <tr>
                                            <th style="padding-left:0.5%;padding-right:0;background: lightgrey;border: 1px solid !important;font-size: 20px !important;letter-spacing: unset !important;"><b>Behavior and Basic Skills</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0;padding-left: 0.5%;padding-right: 0">E = Excellent</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0;padding-left: 0.5%;padding-right: 0">V.G = Very Good</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0;padding-left: 0.5%;padding-right: 0">G = Good</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0;padding-left: 0.5%;padding-right: 0">S = Satisfactory</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0;padding-left: 0.5%;padding-right: 0"> NI = Needs Improvement</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr class="hr_report" style="margin-top: 18px !important;border: 1px solid !important;display: none">
                        <br>
                        <div style="margin-left: 4%">
                            <table>
                                <tr>
                                    <th><h3 style="border-bottom: 2px solid;margin-top: 5%;font-size: 30px">1<sup>st</sup> Semester</h3></th>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0;font-size: 36px;">
                                        <ul>
                                            <li style="list-style-type: circle;font-family: Nyala;">Homeroom Teacher's Comments</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0.5%;font-size: 24px">
                                        <?php if (!empty($home)):?>
                                            <b style="text-decoration: underline"><?php echo $first_sem_comm;?></b>
                                        <?php else:?>
                                            __________________________________________
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td  style="padding-left: 15%;margin-top: 0;padding-top: 0;padding-bottom: 0.5%;font-size: 36px"><?php if (!empty($teacher->signature)):?>
                                            Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0.5%;padding-bottom: 0.5%;font-size: 36px;">
                                        <ul>
                                            <li style="list-style-type: circle;font-family: Nyala"> Parent's Comments</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        __________________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 9%; font-size: 36px;margin-top:0;margin-bottom:0;font-family: Nyala">
                                        Sign____________
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-bottom: 2%;">
                                <tr>
                                    <th><h3 style="border-bottom: 2px solid;margin-top: 5%;font-size: 30px">2<sup>nd</sup> Semester</h3></th>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0;font-size: 36px;">
                                        <ul>
                                            <li style="list-style-type: circle;font-family: Nyala">Homeroom Teacher's Comments</li>

                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;margin-bottom: 0.5%;font-size: 24px;">
                                        <?php if (isset($second_sem_comm)):?>
                                            <b style="text-decoration: underline;"><?php echo $second_sem_comm;?></b>
                                        <?php else:?>
                                            __________________________________________
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 15%;font-family: Nyala;padding-top: 0;padding-bottom: 0.5%;font-size: 36px;"><?php if (!empty($teacher->signature)):?>
                                            Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                        <?php else:?>
                                            <p style="font-family: Nyala;font-size: 36px;"> Sign____________</p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0.5%;padding-bottom: 0.5%;font-size: 36px;">
                                        <ul>
                                            <li style="list-style-type: circle;font-family: Nyala"> Parent's Comments</li>

                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0.5%;padding-bottom: 0.5%">
                                        __________________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 15%; font-size: 36px;font-family: Nyala;padding-top: 2%;padding-bottom: 0.5%">
                                        Sign____________
                                    </td>
                                    <td></td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div style="border: 10px solid #0680d1;padding-left: 0;padding-right: 0;max-width: 49%!important;margin-left: 1%;height: 1098px !important;" class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <tr>
                                        <th class="text-center" style="padding-bottom: 0;padding-top: 0.5%"><img src="<?php echo base_url()?>/uploads/files/<?php echo get_option('website_logo')?>" width="200"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 30px;padding-top: 0;padding-bottom:0;border: none">አስፓየር ዩዝ አካዳሚ</th>
                                    </tr>
                                    <tr>
                                     <th class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 30px;padding-top: 0;padding-bottom: 0;border: none">Aspire Youth Academy</th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <table class="table" style="border: 3px solid; font-family: Nyala">
                            <tr>
                                <td  class="text-center" style="margin-left: 8%;padding-top: 0.5%;padding-bottom: 0.5%;border: none;font-size: 28px"><b>Address:</b> Addis Ababa: N/S/L Sub-City  &nbsp;&nbsp;&nbsp;Woreda: 11</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="margin-left: 25%;padding-top: 0.5%;padding-bottom: 0.5%;border: none;font-size: 28px"><b>Tel:</b> 011-4-71-11-24</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="margin-left: 19%;padding-top: 0.5%;padding-bottom: 0.5%;border: none;font-size: 28px"><b>Telegram: </b>AYA online education channel</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="margin-left: 20%;padding-top: 0.5%;padding-bottom: 2%;border: none;font-size: 28px"><b>Website: </b> <a href="http://www.aspireschoolet.com/">http://www.aspireschoolet.com/</a></td>
                            </tr>
                        </table>
                        <table class="table">
                            <tr>
                                <th style="text-align: center;text-decoration: underline; font-size: 36px !important;font-family: Bookman;padding-top: 0.5%;padding-bottom: 0.5%;border: none">የተማሪ ውጤት መግለጫ</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;text-decoration: underline; font-size: 36px !important;font-family: Bookman;padding-top: 0.5%;border: none">Student's Report Card</th>
                            </tr>
                        </table>

                        <div class="products p-2">
                            <table class="table">
                                <tbody>
                                <?php
                                $_age = floor((time() - strtotime($student->profile->usermeta('dob'))) / 31556926);
                                ?>
                                <tr class="content">
                                    <td style="font-size: 28px;padding-top: 1%;padding-bottom: 1%;border: none"><span style="font-family: Cambria">Student's Name:</span> <b style="font-family: Bookman;text-decoration: underline"><?php echo $student->profile->name;?></b></td>
                                </tr>
                                <tr class="content">
                                    <td style="font-size: 28px;padding-top: 1%;padding-bottom: 1%;border: none"><span style="font-family: Cambria">Sex:</span> <b style="font-family: Bookman;text-decoration: underline"><?php echo $student->profile->gender;?></b> &nbsp;&nbsp;&nbsp;<span style="font-family: Cambria">Age:</span> <b style="font-size: 20px !important;font-family: Bookman;">________________________</b> </td>
                                </tr>
                                <tr class="content">
                                    <td style="font-size: 28px;padding-top: 1%;padding-bottom: 1%;border: none"><span  style="font-family: Cambria">Grade: </span><b style="font-size: 20px !important;font-family: Bookman;text-decoration: underline"><?php echo $student->class->name;?></b> &nbsp;&nbsp;&nbsp; <span style="font-family: Cambria">Academic Year:</span> <b style="font-family: Bookman;text-decoration: underline"><?php echo date('Y',strtotime($session->created_at));?>/21 G.C</b></td>
                                </tr>
                                <tr class="content">
                                    <td style="font-size: 28px;padding-top: 1%;padding-bottom: 1%;border: none">
                                        <span style="font-family: Cambria">Promoted to: <b style="font-family: Bookman"><?php echo '_________';?></b></span>

                                        &nbsp;&nbsp;<span style="font-family: Cambria">Detained at: <b style="font-family: Bookman"><?php echo '_________';?></b></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr class="new1" style="border: 3px solid #525f7f !important;">
                        <div class="products p-2">
                            <table class="table">
                                <tbody>
                                <tr class="content">
                                    <th style="font-size: 28px">Homeroom Teacher's </th>
                                    <th style="font-size: 28px;">Director's Sign</th>
                                </tr>
                                <tr class="content">
                                    <td style="font-size: 28px">
                                        <span>Name:</span> <b><?php echo $teacher->profile->name;?></b>
                                        <br>
                                        <?php if (!empty($teacher->signature)):?>
                                            <div style="padding-top: 2%;margin-top: 0">Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="80px" height="80px"></div>
                                        <?php endif;?>
                                    </td>
                                    <td style="font-size: 28px;">
                                        <?php if (!empty($dir)):?>
                                            <div style="padding-top: 10%">Sign: <img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" width="80px" height="80px"></div>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p style="page-break-before: always">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6" style="border: 10px solid #0680d1;margin-left: -1%;margin-right: 0.5%;max-width: 50.5%!important;height: 1098px !important;">
                         <?php
                        if($session && $student) {
                            $semesters = $session->semesters;
                            if(isset($semesters) && is_array($semesters) && count($semesters) > 0) {
                                $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
                                ?>
                                <div class="table-responsive" style="margin-top: 2%;margin-bottom: 2%;overflow-x: hidden">
                                    <table class="table" style="overflow-x: hidden">
                                        <thead class="cool">
                                        <tr>
                                            <th colspan="7" style="background: lightgrey;text-align: center;border: 1px solid;"><span style="font-size: 24px;">Academic Result Record</span></th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" width="20%" style="font-size: 10px;margin-right: -10%">Subject</th>
                                            <th colspan="2" style="font-size: 10px;margin-right: -10%">1<sup>st</sup> Semester</th>
                                            <th colspan="2" style="font-size: 10px;margin-right: -10%">2<sup>nd</sup> Semester</th>
                                            <th colspan="2" style="font-size: 10px;margin-right: -10%">Yearly Average</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid;font-size: 10px;padding-top: 2%;padding-bottom: 1%">Score</th>
                                            <th style="border: 1px solid;font-size: 10px;padding-top: 2%;padding-bottom: 1%">Grade</th>
                                            <th style="border: 1px solid;font-size: 10px;padding-top: 2%;padding-bottom: 1%">Score</th>
                                            <th style="border: 1px solid;font-size: 10px;padding-top: 2%;padding-bottom: 1%">Grade</th>
                                            <th style="border: 1px solid;font-size: 10px;padding-top: 2%;padding-bottom: 1%">Score</th>
                                            <th style="border: 1px solid;font-size: 10px;padding-top: 2%;padding-bottom: 1%">Grade</th>
                                        </tr>
                                        </thead>
                                        <tbody class="cool">
                                        <?php
                                        $total_marks = [];
                                        $count = 0;
                                        $n = 0;
                                        $data_arr = [];
                                        $data_arr_opt = [];
                                        $subjects = $student->class->subjects;
                                        foreach ($subjects as $subj) {
                                            $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[0]->id, $subj->id,$student->section->id);
                                            $res2 = $resultsModel->getSemesterTotalResultsPerSubject($semesters[1]->id, $subj->id,$student->section->id);
                                            if($subj->optional == 0) {
                                                if(!isset($data_arr[$subj->id])){
                                                    $data_arr[$subj->id] = $res+$res2;
                                                }else {
                                                    $data_arr[$subj->id] += ($res+$res2);
                                                }
                                            }else if ($subj->optional == 1){
                                                if(!isset($data_arr_opt[$subj->id])){

                                                    $data_arr_opt[$subj->id] = $res+$res2;
                                                }else {
                                                    $data_arr_opt[$subj->id] += ($res+$res2);
                                                }
                                            }

                                        }

                                        foreach ($subjects as $subject) {
                                            if ($subject->optional == 0)
                                                $count++;
                                            $n++;?>
                                            <tr>
                                                <td style="border: 1px solid;font-size: 20px;padding-top: 0;padding-bottom: 0"><?php echo $subject->name; ?></td>
                                                <?php
                                                foreach ($semesters as $item) {
                                                    $result = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subject->id,$student->section->id);
                                                    ?>
                                                    <td style="border: 1px solid;font-size: 20px;padding-top: 0;padding-bottom: 0"><?php

                                                        if($result && !empty($result) && $subject->optional == 0) {
                                                            echo $result;
                                                        } elseif ($subject->optional == 0) {
                                                            echo '-';
                                                        }
                                                        if ($subject->optional == 0) {
                                                            if (isset($total_marks[$item->id])) {
                                                                $total_marks[$item->id] += is_numeric($result) ? $result : 0;
                                                            } else {
                                                                $total_marks[$item->id] = is_numeric($result) ? $result : 0;
                                                            }
                                                        }
                                                        elseif($subject->optional == 1) {
                                                            echo $result;
                                                        }
                                                        ?></td>
                                                    <td style="border: 1px solid;font-size: 20px;padding-top: 0;padding-bottom: 0"><?php echo getScore2($grading,$result);?></td>
                                                    <?php
                                                }
                                                ?>
                                                <td style="border: 1px solid;font-size: 20px;padding-top: 0;padding-bottom: 0">
                                                    <?php if ($subject->optional == 0):?>
                                                        <?php echo $result = isset($data_arr[$subject->id]) ? $data_arr[$subject->id] / count($semesters) : '-';?>
                                                    <?php else:?>
                                                        <?php
                                                        $res = (new \App\Models\ClassSubjects())->find($subject->id);
                                                        $result = $data_arr_opt[$subject->id] / count($semesters);
                                                        echo $result;
                                                        ?>
                                                    <?php endif;?>
                                                </td>
                                                <td style="border: 1px solid;font-size: 20px;padding-top: 0;padding-bottom: 0"><?php echo getScore2($grading,$result);?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php if (!empty($saved_evaluations)):?>
                                        <div>
                                            <?php $category = (new \App\Models\KGCategory())->get()->getRow();?>
                                                <span class="text-center mt-1" style="font-size:24px;font-family: 'LLucida Calligraphy';">

                                                    Development Area: <b><?php echo $category->name;?></b>
                                                </span>
                                                <?php if ($category->sub_category_id):
                                                    $sub_categories = json_decode($category->sub_category_id);
                                                    $sub_categories = array_slice($sub_categories,0,2);
                                                    foreach ($sub_categories as $sub):
                                                        $evaluations_ = (new \App\Models\KGEvaluation())->where('sub_category_id',$sub)->findAll();
                                                        $sub_category = (new \App\Models\KGSubCategory())->find($sub);
                                                        ?>
                                                        <br>
                                                        <table class="table" style="overflow-x: hidden;overflow-y: hidden">
                                                           <thead class="cool">
                                                           <tr>
                                                               <td class="text-center" width="70%" style="padding-top: 0;padding-bottom: 0;font-size: 24px;margin-right: -5%">Attribute</td>
                                                               <td colspan="2" class="text-center" style="padding-top: 0;padding-bottom: 0;font-size: 24px;margin-right: -5%">Level</td>
                                                           </tr>
                                                           <tr>
                                                               <th style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -5%"><?php echo $sub_category['name'];?></th>
                                                               <td class="text-center" style="padding-top: 0;padding-bottom: 0;font-size: 18px;margin-right: -5%"><b>Sem-I</b></td>
                                                               <td class="text-center" style="padding-top: 0;padding-bottom: 0;font-size: 18px;margin-right: -5%"><b>Sem-II</b></td>
                                                           </tr>
                                                           </thead>
                                                          <tbody class="cool">
                                                          <?php
                                                          $k=0;
                                                          foreach ($evaluations_ as $key => $evaluation):
                                                              $k++;
                                                              ?>
                                                              <tr>
                                                                  <td style="padding-top: 0;padding-bottom: 0;font-size: 24px"><?php echo $evaluation['description'];?></td>
                                                                  <td style="padding-top: 0;padding-bottom: 0;font-size: 24px">
                                                                      <?php echo getEvaluationItem($saved_evaluations,$evaluation['id'],1,$k);?>
                                                                  </td>
                                                                  <td style="padding-top: 0;padding-bottom: 0;font-size: 24px">
                                                                      <?php echo getEvaluationItem($saved_evaluations,$evaluation['id'],2,$k);?>
                                                                  </td>
                                                              </tr>
                                                          <?php endforeach;?>
                                                          </tbody>

                                                        </table>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                        </div>
                                    <?php endif;?>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-danger">No semesters found</div>
                                <?php
                            }
                        } else {?>
                            <div class="alert alert-danger">
                                Invalid session or student
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                    <div class="col-md-6" style="border: 10px solid #0680d1;max-width: 49%!important;margin-left: 1%;height: 1098px !important;">
                        <?php if (!empty($saved_evaluations)):?>
                        <div>
                            <?php $categories = (new \App\Models\KGCategory())->findAll();?>
                            <?php foreach ($categories as $category):?>
                                <span class="text-center" style="font-size: 24px;font-family: 'LLucida Calligraphy';">

                                    Development Area: <b><?php echo $category['name'];?></b>
                                </span>
                                <?php if ($category['sub_category_id']):
                                    $sub_categories = json_decode($category['sub_category_id']);
                                    $sub_categories = array_slice($sub_categories,2);
                                    foreach ($sub_categories as $sub):
                                        $evaluations_ = (new \App\Models\KGEvaluation())->where('sub_category_id',$sub)->findAll();
                                        $sub_category = (new \App\Models\KGSubCategory())->find($sub);
                                        ?>
                                        <br>
                                        <table class="table" style="width: 100%">
                                            <thead class="cool">
                                            <tr>
                                                <td class="text-center" style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%">Attribute</td>
                                                <td colspan="2" class="text-center" style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%;width: 33%">Level</td>
                                            </tr>
                                            <tr>

                                                <th style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%"><?php echo $sub_category['name'];?></th>
                                                <td  style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%"><b>Sem-I</b></td>
                                                <td  style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%"><b>Sem-II</b></td>
                                            </tr>

                                            </thead>
                                           <tbody class="cool">
                                           <?php
                                           $k=0;
                                           foreach ($evaluations_ as $key => $evaluation):
                                               $k++;
                                               ?>
                                               <tr>
                                                   <td style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%"><?php echo $evaluation['description'];?></td>
                                                   <td style="padding-top: 0;padding-bottom: 0;font-size: 20px">
                                                       <?php echo getEvaluationItem($saved_evaluations,$evaluation['id'],1,$k);?>
                                                   </td>
                                                   <td style="padding-top: 0;padding-bottom: 0;font-size: 20px">
                                                       <?php echo getEvaluationItem($saved_evaluations,$evaluation['id'],2,$k);?>
                                                   </td>
                                               </tr>
                                           <?php endforeach;?>
                                           </tbody>
                                        </table>
                                    <?php endforeach;?>
                                <?php else:
                                    $evaluations = (new \App\Models\KGEvaluation())->where('category_id',$category['id'])->findAll();
                                    ?>
                                    <br>
                                    <table class="table" style="width: 100%">
                                       <thead class="cool">
                                       <tr>
                                           <th style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%;">Attribute</th>
                                           <th class="text-center" style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%">Sem-I</th>
                                           <th class="text-center" style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%">Sem-II</th>
                                       </tr>
                                       </thead>
                                      <tbody class="cool">
                                      <?php
                                      $k2=0;
                                      foreach ($evaluations as $key2 => $evaluation):
                                          $k2++;
                                          ?>
                                          <tr>
                                              <td style="padding-top: 0;padding-bottom: 0;font-size: 20px;margin-right: -10%"><?php echo $evaluation['description'];?></td>
                                              <td style="padding-top: 0;padding-bottom: 0;font-size: 20px">
                                                  <?php echo getEvaluationItem($saved_evaluations,$evaluation['id'],1,$k2);?>
                                              </td>
                                              <td style="padding-top: 0;padding-bottom: 0;font-size: 20px">
                                                  <?php echo getEvaluationItem($saved_evaluations,$evaluation['id'],2,$k2);?>
                                              </td>
                                          </tr>
                                      <?php endforeach;?>
                                      </tbody>
                                    </table>
                                <?php endif;endforeach;?>
                        </div>
                        <?php endif;?>
                   </div>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php
endif;
?>
<script>

    window.print();
        setTimeout(() => {
            window.close();
        },3000)


</script>

