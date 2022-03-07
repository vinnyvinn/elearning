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

$subjects = $student->class->subjects;
$students = $class->students;
$quarters = getSession()->quarters;

$sem_arr = array();
$quarter_arr = array();
$yearly_arr = array();
$sem_av_arr = array();
foreach ($students as $student){
    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
    foreach ($quarters as $quarter){
        foreach ($subjects as $subject){
            $result = $resultsModel->getQuarterTotalResultsPerSubject($quarter->id, $subject->id);
            if (!isset($quarter_arr[$quarter->id.'-'.$student->id])){
                $quarter_arr[$quarter->id.'-'.$student->id] = is_numeric($result) ? $result : 0;
            }else{
                $quarter_arr[$quarter->id.'-'.$student->id] += is_numeric($result) ? $result : 0;
            }

            if (!isset($sem_arr[$quarter->semester->id.'-'.$student->id])){
                $sem_arr[$quarter->semester->id.'-'.$student->id] = is_numeric($result) ? $result : 0;
            }else{
                $sem_arr[$quarter->semester->id.'-'.$student->id] += is_numeric($result) ? $result : 0;
            }
            if (!isset($yearly_arr[$student->id])){
                $yearly_arr[$student->id] = is_numeric($result) ? $result : 0;
            }else{
                $yearly_arr[$student->id] += is_numeric($result) ? $result : 0;
            }
        }
    }
}
$final_quarter_ranks = array();
$final_sem_ranks = array();
foreach ($quarters as $quarter){
    $obtain_rank = array();
    foreach ($quarter_arr as $key => $q){
        if (substr(explode('-',$key)[0],0,$quarter->id) ==$quarter->id){
            array_push($obtain_rank,array($key=>$q));
        }
    }
    $res = array_flatten($obtain_rank);
   array_push($final_quarter_ranks,array_rank($res));
}


foreach ($semesters as $semester){
    $obtain_rank = array();
    foreach ($sem_arr as $key => $q){
        if (substr(explode('-',$key)[0],0,$semester->id) ==$semester->id){
            array_push($obtain_rank,array($key=>$q));
        }
    }
   $res = array_flatten($obtain_rank);
    array_push($final_sem_ranks,array_rank($res));
}

$final_quarter_ranks = array_flatten($final_quarter_ranks);
$final_sem_ranks = array_flatten($final_sem_ranks);
$yearly_rank = array_rank($yearly_arr);
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
        /*.academic td{*/
        /*    border: 1px solid black !important;*/
        /*}*/
        .academic{
            overflow-x: hidden !important;
            overflow-y: hidden !important;
        }
    </style>
    <title>Yearly Certificate</title>
