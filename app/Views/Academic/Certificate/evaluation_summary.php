<?php
$section = (new \App\Models\Sections())->find($student->section->id);
$class = (new \App\Models\Classes())->find($student->class->id);
$std_id = $student->id;
$teacher = (new \App\Models\Teachers())->find($section->advisor->id);
$home = (new \App\Models\Homeroom())->where('student',$std_id)->where('class',$class->id)->where('section',$section->id)->where('session',active_session())->get()->getLastRow();
if(isset($session) && is_numeric($session)) {
    $session = (new \App\Models\Sessions())->find($session);
} else {
    $session = getSession();
}

$semesters = (new \App\Models\Semesters())->where('session',active_session())->findAll();
$student_evaluation = (new \App\Models\StudentEvaluation())->where('student',$student->id)->get()->getLastRow();
$first_sem_comm = '';
$second_sem_comm = '';
if (!empty(($home))){
    $first_sem_comm = (new \App\Models\StudentComment())->find($home->first_sem_comment) ? (new \App\Models\StudentComment())->find($home->first_sem_comment)['description'] : '';
     $second_sem_comm = (new \App\Models\StudentComment())->find($home->second_sem_comment) ? (new \App\Models\StudentComment())->find($home->second_sem_comment)['description'] : '';
}
$saved_evaluations = isset($student_evaluation->remark) ? json_decode($student_evaluation->remark) : [];
?>
<style>
  td{
     padding-top: 3% !important;
     padding-bottom: 3% !important;
  }

  body {
      /* width: 100%; */
      -webkit-font-smoothing: antialiased !important;
      -moz-osx-font-smoothing: grayscale !important;
      font-size: 13px !important;
      line-height: 24px !important;
      font-family: 'Work Sans', sans-serif !important;
      /* font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif; */
      /* color: #666; */
      /* overflow-x: hidden; */
  }
  .card{
      overflow-x: scroll !important;
  }
</style>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Yearly Certificate</h6><br/>
                    <span class="text-white"><?php echo $student->profile->name;?></span><br/>
                    <span class="text-white"><?php echo $student->class->name; ?></span><br/>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('admin.academic.yearly_certificate.print', $student->id)); ?>">Print</a>
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('admin.academic.yearly_certificate.download-cert', $student->id)); ?>">Download Pdf</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="card">
            <div class="card-body">


                <div class="container mt-5 mb-3">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
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
                                    <div class="col-md-6 col-sm-12">
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
                                 <div style="margin-left: 4%;">
                                    <table>
                                        <tr>
                                            <th><h3 style="border-bottom: 2px solid;margin-top: 5%;font-size: 30px">1<sup>st</sup> Semester</h3></th>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 0;padding-bottom: 0;font-size: 24px;">
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
                                            <td  style="padding-left: 15%;margin-top: 0;padding-top: 0;padding-bottom: 0.5%;font-family: Nyala"><?php if (is_homeroom_sign_sem1($std_id) && !empty($teacher->signature)):?>
                                                    Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                                <?php else:?>
                                                    <p style="font-family: Nyala;font-size: 36px"> Sign: ____________</p>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 0.5%;padding-bottom: 0.5%;font-size: 24px;">
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
                                            <td style="padding-left: 9%; font-size: 24px;margin-top:0;margin-bottom:0;font-family: Nyala">
                                                Sign____________
                                            </td>
                                        </tr>
                                    </table>

                                    <table style="margin-bottom: 2%;">
                                        <tr>
                                            <th><h3 style="border-bottom: 2px solid;margin-top: 5%;font-size: 30px">2<sup>nd</sup> Semester</h3></th>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 0;padding-bottom: 0;font-size: 24px;">
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
                                            <td style="padding-left: 15%;font-family: Nyala;padding-top: 0;padding-bottom: 0.5%;font-size: 24px;"><?php if (is_homeroom_sign_sem2($std_id) && !empty($teacher->signature)):?>
                                                    Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                                <?php else:?>
                                                    <p style="font-family: Nyala;font-size: 24px;"> Sign____________</p>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 0.5%;padding-bottom: 0.5%;font-size: 24px;">
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
                                            <td style="padding-left: 15%; font-size: 24px;font-family: Nyala;padding-top: 2%;padding-bottom: 0.5%">
                                                Sign____________
                                            </td>
                                            <td></td>
                                        </tr>

                                    </table>
                                </div>

                                <hr>
                            </div>
                        </div>
                    </div>
                </div>

            <div style="text-align: right;">
              <a href="<?php echo site_url(route_to('admin.academic.yearly_certificate.evaluation', $student->id)); ?>" class="previous">&laquo; Previous</a>
              <a href="javascript:void(0)" class="next">Next &raquo;</a>
            </div>
        </div>
    </div>
</div>
<style>

    hr {
        color: #0000004f;
        margin-top: 5px;
        margin-bottom: 5px
    }

    .add td {
        color: #c5c4c4;
        text-transform: uppercase;
        font-size: 12px
    }

    .content {
        font-size: 14px
    }
    .table td, .table th{
        white-space: revert !important;
    }
</style>