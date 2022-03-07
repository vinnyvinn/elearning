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
    $first_sem_comm = (new \App\Models\StudentComment())->find($home->first_sem_comment)['description'];
     $second_sem_comm = (new \App\Models\StudentComment())->find($home->second_sem_comment) ? (new \App\Models\StudentComment())->find($home->second_sem_comment)['description'] : '';
}
$saved_evaluations = isset($student_evaluation->remark) ? json_decode($student_evaluation->remark) : [];
?>
<style>
  td{
     padding-top: 3% !important;
     padding-bottom: 3% !important;
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
                                   <div class="col-md-6">
                                       <div class="table-responsive">
                                           <table class="table table-bordered table-striped" style="width: 100%">
                                               <thead>
                                               <tr>
                                                   <th style="background: lightgrey;"><h3>Grading Systems</h3></th>
                                               </tr>
                                               </thead>
                                               <tbody>
                                               <tr>
                                                   <td>90 - 100 = A</td>
                                               </tr>
                                               <tr>
                                                   <td>80 - 89 = B</td>
                                               </tr>
                                               <tr>
                                                   <td>70 - 79 = C</td>
                                               </tr>
                                               <tr>
                                                   <td>60 - 69 = D</td>
                                               </tr>
                                               <tr>
                                                   <td>< 60 = F</td>
                                               </tr>
                                               </tbody>
                                           </table>
                                       </div>
                                   </div>
                                   <div class="col-md-6">
                                       <div class="table-responsive">
                                           <table class="table table-bordered table-striped" style="width: 100%">
                                               <thead>
                                               <tr>
                                                   <th style="background: lightgrey;"><h3>Behavior and Basic Skills</h3></th>
                                               </tr>
                                               </thead>
                                               <tbody>
                                               <tr>
                                                   <td>E = Excellent</td>
                                               </tr>
                                               <tr>
                                                   <td>V.G = Very good</td>
                                               </tr>
                                               <tr>
                                                   <td>G = Good</td>
                                               </tr>
                                               <tr>
                                                   <td>S = Satisfactory</td>
                                               </tr>
                                               <tr>
                                                   <td>NI = Needs Improvement</td>
                                               </tr>
                                               </tbody>
                                           </table>

                                       </div>
                                   </div>
                               </div>
                                <br>
                                <div style="margin-left: 4%">
                                    <table>
                                        <tr>
                                            <th><h3>1<sup>st</sup> Semester</h3></th>
                                        </tr>
                                        <tr>
                                         <td>Home Room Teacher's Comments</td>
                                        </tr>
                                        <tr>
                                         <td>
                                             <?php if (!empty($home)):?>
                                             <b><?php echo $first_sem_comm;?></b>
                                             <?php endif;?>
                                         </td>
                                        </tr>
                                        <tr>
                                         <td><?php if (!empty($teacher->signature)):?>
                                                 Sign: <img src="<?php echo base_url('/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                             <?php endif;?>
                                         </td>
                                        </tr>
                                        <tr>
                                       <td>Parent's Comments</td>
                                        </tr>
                                        <tr>
                                          <td>
                                           Sign______________________________________________
                                          </td>
                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <th><h3>2<sup>nd</sup> Semester</h3></th>
                                        </tr>
                                        <tr>
                                            <td>Home Room Teacher's Comments</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php if (!empty($home)):?>
                                               <b><?php echo $second_sem_comm;?></b>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php if (!empty($teacher->signature)):?>
                                                    Sign: <img src="<?php echo base_url('/'.$teacher->signature)?>" alt="" width="60px" height="60px">
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Parent's Comments</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Sign______________________________________________
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <hr>
                            </div>
                        </div>
                    </div>
                </div>

            <div style="text-align: right;">
                <a href="<?php echo site_url(route_to('teacher.academic.yearly_certificate.evaluation', $student->id)); ?>" class="previous">&laquo; Previous</a>
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
    .next {
        background-color: #04AA6D;
        color: white;
    }
    a {
        text-decoration: none;
        display: inline-block;
        padding: 8px 16px;
    }
    a:hover {
        background-color: #ddd;
        color: black;
    }
    .previous {
        background-color: #f1f1f1;
        color: black;
    }
</style>