</head>
<body id="download">
<div id="pannation-project">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div style="border: 10px solid #0680d1;margin-right: 0;max-width: 51%!important;height: 1098px !important;" class="col-md-6">
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
                                    <td  style="padding-left: 15%;margin-top: 0;padding-top: 0;padding-bottom: 0.5%;font-size: 36px"><?php if (is_homeroom_sign_sem1($std_id) && !empty($teacher->signature)):?>
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
                                    <td style="padding-left: 15%;font-family: Nyala;padding-top: 0;padding-bottom: 0.5%;font-size: 36px;"><?php if (is_homeroom_sign_sem2($std_id) && !empty($teacher->signature)):?>
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
                    <div style="border: 10px solid #0680d1;padding-left: 0;padding-right: 0;max-width: 48%!important;margin-left: 1%;height: 1098px !important;" class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <tr>
                                        <th class="text-center" style="padding-bottom: 0;padding-top: 0.5%"><img src="<?php echo base_url()?>/images/aspire-logo.jpeg" width="200"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 30px;padding-top: 0;padding-bottom:0;border: none">አስፓየር ዩዝ አካዳሚ</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 30px;padding-top: 0;padding-bottom: 0;border: none"><?php echo get_option('id_school_name')?></th>
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
                <div class="row">
                    <div class="col-md-6" style="border: 10px solid #0680d1;margin-right: 0;margin-left: 0%;max-width: 51%!important;height: 1098px !important;">
                        <div class="table-responsive" style="margin-top: 2%;margin-bottom: 2%;">
                            <table class="table academic" style="overflow-x: hidden;width: 80%">

                                <thead class="thead-light">
                                <tr><?php
                                    $cols = count($semesters) + count($quarters) +2;
                                    ?>
                                    <th colspan="<?php echo $cols?>" class="text-center" style="border: 1px solid black">ACADEMIC RESULT RECORD</th>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid black;padding-right: 9px;padding-left: 4px;width: 0%">Subject</td>
                                    <?php
                                    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
                                    $semesters = getSession()->semesters;
                                    foreach ($semesters as $semester):?>
                                    <?php foreach ($semester->quarters as $quarter):
                                    ?>
                                    <td style="border: 1px solid black;padding-right: 9px;padding-left: 4px;"><?php echo $quarter->name;?></td>
                                    <?php endforeach;?>
                                     <td style="border: 1px solid black;padding-right: 9px;padding-left: 4px;"><?php echo $semester->name;?></td>
                                    <?php endforeach;?>
                                    <td style="border: 1px solid black;padding-right: 9px;padding-left: 4px;"><b>Yearly Average</b></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total_quarters = array();
                                $total_semesters = array();
                                $subjects = $student->class->subjects;
                                foreach ($subjects as $subject):
                                    ?>
                                <tr>
                                  <td style="border: 1px solid black"><?php echo $subject->name;?></td>
                                    <?php
                                    $yearly_total =0;
                                    foreach ($semesters as $semester):
                                        $result_arr = array();

                                 foreach ($semester->quarters as $quarter):?>
                                  <td style="border: 1px solid black">
                                      <?php
                                      $result = $resultsModel->getQuarterTotalResultsPerSubject($quarter->id, $subject->id);
                                      $yearly_total += is_numeric($result) ? $result : 0;
                                      if (!isset($result_arr[$semester->id]))
                                          $result_arr[$semester->id] =  is_numeric($result) ? $result : 0;
                                      else
                                          $result_arr[$semester->id] +=  is_numeric($result) ? $result : 0;

                                      if (!isset($total_quarters[$quarter->id]))
                                          $total_quarters[$quarter->id] = is_numeric($result) ? $result : 0;
                                      else
                                          $total_quarters[$quarter->id] +=  is_numeric($result) ? $result : 0;

                                      echo is_numeric($result) ? $result : '-';
                                      ?></td>
                                  <?php endforeach;?>
                                    <td style="border: 1px solid black">
                                        <?php
                                        if (!isset($total_semesters[$semester->id]))
                                          $total_semesters[$semester->id] = number_format($result_arr[$semester->id]/count($semester->quarters),2);
                                        else
                                            $total_semesters[$semester->id] += number_format($result_arr[$semester->id]/count($semester->quarters),2);

                                        echo number_format($result_arr[$semester->id]/count($semester->quarters),2);
                                        ?>
                                    </td>
                                    <?php endforeach;?>
                                    <td style="border: 1px solid black">
                                     <?php echo number_format($yearly_total/count($quarters),2);?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                <tr>
                                    <td style="border: 1px solid black"><b>Total</b></td>
                                    <?php
                                    $total_sem = 0;
                                    foreach ($semesters as $semester):
                                        $total_sem +=$total_semesters[$semester->id];
                                    foreach ($semester->quarters as $quarter):?>
                                    <td style="border: 1px solid black"><b><?php echo $total_quarters[$quarter->id];?></b></td>
                                    <?php endforeach;?>
                                        <td style="border: 1px solid black"><b><?php echo $total_semesters[$semester->id];?></b></td>
                                    <?php endforeach;?>
                                    <td style="border: 1px solid black">
                                        <b>
                                    <?php echo number_format($total_sem/count($semesters),2);?>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                 <td style="border: 1px solid black"><b>Average</b></td>
                                    <?php
                                    foreach ($semesters as $semester):
                                        $total_q = 0;
                                        foreach ($semester->quarters as $quarter):
                                            $total_q += number_format($total_quarters[$quarter->id]/count($subjects),2);
                                            ?>
                                            <td style="border: 1px solid black"><b><?php echo number_format($total_quarters[$quarter->id]/count($subjects),2);?></b></td>
                                        <?php endforeach;?>
                                        <td style="border: 1px solid black"><b><?php echo number_format($total_q/count($semester->quarters),2);?></b></td>
                                    <?php endforeach;?>
                                    <td style="border: 1px solid black">
                                        <b>
                                          <?php
                                          echo number_format(($total_sem/count($subjects))/count($semesters),2)
                                          ?>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black"><b>Rank</b></td>
                                    <?php  foreach ($semesters as $semester):
                                        foreach ($semester->quarters as $quarter):?>
                                            <td style="border: 1px solid black"><b><?php echo $final_quarter_ranks[$quarter->id.'-'.$student->id].'/'.count($students);?></b></td>
                                        <?php endforeach;?>
                                        <td style="border: 1px solid black"><b><?php echo $final_sem_ranks[$semester->id.'-'.$student->id].'/'.count($students);?></b></td>
                                    <?php endforeach;?>
                                    <td style="border: 1px solid black">
                                        <b>
                                          <?php echo $yearly_rank[$student->id] .'/'.count($students);?>
                                        </b>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6" style="border: 10px solid #0680d1;max-width: 48%!important;margin-left: 1%;height: 1098px !important;">
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
                                            ?>
                                            <td style="border: 1px solid;font-size: 18px">
                                                <?php  echo array_values((array)$ev)[0] =='V' ? 'V.G' : array_values((array)$ev)[0] ;?>
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
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0"><?php echo $student_evaluation->second_sem_tardy;?></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0;padding-left: 0;padding-right: 0;word-spacing: -4px">Number of absent days</td>
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0"><?php echo $student_evaluation->first_sem_absent;?></td>
                                    <td style="border: 1px solid;font-size: 26px;padding-top:0;padding-bottom: 0"><?php echo $student_evaluation->second_sem_absent;?></td>
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
</body>

<script>

    window.print();
       setTimeout(() => {
           window.close();
       },3000)


</script>

