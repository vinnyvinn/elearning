<?php
//$student = (new \App\Models\Students())->find($student);
$section = (new \App\Models\Sections())->find($student->section->id);
$class = (new \App\Models\Classes())->find($student->class->id);
$evaluation = (new \App\Models\YearlyStudentEvaluation())->where('student',$student->id)->where('class',$class->id)->where('section',$section->id)->where('session',active_session())->get()->getLastRow();
$std_id = $student->id;
if(isset($session) && is_numeric($session)) {
    $session = (new \App\Models\Sessions())->find($session);
} else {
    $session = getSession();
}

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
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('admin.academic.yearly_certificate.print', $student->id)); ?>">Print</a>
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('admin.academic.yearly_certificate.download-cert', $student->id)); ?>">Download Pdf</a>
                    <a style="color:#fff" class="btn btn-warning btn-sm" data-toggle="modal"
                       data-target=".edit_<?php echo $student->id; ?>">Add Conduct
                    </a>
                    <div class="modal fade edit_<?php echo $student->id; ?>" role="document"
                         aria-labelledby="modal-default"
                         style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form class="ajaxForm" loader="true" method="post"
                                      action="<?php echo site_url(route_to('admin.academic.yearly_certificate.save_yearly_evaluation', $student->id)); ?>">
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
            <?php
            if($session && $student) {
                $semesters = $session->semesters;
                if(isset($semesters) && is_array($semesters) && count($semesters) > 0) {
                    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <?php
                                foreach ($semesters as $semester) {
                                    ?>
                                    <th><?php echo $semester->name; ?></th>
                                    <?php
                                }
                                ?>
                                <th>Average</th>
                                <th>Pass Mark</th>
                                <th>Performance</th>
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
//                            foreach ($subjects as $subj) {
//                                foreach ($semesters as $item) {
//                                    $res = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subj->id,$student->section->id);
//                                    if($res && !empty($res) && $subj->optional == 0) {
//                                        if(!isset($data_arr[$subj->id])){
//                                            $data_arr[$subj->id] = $res;
//                                        }else {
//                                            $data_arr[$subj->id] += $res;
//                                        }
//                                    }else if ($res && !empty($res) && $subj->optional == 1){
//                                        if(!isset($data_arr_opt[$subj->id])){
//                                            $data_arr_opt[$subj->id] = $res;
//                                        }else {
//                                            $data_arr_opt[$subj->id] += $res;
//                                        }
//                                    }
//                                }
//
//                            }

                            foreach ($subjects as $subject) {
                                if ($subject->optional == 0)
                                 $count++;
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $subject->name; ?></td>
                                    <?php
                                    foreach ($semesters as $item) {
                                        ?>
                                        <td><?php
                                            $result = getSemesterSubjectTotal($student->id,$subject->id,$item->id);
                                            if($result && !empty($result) &&  $subject->optional == 0) {
                                                echo $result;
                                            }elseif($subject->optional ==0) {
                                                echo '-';
                                            }

                                            if($subject->optional == 1) {
                                              echo getSemesterSubjectGrade($student->id,$subject->id,$semester->id);
                                            }
                                            ?></td>
                                        <?php
                                    }
                                    ?>
                                    <td>
                                        <?php if ($subject->optional == 0):?>
                                            <?php echo isset($data_arr[$subject->id]) ? $data_arr[$subject->id] / count($semesters) : '-';?>
                                        <?php else:?>
                                            <?php
                                            $res = (new \App\Models\ClassSubjects())->find($subject->id);

                                            $result = isset($data_arr_opt[$subject->id]) ? ($data_arr_opt[$subject->id] / count($semesters)) : '';

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
                                    <td><?php echo $subject->pass_mark;?></td>
                                    <td>
                                        <?php if ($subject->optional == 0):?>
                                            <?php if (isset($data_arr[$subject->id])):?>
                                                <?php
                                                if (($data_arr[$subject->id] / count($semesters)) >= $subject->pass_mark):?>
                                                    <span class="badge badge-success">Pass</span>
                                                <?php else:?>
                                                    <span class="badge badge-danger">Fail</span>
                                                <?php endif;endif;endif;?>

                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td></td>
                                <th>Conduct</th>
                                <td><?php echo isset($evaluation->first_sem_evaluation) ? $evaluation->first_sem_evaluation : '';?></td>
                                <td><?php echo isset($evaluation->second_sem_evaluation) ? $evaluation->second_sem_evaluation : '';?></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td></td>
                                <th>Total Marks</th>
                                <?php
                                foreach ($semesters as $item) {
                                    ?>
                                    <th><?php echo $total_marks[$item->id]; ?></th>
                                    <?php
                                }
                                ?>
                                <td><?php
                                    $tt=0;
                                    foreach ($data_arr as $d){
                                        $tt += $d/count($semesters);
                                    }
                                    echo number_format($tt,2);
                                    ?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Average</th>
                                <?php
                                foreach ($semesters as $item) {
                                    ?>
                                    <th><?php echo number_format($total_marks[$item->id]/$count,2); ?></th>
                                    <?php
                                }
                                ?>
                                <td><?php echo number_format($tt/$count,2);?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Rank</th>
                                <?php
                                $datasem1_arr_ =  array();
                                $datasem2_arr_ =  array();
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

                                    foreach ($subjects as $subj) {
                                        $res = $resultsModel->getSemesterTotalResultsPerSubject($semesters[1]->id, $subj->id,$student->section->id);
                                        if(!isset($datasem2_arr_[$student->id])){
                                            $datasem2_arr_[$student->id] = ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                        }else {
                                            $datasem2_arr_[$student->id] += ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                        }

                                    }
                                }

                                $ranking = array_rank($datasem1_arr_);
                                $ranking2 = array_rank($datasem2_arr_);
                                ?>

                                <th><?php echo $ranking[$std_id].'/'.count($section->students); ?></th>
                                <th><?php echo $ranking2[$std_id].'/'.count($section->students); ?></th>

                                <td><?php
                                    $data_arr_ =  array();
                                    foreach ($section->students as $student) {
                                        $total_marks = 0;
                                        $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
                                        $subjects = $student->class->subjects;

                                        foreach ($subjects as $subj) {
                                            foreach ($semesters as $item) {
                                                $res = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subj->id,$student->section->id);
                                                if(!isset($data_arr_[$student->id])){
                                                    $data_arr_[$student->id] = ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                                }else {
                                                    $data_arr_[$student->id] += ($res && !empty($res) && $subj->optional == 0) ?  $res : 0;
                                                }
                                            }
                                        }
                                    }
                                    $ranks = array_rank($data_arr_);
                                    echo $ranks[$std_id].'/'.count($section->students);
                                    ?></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>


                    </div>
                    <hr>

                    <?php
                } else {
                    ?>
                    <div class="alert alert-danger">No semesters found</div>
                    <?php
                }
            } else {
                ?>
                <div class="alert alert-danger">
                    Invalid session or student
                </div>
                <?php
            }
            ?>

            <div style="text-align: right">
                <a href="<?php echo site_url(route_to('admin.academic.yearly_certificate.report-card', $std_id)); ?>" class="previous">&laquo; Previous </a>
                <a href="<?php echo site_url(route_to('admin.academic.yearly_certificate.evaluation', $std_id)); ?>" class="next">Next &raquo;</a>
            </div>
        </div>

    </div>
</div>
