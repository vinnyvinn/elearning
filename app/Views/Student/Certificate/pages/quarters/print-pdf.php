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
if (!empty(($home)) && $home->first_sem_comment){
    $first_sem_comm = (new \App\Models\StudentComment())->find($home->first_sem_comment)['description'];
    $second_sem_comm = (new \App\Models\StudentComment())->find($home->second_sem_comment) ? (new \App\Models\StudentComment())->find($home->second_sem_comment)['description'] : '';
}
$promotion = (new \App\Models\Promotion())->where('student',$std_id)->where('old_class',$class->id)->where('old_section',$section->id)->where('old_session',$student->session->id)->get()->getLastRow();

$std_name = $student->profile->name;

$subjects = $student->class->subjects;
$students = $class->students;
$quarters = getSession()->quarters;

$sem_arr = array();
$quarter_arr = array();
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
            width: 100%;
            border:1px solid #aaa !important;
            margin-top: 18px !important;
        }
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
            font-size: 16px !important;
        }

        .fs14{
         font-size: 16px !important;
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
                <div class="row">
                 <div style="border: 10px solid #0680d1;margin-right: 1%;max-width: 49%;height: 720px;" class="col-md-6">
                        <div class="row">
                            <div class="col-md-6" style="height: 200px;">
                                <a style="display: none" href="<?php echo site_url(route_to("admin.academic.yearly_certificate.download-cert",$std_id));?>" class="btn btn-primary">Download File</a>
                                <div class="table-responsive" style="margin-top: 5%;">
                                    <table class="table table-bordered table-striped" style="height: 100%">
                                        <thead>
                                        <tr>
                                            <th style="background: lightgrey;border: 1px solid !important;font-size: 12px !important;"><b>Grading Systems</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">90 - 100 = A</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">80 - 89 = B</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">70 - 79 = C</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">60 - 69 = D</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">< 60 = F</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6" style="height: 200px;">
                                <div class="table-responsive" style="margin-top: 5%">
                                    <table class="table table-bordered table-striped" style="height: 100%">
                                        <thead>
                                        <tr>
                                            <th style="background: lightgrey;border: 1px solid !important;font-size: 10px !important;letter-spacing: unset !important;"><b>Behavior and Basic Skills</b></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">E = Excellent</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">V.G = Very Good</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">G = Good</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">S = Satisfactory</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px !important;border: 1px solid !important;padding-top: 2%;padding-bottom: 2%">NI = Needs Improvement</td>
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
                                    <th><h3 style="border-bottom: 2px solid;margin-top: 2%">1<sup>st</sup> Semester</h3></th>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0;font-size: 20px">
                                        <ul>
                                            <li style="list-style-type: circle;font-family: Nyala;">Homeroom Teacher's Comments</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0">
                                        <?php if (!empty($home)):?>
                                            <b style="text-decoration: underline"><?php echo $first_sem_comm;?></b>
                                        <?php else:?>
                                            __________________________________________
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td  style="padding-left: 15%;margin-top: 0;padding-top: 0;padding-bottom: 0;font-size: 20px"><?php if (!empty($teacher->signature)):?>
                                            Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0;font-size: 20px">
                                        <ul>
                                            <li style="list-style-type: circle;font-family: Nyala"> Parent's Comments</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0">
                                        __________________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 9%; font-size: 20px;padding-top:0;padding-bottom:0;font-family: Nyala">
                                        Sign____________
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-bottom: 2%;">
                                <tr>
                                    <th><h3 style="border-bottom: 2px solid;margin-top: 2%">2<sup>nd</sup> Semester</h3></th>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0;font-size: 20px">
                                        <ul>
                                            <li style="list-style-type: circle;font-family: Nyala">Homeroom Teacher's Comments</li>

                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;margin-bottom: 0">
                                        <?php if (isset($second_sem_comm)):?>
                                            <b style="text-decoration: underline;"><?php echo $second_sem_comm;?></b>
                                        <?php else:?>
                                          __________________________________________
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                 <td style="padding-left: 15%;font-family: Nyala;padding-top: 0;padding-bottom: 0;font-size: 20px"><?php if (!empty($teacher->signature)):?>
                                   Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                        <?php else:?>
                                        <p style="font-family: Nyala"> Sign____________</p>
                                        <?php endif;?>
                                 </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0.5%;padding-bottom: 0;font-size: 20px">
                                        <ul>
                                            <li style="list-style-type: circle;font-family: Nyala"> Parent's Comments</li>

                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;padding-bottom: 0">
                                        __________________________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 15%; font-size: 20px;font-family: Nyala;padding-top: 0;padding-bottom: 0">
                                        Sign____________
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                 <div style="border: 10px solid #0680d1;padding-left: 0;padding-right: 0;max-width: 49%!important;margin-left: 1%;height: 720px !important;" class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                    <table class="table">
                                        <tr>
                                            <td style="padding-top: 0;padding-bottom: 0;border: none" class="text-center"><img src="<?php echo base_url()?>/images/aspire-logo.jpeg" width="150"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 26px;padding-top: 0;padding-bottom: 0;border: none">አስፓየር ዩዝ አካዳሚ</t</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 26px;padding-top: 0;padding-bottom: 0;border: none">Aspire Youth Academy</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 26px;padding-top: 0;padding-bottom: 0;border: none">(KG- Grade 12)</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                          <table class="table" style="border: 3px solid; font-family: Nyala">
                              <tr>
                                 <td style="padding-top: 0;padding-bottom: 0;font-size: 22px;border: none;padding-right: 0;"><b style="margin-left: 2%;">Address:</b> Addis Ababa: N/S/L Sub-City  &nbsp;&nbsp;&nbsp;Woreda: 11</td>
                              </tr>
                              <tr>
                                  <td style="padding-top: 0;padding-bottom: 0;font-size: 22px;border: none"><b style="margin-left: 36%;">Tel:</b> 011-4-62-52-41</td>
                              </tr>
                              <tr>
                                  <td style="padding-top: 0;padding-bottom: 0;font-size: 22px;border: none"><b style="margin-left: 24%;">Telegram: </b> AYA online education channel</td>
                              </tr>
                              <tr>
                                  <td style="padding-right:0;padding-top: 0;padding-bottom: 0;font-size: 22px;border: none"><b style="margin-left: 26%;">Website: </b><a href="http://www.aspireschoolet.com/">http://www.aspireschoolet.com/</a></td>
                              </tr>

                          </table>
                     <table class="table">
                         <tr>
                             <td style="padding-top: 0;padding-bottom: 0;font-size: 26px;text-decoration: underline;font-family: Bookman;border: none" class="text-center">የተማሪ ውጤት መግለጫ</td>
                         </tr>
                         <tr>
                             <td style="padding-top: 0;padding-bottom:0;font-size: 26px;text-decoration: underline;font-family: Bookman;border: none" class="text-center">Student's Report Card</td>
                         </tr>
                     </table>
                        <div class="products p-2">
                            <table class="table">
                                <tbody>
                                <?php
                                $_age = floor((time() - strtotime($student->profile->usermeta('dob'))) / 31556926);
                                ?>
                                <tr class="content">
                                    <td style="padding-top: 1%;padding-bottom: 1%;font-size: 18px;border: none"><span style="font-family: Cambria">Student's Name:</span> <b style="font-family: Bookman;text-decoration: underline"><?php echo $student->profile->name;?></b></td>
                                </tr>
                                <tr class="content">
                                    <td style="padding-top: 1%;padding-bottom: 1%;border: none;font-size: 18px "><span style="font-family: Cambria">Sex:</span> <b style="font-family: Bookman;text-decoration: underline"><?php echo $student->profile->gender;?></b> &nbsp;&nbsp;&nbsp;<span style="font-family: Cambria">Age:</span> <b style="font-family: Bookman;">________________________</b> </td>
                                </tr>
                                <tr class="content">
                                    <td style="padding-top: 1%;padding-bottom: 1%;padding-right:0;word-spacing:-2px;font-size: 18px;border: none"><span  style="font-family: Cambria">Grade: </span><b style="font-family: Bookman;text-decoration: underline"><?php echo $student->class->name;?></b> &nbsp;&nbsp;&nbsp; <span style="font-family: Cambria">Academic Year:</span> <b style="font-family: Bookman;text-decoration: underline"><?php echo date('Y',strtotime($session->created_at));?>/21 G.C</b></td>
                                </tr>
                                <tr class="content">
                                    <td style="padding-top: 1%;padding-bottom: 1%;font-size: 18px;border: none">
                                        <span style="font-size: 16px !important;font-family: Cambria">Promoted to: <b style="font-size: 16px !important;font-family: Bookman"><?php echo '_________';?></b></span>

                                        &nbsp;&nbsp;<span style="font-size: 16px !important;font-family: Cambria">Detained at: <b style="font-size: 16px !important;font-family: Bookman"><?php echo '_________';?></b></span>
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
                                    <th style="font-size: 16px !important;padding-top: 2%;padding-bottom: 2%;border: none">Homeroom Teacher's </th>
                                    <th style="font-size: 16px !important;padding-top: 2%;padding-bottom: 2%;border: none">Director's Sign</th>
                                </tr>
                                <tr class="content">
                                    <td style="padding-top: 1%;padding-bottom: 1%;border: none">
                                        <span style="font-size: 16px !important;">Name:</span> <b style="font-size: 16px !important;"><?php echo $teacher->profile->name;?></b>
                                        <br>
                                        <?php if (!empty($teacher->signature)):?>
                                            <div style="padding-top: 1%;padding-bottom:1%;font-size: 16px !important;margin-top: 0">Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="80px" height="50px"></div>
                                        <?php endif;?>
                                    </td>
                                    <td style="padding-top: 1%;padding-bottom: 1%;border: none;">
                                        <?php if (!empty($dir)):?>
                                            <div style="padding-top: 1%"><img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" width="60px" height="60px"></div>
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
<!--    <download-pdf></download-pdf>-->
    <p style="page-break-before: always">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6" style="border: 10px solid #0680d1;margin-right: 1%;max-width: 49%!important;height: 720px !important;">
                        <div class="table-responsive" style="margin-top: 2%;margin-bottom: 2%">
                            <table class="table academic" style="overflow-x: hidden">

                                <thead class="thead-light">
                                <tr>
                                    <th colspan="7" style="border: 1px solid black">ACADEMIC RESULT RECORD</th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black;padding-right: 9px;padding-left: 4px;width: 1%;font-size: 10px">Subject</td>
                                    <?php
                                    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
                                    $semesters = getSession()->semesters;
                                    foreach ($semesters as $semester):?>
                                        <?php foreach ($semester->quarters as $quarter):
                                            ?>
                                            <td style="border: 1px solid black;font-size: 11px;padding-left: 5px;padding-right: 5px;"><?php echo $quarter->name;?></td>
                                        <?php endforeach;?>
                                        <td style="border: 1px solid black;font-size: 11px;padding-left: 5px;padding-right: 5px;"><?php echo $semester->name;?></td>
                                    <?php endforeach;?>
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
                                        <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><?php echo $subject->name;?></td>
                                        <?php
                                        foreach ($semesters as $semester):
                                            $result_arr = array();

                                            foreach ($semester->quarters as $quarter):?>
                                                <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;">
                                                    <?php
                                                    $result = $resultsModel->getQuarterTotalResultsPerSubject($quarter->id, $subject->id);

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
                                            <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;">
                                                <?php
                                                if (!isset($total_semesters[$semester->id]))
                                                    $total_semesters[$semester->id] = number_format($result_arr[$semester->id]/count($semester->quarters),2);
                                                else
                                                    $total_semesters[$semester->id] += number_format($result_arr[$semester->id]/count($semester->quarters),2);

                                                echo number_format($result_arr[$semester->id]/count($semester->quarters),2);
                                                ?>
                                            </td>
                                        <?php endforeach;?>
                                    </tr>
                                <?php endforeach;?>
                                <tr>
                                    <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b>Total</b></td>
                                    <?php  foreach ($semesters as $semester):
                                        foreach ($semester->quarters as $quarter):?>
                                            <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b><?php echo $total_quarters[$quarter->id];?></b></td>
                                        <?php endforeach;?>
                                        <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b><?php echo $total_semesters[$semester->id];?></b></td>
                                    <?php endforeach;?>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b>Average</b></td>
                                    <?php  foreach ($semesters as $semester):
                                        foreach ($semester->quarters as $quarter):?>
                                            <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b><?php echo number_format($total_quarters[$quarter->id]/count($subjects),2);?></b></td>
                                        <?php endforeach;?>
                                        <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b><?php echo number_format($total_semesters[$semester->id]/count($subjects),2);?></b></td>
                                    <?php endforeach;?>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b>Rank</b></td>
                                    <?php  foreach ($semesters as $semester):
                                        foreach ($semester->quarters as $quarter):?>
                                            <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b><?php echo $final_quarter_ranks[$quarter->id.'-'.$student->id].'/'.count($students);?></b></td>
                                        <?php endforeach;?>
                                        <td style="border: 1px solid black;font-size: 14px;padding-left: 5px;padding-right: 5px;"><b><?php echo $final_sem_ranks[$semester->id.'-'.$student->id].'/'.count($students);?></b></td>
                                    <?php endforeach;?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6" style="border: 10px solid #0680d1;max-width: 49%!important;margin-left: 1%;height: 720px !important;">
                        <table class="table behavior" style="margin-top: 2%">
                            <thead class="thead-light">
                            <tr>
                                <th colspan="3" style="background: lightgrey;text-align: center;border: 1px solid;font-size: 16px" class="bh"><span style="font-size: 16px">Student's Behavior and Basic Skills Progress Report</span></th>
                            </tr>
                            <tr>
                                <th style="width: 40%;border: 1px solid;font-size: 10px;padding-top: 0;padding-left: 0.5%;padding-right: 0" class="bh">Traits / Evaluation areas</th>
                                <?php foreach ($semesters as $semester):?>
                                    <th class="bh" style="border: 1px solid;font-size: 10px"><?php echo $semester->name;?></th>
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
                                        <td style="width: 40%; font-family: Bookman;border: 1px solid;font-size: 17px;padding-top:0;padding-bottom: 0;padding-right: 0;padding-left: 0.5%;word-spacing: -3px"><?php echo $trait['description'];?></td>
                                        <?php foreach ($evaluation as $k => $ev) {
                                            ?>
                                            <td style="border: 1px solid;font-size: 13px;padding-top: 0;padding-bottom: 0">
                                                <?php  echo array_values((array)$ev)[0] =='V' ? 'V.G' : array_values((array)$ev)[0] ;?>
                                            </td>
                                        <?php }?>
                                    </tr>
                                <?php }?>
                                <tr>
                                    <th colspan="4" style="text-align: center;border: 1px solid;"><span style="font-size: 16px;padding-top: 1%;padding-bottom: 1%">Tardy and Absent</span></th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%;padding-left: 0.5%;padding-right: 0">Number of tardy days</td>
                                    <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo $student_evaluation->first_sem_tardy;?></td>
                                    <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo $student_evaluation->second_sem_tardy;?></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%;padding-left: 0.5%;padding-right: 0">Number of absent days</td>
                                    <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo $student_evaluation->first_sem_absent;?></td>
                                    <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo $student_evaluation->second_sem_absent;?></td>
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

<script src="<?php echo base_url('assets/js/html2pdf.bundle.js')?>"></script>

<script>
var name = '<?php echo $std_name.'-'.$std_id;?>';

  var element = document.getElementById('pannation-project');
  var opt = {
      margin:       0,
      filename:     name+'.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { dpi: 800, letterRendering: true},
      jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
  };

  // New Promise-based usage:
 //  html2pdf().set(opt).from(element).save();

  // Old monolithic-style usage:
  html2pdf(element, opt)
  .then(res =>{
      console.log('finished')
      setTimeout(()=>{
          window.close();
      },2000)

  })

</script>
