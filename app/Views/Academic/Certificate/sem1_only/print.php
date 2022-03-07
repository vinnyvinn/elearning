<?php
//$student = (new \App\Models\Students())->find($student);
$section = (new \App\Models\Sections())->find($student->section->id);
$class = (new \App\Models\Classes())->find($student->class->id);

//$next_class = (new \App\Models\Classes())->where('weight',$class->weight+1)->where('type',$class->type)->get()->getLastRow();
$semesters = (new \App\Models\Semesters())->where('session',active_session())->findAll();
$student_evaluation = (new \App\Models\StudentEvaluation())->where('student',$student->id)->where('session',active_session())->where('class',$class->id)->where('section',$section->id)->get()->getLastRow();
$saved_evaluations = isset($student_evaluation->remark) ? json_decode($student_evaluation->remark) : [];
$std_id = $student->id;
$conduct = (new \App\Models\YearlyStudentEvaluation())->where('student',$student->id)->where('class',$class->id)->where('section',$section->id)->where('session',active_session())->get()->getLastRow();
$home = (new \App\Models\Homeroom())->where('student',$std_id)->where('session',active_session())->get()->getLastRow();
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
$director_s = (new \App\Models\DirectorSign())->where("student",$std_id)->first();
$comment = (new \App\Models\Homeroom())->where('student',$std_id)->where('session',active_session())->where("first_sem_comment !=",null)->where("second_sem_comment !=",null)->first();

