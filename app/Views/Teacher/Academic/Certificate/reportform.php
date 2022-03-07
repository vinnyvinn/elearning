<?php
$section = (new \App\Models\Sections())->find($student->section->id);
$class = (new \App\Models\Classes())->find($student->class->id);

$std_id = $student->id;
if(isset($session) && is_numeric($session)) {
    $session = (new \App\Models\Sessions())->find($session);
} else {
    $session = getSession();
}
$teacher = (new \App\Models\Teachers())->find($section->advisor->id);
$directors = (new \App\Models\Teachers())->where('is_director',1)->findAll();
$dir = '';
foreach ($directors as $director){
    if ($director->director_classes) {
        if (in_array($class->id, json_decode($director->director_classes))) {
            $dir = $director;
        }
    }
}
$comment = (new \App\Models\Homeroom())->where('student',$student->id)->where('session',active_session())->get()->getLastRow();
$promotion = (new \App\Models\Promotion())->where('student',$std_id)->where('old_class',$class->id)->where('old_section',$section->id)->where('old_session',$student->session->id)->get()->getLastRow();
?>
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
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('teacher.academic.yearly_certificate.print', $student->id)); ?>">Print </a>
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('teacher.academic.yearly_certificate.download-cert', $student->id)); ?>">Download Pdf</a>
                    <a style="color:#fff" class="btn btn-warning btn-sm" data-toggle="modal"
                       data-target=".edit_<?php echo $student->id; ?>">Add Conduct
                    </a>
                    <div class="modal fade edit_<?php echo $student->id; ?>" role="document"
                         aria-labelledby="modal-default"
                         style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form class="ajaxForm" loader="true" method="post"
                                          action="<?php echo site_url(route_to('teacher.academic.yearly_certificate.save_yearly_evaluation', $student->id)); ?>">
                                    <input type="hidden" name="id" value="<?php echo $student->id; ?>" />
                                    <input type="hidden" name="class" value="<?php echo $class->id; ?>" />
                                    <input type="hidden" name="section" value="<?php echo $section->id;?>" />
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="modal-title-default">Student Conduct</h6>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div style="text-align: left">
                                                <label for="sess">1 <sup>st</sup> Semester Conduct</label>
                                            </div>
                                            <textarea name="first_sem_evaluation" class="form-control" rows="2" required><?php echo isset($evaluation->first_sem_evaluation) ? $evaluation->first_sem_evaluation : '' ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div style="text-align: left">
                                                <label for="sess">2 <sup>nd</sup> Semester Conduct</label>
                                            </div>
                                            <textarea name="second_sem_evaluation" class="form-control" rows="2"><?php echo isset($evaluation->second_sem_evaluation) ? $evaluation->second_sem_evaluation : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">

                                        <button type="submit" class="btn btn-success">Update</button>

                                        <button type="button" class="btn btn-link  ml-auto"
                                                data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                        <div class="col-md-8">
                            <div class="card">
                                <div class="d-flex flex-row p-2"> <img src="<?php echo base_url()?>/uploads/files/<?php echo get_option('website_logo');?>" width="96">
                                    <div class="d-flex flex-column"> <h2 style="padding-left: 14%">Report Form</h2> <small style="text-decoration: underline;text-align: center;"></small> <h1><?php echo get_option('id_school_name');?></h1></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-borderless kk">
                                        <tbody>
                                            <tr>
                                                <td style="padding-top: 0; padding-bottom: 0"><b>Address:</b> <?php echo get_option('id_location')?></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 0; padding-bottom: 0"><b>Tel:</b> 011-4-62-52-41 <b> Telegram: </b> AYA online education channel</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 0; padding-bottom: 0"><b>Website: </b> <a href="http://www.aspireschoolet.com/">http://www.aspireschoolet.com/</a></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <h1 style="text-align: center;text-decoration: underline">Student's Report Card</h1>
                                <div class="products p-2">
                                    <table class="table table-borderless">
                                        <tbody>
                                        <?php
                                        $_age = floor((time() - strtotime($student->profile->usermeta('dob'))) / 31556926);
                                        ?>
                                        <tr class="content">
                                            <td>Student's Name: <b><?php echo $student->profile->name;?></b> &nbsp;&nbsp;ID: <b><?php echo $student->admission_number;?></b></td>
                                        </tr>
                                        <tr class="content">
                                            <td>Sex: <b><?php echo $student->profile->gender;?></b> &nbsp;&nbsp;Age: <b><?php echo $_age;?></b> &nbsp;&nbsp;Grade: <b><?php echo $student->class->name;?></b></td>
                                        </tr>
                                        <tr class="content">
                                            <td>Academic Year: <b><?php echo date('Y',strtotime($session->created_at));?></b></td>
                                        </tr>
                                        <tr class="content">

                                            <td>
                                                <?php
                                                $total_marks = [];
                                                $subs_arr = [];
                                                $counter = 0;
                                                $session = (new \App\Models\Sessions())->find($student->session->id);
                                                $semesters = $session->semesters;
                                                $subjects = $student->class->subjects;
                                                foreach ($subjects as $subject) {
                                                    if ($subject->optional == 0)
                                                        $counter++;
                                                    foreach ($semesters as $item) {
                                                        $resultsModel = new \App\Libraries\YearlyResults($student->id, $student->session->id);
                                                        $result = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subject->id, $student->section->id);
                                                        if ($subject->optional == 0) {
                                                            if (isset($total_marks[$item->id])) {
                                                                $total_marks[$item->id] += is_numeric($result) ? $result : 0;
                                                            } else {
                                                                $total_marks[$item->id] = is_numeric($result) ? $result : 0;
                                                            }

                                                            if (!isset($subs_arr[$item->id.'.'.$subject->id])){
                                                                $subs_arr[$item->id.'.'.$subject->id] = $result;
                                                            }
                                                        }
                                                    }
                                                }

                                                $counter_fail = 0;
                                                foreach ($subjects as $subject) {
                                                    if ($subject->optional == 0) {
                                                        if ((($subs_arr[$semesters[0]->id.'.' . $subject->id] + $subs_arr[$semesters[1]->id.'.' . $subject->id]) / 2) < $subject->pass_mark) {
                                                            $counter_fail++;
                                                        }
                                                    }
                                                }
                                                $av_score = number_format(($total_marks[$semesters[0]->id] + $total_marks[$semesters[1]->id])/$counter,2);
                                                ?>
                                                <span>Promoted To: <b><?php echo ($counter_fail < 3 && $av_score >= $student->class->pass_mark) ? (isset($next_class->name) ? $next_class->name : '------------------------------'): '------------------------------';?></b></span>

                                                &nbsp;&nbsp;<span>Detained At: <b><?php echo ($counter_fail < 3 && $av_score >= $student->class->pass_mark) ? '------------------------------' : $class->name;?></b></span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr class="new1" style="border: 3px solid #525f7f !important;">
                                <div class="products p-2">
                                    <table class="table table-borderless">
                                        <tbody>
                                        <tr class="content">
                                            <th>Homeroom Teacher's </th>
                                            <th>Director's</th>
                                        </tr>
                                        <tr class="content">
                                            <td>
                                              Name: <b><?php echo $teacher->profile->name;?></b><br>
                                             <br>

                                              <?php
                                              if (!empty($teacher->signature)):?>
                                             <div style="padding-top: 10%">Sign: <img src="<?php echo base_url('/uploads/'.$teacher->signature)?>" alt="" style="width: 80px;height: 80px"></div>
                                                <?php endif;?>
                                            </td>
                                            <td>
                                                <?php if (!empty($dir)):?>
                                                <div style="padding-top: 10%">Sign: <img src="<?php echo base_url('/uploads/'.$dir->signature)?>" alt="" style="width: 80px;height: 80px"></div>
                                                <?php endif;?>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            <div style="text-align: right;">
                <a href="javascript:void(0)" class="previous">&laquo; Previous</a>
                <a href="<?php echo site_url(route_to('teacher.academic.yearly_certificate.view', $student->id)); ?>" class="next">Next &raquo;</a>
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