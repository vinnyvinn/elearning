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

$std_name = $student->profile->name;
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
                                            <td class="text-center" style="font-family: 'LLucida Calligraphy';font-size: 26px;padding-top: 0;padding-bottom: 0;border: none">??????????????? ?????? ????????????</t</td>
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
                             <td style="padding-top: 0;padding-bottom: 0;font-size: 26px;text-decoration: underline;font-family: Bookman;border: none" class="text-center">???????????? ????????? ????????????</td>
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
                        <?php
                        if($session && $student) {
                            $semesters = $session->semesters;
                            if(isset($semesters) && is_array($semesters) && count($semesters) > 0) {
                                $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
                                ?>
                                <div class="table-responsive" style="margin-top: 2%;margin-bottom: 2%;padding: 0 !important;">
                                    <table class="table" style="overflow-x: hidden">

                                        <thead class="thead-light">
                                       <tr>
                                       <th colspan="4" style="background: lightgrey;border: 1px solid;"><span style="font-size: 16px;">Academic Result Record</span></th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid;font-size: 13px;padding-left: 1%;padding-right: 0">Subject</th>
                                            <?php
                                            foreach ($semesters as $semester) {
                                                ?>
                                                <th style="border: 1px solid;font-size: 13px;padding-left: 1%;padding-right: 0;"><?php echo $semester->name; ?></th>
                                                <?php
                                            }
                                            ?>
                                            <th  style="border: 1px solid;font-size: 13px;padding-left: 1%;padding-right: 0">Average</th>
                                        </tr>
                                        </thead>
                                        <tbody>
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
                                                <td style="border: 1px solid;font-size: 18px;padding-top: 1%;padding-bottom: 1%"><?php echo $subject->name; ?></td>
                                                <?php
                                                foreach ($semesters as $item) {
                                                    ?>
                                                    <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php
                                                        $result = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subject->id,$student->section->id);
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
                                                            $res = (new \App\Models\ClassSubjects())->find($subject->id);
                                                            if (!empty($res->grading) && $result) {
                                                                $grade = json_decode($res->grading);
                                                                foreach ($grade as $g) {
                                                                    $item = explode('-', $g->scale);
                                                                    if ($result >= min($item) && $result <= max($item)) {
                                                                        echo $g->grade.'('.$result.')';
                                                                        break;
                                                                    }
                                                                }
                                                            }else{
                                                                echo '-';
                                                            }
                                                        }
                                                        ?></td>
                                                    <?php
                                                }
                                                ?>
                                                <td style="border: 1px solid;font-size: 18px;padding-top: 1%;padding-bottom: 1%">
                                                    <?php if ($subject->optional == 0):?>
                                                        <?php echo isset($data_arr[$subject->id]) ? $data_arr[$subject->id] / count($semesters) : '-';?>
                                                    <?php else:?>
                                                        <?php
                                                        $res = (new \App\Models\ClassSubjects())->find($subject->id);
                                                        $result = $data_arr_opt[$subject->id] / count($semesters);

                                                        if (!empty($res->grading) && $result) {
                                                            $grade = json_decode($res->grading);
                                                            foreach ($grade as $g) {
                                                                $item = explode('-', $g->scale);
                                                                if ($result >= min($item) && $result <= max($item)) {
                                                                    echo $g->grade.'('.$result.')';
                                                                    break;
                                                                }
                                                            }
                                                        }else{
                                                            echo '-';
                                                        }
                                                        ?>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <th colspan="" style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%">Conduct</th>
                                            <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo isset($conduct->first_sem_evaluation) ? $conduct->first_sem_evaluation : '';?></td>
                                            <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo isset($conduct->second_sem_evaluation) ? $conduct->second_sem_evaluation : '';?></td>
                                            <td style="border: 1px solid;"></td>
                                        </tr>
                                        <tr>
                                            <th colspan="" style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%">Total Marks</th>
                                            <?php
                                            foreach ($semesters as $item) {
                                                ?>
                                                <th style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo $total_marks[$item->id]; ?></th>
                                                <?php
                                            }
                                            ?>
                                            <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php
                                                $tt=0;
                                                foreach ($data_arr as $d){
                                                    $tt += $d/count($semesters);
                                                }
                                                echo number_format($tt,2);
                                                ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="" style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%">Average</th>
                                            <?php
                                            foreach ($semesters as $item) {
                                                ?>
                                                <th style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo number_format($total_marks[$item->id]/$count,2); ?></th>
                                                <?php
                                            }
                                            ?>
                                            <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo number_format($tt/$count,2);?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="" style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%">Rank</th>
                                            <?php
                                            $datasem1_arr_ =  array();
                                            $datasem2_arr_ =  array();
                                            $data_arr_ =  array();
                                            foreach ($section->students as $student) {
                                                $total_marks = 0;
                                                $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
                                                $subjects = $student->class->subjects;

                                                foreach ($subjects as $subj) {
                                                    $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[0]->id, $subj->id,$student->section->id);
                                                    $res2 = $resultsModel->getSemesterTotalResultsPerSubject($semesters[1]->id, $subj->id,$student->section->id);
                                                    if(!isset($datasem1_arr_[$student->id])){
                                                        $datasem1_arr_[$student->id] = ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                                    }else {
                                                        $datasem1_arr_[$student->id] += ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                                    }
                                                    if(!isset($datasem2_arr_[$student->id])){
                                                        $datasem2_arr_[$student->id] = ($res2 && !empty($res2) && $subj->optional == 0) ?  $res2 : 0;
                                                    }else {
                                                        $datasem2_arr_[$student->id] += ($res2 && !empty($res2) && $subj->optional == 0) ?  $res2 : 0;
                                                    }
                                                    if(!isset($data_arr_[$student->id])){
                                                        $res1 =($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                                        $res2 =($res2 && !empty($res2) && $subj->optional == 0) ?  $res2 : 0;
                                                        $data_arr_[$student->id] = $res1 + $res2;
                                                    }else {
                                                        $data_arr_[$student->id] += ($res1+$res2);
                                                    }
                                                }
                                            }

                                            $ranking = array_rank($datasem1_arr_);
                                            $ranking2 = array_rank($datasem2_arr_);?>

                                            <th style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo $ranking[$std_id].'/'.count($section->students); ?></th>
                                            <th style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php echo $ranking2[$std_id].'/'.count($section->students); ?></th>
                                            <td style="border: 1px solid;font-size: 16px;padding-top: 1%;padding-bottom: 1%"><?php
                                                $ranks = array_rank($data_arr_);
                                                echo $ranks[$std_id].'/'.count($section->students);
                                                ?></td>
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