$first_sem_comm = '';
$second_sem_comm = '';
if (!empty(($home)) && $home->first_sem_comment){
    $first_sem_comm = (new \App\Models\StudentComment())->find($home->first_sem_comment) ? (new \App\Models\StudentComment())->find($home->first_sem_comment)['description'] : '';
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
                max-width: 100%;

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
<body id="download">
<div id="pannation-project">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row" style="width: 100% !important;">
                    <div style="border: 10px solid #0680d1;margin-left:0;max-width: 750px!important;height: 1130px !important;" class="col-md-6">
                        <div class="row">
                            <div class="col-md-6" style="height: 220px">
                                <div class="table-responsive" style="margin-top: 5%;">
                                    <table class="table table-bordered table-striped" style="height: 100%">
                                        <thead>
                                        <tr>
                                            <th style="background: lightgrey;border: 1px solid !important;font-size: 20px !important;"><b>Grading Systems</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0"><?php echo get_option("grade_a")?:'90 - 100'?> = A</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0"><?php echo get_option("grade_b")?:'80 - 89'?> = B</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0"><?php echo get_option("grade_c")?:'70 - 79'?> = C</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0"><?php echo get_option("grade_d")?:'60 - 69'?> = D</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 26px !important;border: 1px solid !important;padding-top: 0;padding-bottom: 0"><?php echo get_option("grade_f")?:' < 60'?> = F</td>
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
                                        <?php if (isset($home->first_sem_comment)):?>
                                            <b style="text-decoration: underline"><?php echo $first_sem_comm;?></b>
                                        <?php else:?>
                                            __________________________________________
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td  style="padding-left: 15%;margin-top: 0;padding-top: 0;padding-bottom: 0.5%;font-size: 36px;font-family: Nyala"><?php if (is_homeroom_sign_sem1($std_id) && !empty($teacher->signature)):?>
                                            Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                        <?php else:?>
                                            Sign ____________
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
                                        <?php if (isset($home->second_sem_comment)):?>
                                            <b style="text-decoration: underline;"><?php echo $second_sem_comm;?></b>
                                        <?php else:?>
                                            __________________________________________
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 15%;font-family: Nyala;padding-top: 0;padding-bottom: 0.5%;font-size: 36px;">
                                        <?php if (is_homeroom_sign_sem2($std_id) && !empty($teacher->signature)):?>
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
                    <div style="border: 10px solid #0680d1;max-width: 750px!important;margin-left: 2%;height: 1130px !important;margin-right: -10%" class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <tr>
                                        <th class="text-center" style="padding-bottom: 0;padding-top: 0.5%"><img src="<?php echo base_url()?>/uploads/files/<?php echo get_option('website_logo');?>" width="200"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 30px;padding-top: 0;padding-bottom:0;border: none"><?php echo get_option('id_school_name_amharic','አስፓየር ዩዝ አካዳሚ')?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 30px;padding-top: 0;padding-bottom: 0;border: none"><?php echo get_option('id_school_name')?></th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <table class="table" style="border: 3px solid; font-family: Nyala">
                            <tr>
                                <td  class="text-center" style="margin-left: 8%;padding-top: 0.5%;padding-bottom: 0.5%;border: none;font-size: 28px"><b>Address:</b> <?php echo get_option("website_location")?></td>
                            </tr>
                            <tr>
                                <?php
                                $phones = get_option('website_phone') ? json_decode(get_option('website_phone')) : '';
                                if ($phones)
                                    $phones = $phones[0];
                                ?>
                                <td class="text-center" style="margin-left: 25%;padding-top: 0.5%;padding-bottom: 0.5%;border: none;font-size: 28px"><b>Tel:</b> <?php echo $phones;?></td>
                            </tr>
                            <tr>
                                <td class="text-center" style="margin-left: 19%;padding-top: 0.5%;padding-bottom: 0.5%;border: none;font-size: 28px"><b>Telegram: </b><a href="<?php echo get_option("telegram_url","https://telegram.org/")?>"><?php echo get_option("telegram_link")?></a></td>
                            </tr>
                            <tr>
                                <td class="text-center" style="margin-left: 20%;padding-top: 0.5%;padding-bottom: 2%;border: none;font-size: 28px"><b>Website: </b> <a href="<?php echo get_option("website_link")?>"><?php echo get_option("website_link")?></a></td>
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
                                    <td style="font-size: 28px;padding-top: 1%;padding-bottom: 1%;border: none"><span  style="font-family: Cambria">Grade: </span><b style="font-size: 20px !important;font-family: Bookman;text-decoration: underline"><?php echo $student->class->name;?></b> &nbsp;&nbsp;&nbsp; <span style="font-family: Cambria">Academic Year:</span> <b style="font-family: Bookman;text-decoration: underline"><?php echo $session->year;?></b></td>
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
                                        <?php if (!empty($teacher->signature) && is_teacher_sign($std_id)):?>
                                            <div style="padding-top: 10%">Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" style="width: 80px;height: 80px"></div>
                                        <?php else:?>
                                            <div style="padding-top: 10%">Sign: ---------------------</div>
                                        <?php endif;?>
                                    </td>
                                    <td style="font-size: 28px;">
                                        <?php if (is_director_sign($std_id) && !empty($dir)):?>
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
                <div class="row" style="width: 100% !important;">
                    <div style="border: 10px solid #0680d1;margin-left:0.3%;max-width: 750px!important;height: 1130px !important;margin-top: 0.3%" class="col-md-6">
                        <?php
                        if($session && $student) {
                            $semesters = $session->semesters;
                            if(isset($semesters) && is_array($semesters) && count($semesters) > 0) {
                                $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
                                ?>
                                <div class="table-responsive" style="margin-top: 2%;margin-bottom: 2%">
                                    <table class="table academic" style="overflow-x: hidden">

                                        <thead class="thead-light">
                                        <tr>
                                            <th colspan="4" style="background: lightgrey;text-align: center;border: 1px solid;"><span style="font-size: 24px;">Academic Result Record</span></th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid;font-size: 20px;padding-left: 0.5%;padding-right: 0">Subject</th>
                                            <?php
                                            foreach ($semesters as $semester) {
                                                ?>
                                                <th style="border: 1px solid;font-size: 20px;padding-left: 0.5%;padding-right: 0"><?php echo $semester->name; ?></th>
                                                <?php
                                            }
                                            ?>
                                            <th  style="border: 1px solid;font-size: 20px;padding-left: 0.5%;padding-right: 0">Average</th>
                                        </tr>
                                        </thead>
                                        <tbody class="vinn">
                                        <?php
                                        $total_marks = [];
                                        $count = 0;
                                        $n = 0;
                                        $data_arr = [];
                                        $data_arr_opt = [];
                                        $subjects = $student->class->subjects;

                                        foreach ($subjects as $subject) {
                                            if ($subject->optional == 0)
                                                $count++;
                                            $n++;?>
                                            <tr>
                                                <td style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo $subject->name; ?></td>
                                                <?php
                                                foreach ($semesters as $k => $item) {
                                                    ?>
                                                    <td style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php

                                                      if ($k ==0) {
                                                          $result = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subject->id, $student->section->id);
                                                          if ($result && !empty($result) && $subject->optional == 0) {
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
                                                          } elseif ($subject->optional == 1) {
                                                              $res = (new \App\Models\ClassSubjects())->find($subject->id);
                                                              if (!empty($res->grading) && $result) {
                                                                  $grade = json_decode($res->grading);
                                                                  foreach ($grade as $g) {
                                                                      $item = explode('-', $g->scale);
                                                                      if ($result >= min($item) && $result <= max($item)) {
                                                                          echo $g->grade . '(' . $result . ')';
                                                                          break;
                                                                      }
                                                                  }
                                                              } else {
                                                                  echo '-';
                                                              }
                                                          }
                                                      }else
                                                          echo '-';
                                                        ?></td>
                                                    <?php
                                                }
                                                ?>
                                                <td style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0">
                                                    <?php
                                                        echo '-';
                                                        ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <th colspan="" style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0">Conduct</th>
                                            <td style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo isset($conduct->first_sem_evaluation) ? $conduct->first_sem_evaluation : 'A';?></td>
                                            <td style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo '-';?></td>
                                            <td style="border: 1px solid;font-size: 26px"><?php echo '-'?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="" style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0">Total Marks</th>
                                            <?php
                                            $sem = $semesters[0];
                                                ?>
                                                <th style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo $total_marks[$sem->id]; ?></th>
                                                <th style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo '-'; ?></th>

                                            ?>
                                            <td style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo '-'?></td>
                                        </tr>
                                        <tr>
                                             <th colspan="" style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0">Average</th>
                                                <th style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo number_format($total_marks[$sem->id]/$count,2); ?></th>
                                                <th style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo '-' ?></th>
                                               <td style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo '-';?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="" style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0">Rank</th>
                                            <?php
                                            $datasem1_arr_ =  array();
                                            foreach ($section->students as $student) {
                                                $total_marks = 0;
                                                $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
                                                $subjects = $student->class->subjects;

                                                foreach ($subjects as $subj) {
                                                    $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[0]->id, $subj->id,$student->section->id);
                                                    if(!isset($datasem1_arr_[$student->id])){
                                                        $datasem1_arr_[$student->id] = ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                                    }else {
                                                        $datasem1_arr_[$student->id] += ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                                    }
                                                }
                                            }

                                            $ranking = array_rank($datasem1_arr_);?>

                                            <th style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo $ranking[$std_id].'/'.count($section->students); ?></th>
                                            <th style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo '-'; ?></th>
                                            <td style="border: 1px solid;font-size: 28px;padding-top: 0;padding-bottom: 0"><?php echo '-'?></td>
                                        </tr>
                                        </tbody>
                                    </table>
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
                    <div style="border: 10px solid #0680d1;max-width: 750px!important;margin-left: 2%;margin-right:-5%;height: 1130px!important;margin-top: 0.3%" class="col-md-6">
                        <table class="table behavior" style="margin-top: 2%">
                            <thead class="thead-light">
                            <tr>
                                <th colspan="3" style="background: lightgrey;text-align: center;border: 1px solid;font-size: 22px;padding-top: 0.5%;padding-bottom: 0.5%" class="bh"><span style="font-size: 26px">Student's Behavior and Basic Skills Progress Report</span></th>
                            </tr>
                            <tr>
                                <th style="width: 40%;border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%;padding-left: 0;padding-right: 0" class="bh">Traits / Evaluation areas</th>
                                <?php foreach ($semesters as $semester):?>
                                    <th class="bh" style="border: 1px solid;font-size: 16px;padding-top: 0;padding-bottom: 0"><?php echo $semester->name;?></th>
                                <?php endforeach;?>
                            </tr>
                            </thead>
                            <tbody class="cool">
                            <?php if (!empty($saved_evaluations)){?>
                                <?php
                                foreach ($saved_evaluations as $key => $evaluation){
                                    $trait = (new \App\Models\Evaluations())->find($key)
                                    ?>
                                    <tr>
                                        <td style="width: 40%; font-family: Bookman;border: 1px solid;font-size: 26px;padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 0;word-spacing: -4px"><?php echo $trait['description'];?></td>
                                        <?php foreach ($evaluation as $k => $ev) {
                                         $sem = getSession()->semesters[0]->id;
                                            ?>
                                            <td style="border: 1px solid;font-size: 18px">
                                                <?php
                                                if ($sem == $k)
                                                echo array_values((array)$ev)[0] =='V' ? 'V.G' : array_values((array)$ev)[0];
                                                else
                                                    echo '-';
                                                ;?>
                                            </td>
                                        <?php }?>
                                    </tr>
                                <?php }?>
                                <tr>
                                    <th colspan="4" style="text-align: center;border: 1px solid;padding-top: 2%;padding-bottom: 2%"><span style="font-size: 18px">Tardy and Absent</span></th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid;font-size: 26px;padding-left: 0;padding-right: 0;word-spacing: -4px;padding-top:0;padding-bottom: 0">Number of tardy days</td>
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0"><?php echo $student_evaluation->first_sem_tardy;?></td>
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0"><?php echo '-';?></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0;padding-left: 0;padding-right: 0;word-spacing: -4px">Number of absent days</td>
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0"><?php echo $student_evaluation->first_sem_absent;?></td>
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0"><?php echo '-';?></td>
                                </tr>
                            <?php }?>

                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script>
//
   window.print();
       setTimeout(() => {
           window.close();
       },3000)


</script>

