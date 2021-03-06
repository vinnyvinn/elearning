<div class="certificate">
<?php
//$student = (new \App\Models\Students())->find($student);
$section = (new \App\Models\Sections())->find($student->section->id);
$class = (new \App\Models\Classes())->find($student->class->id);
$evaluation = (new \App\Models\YearlyStudentEvaluation())->where('student',$student->id)->where('class',$class->id)->where('section',$section->id)->where('session',$session)->get()->getLastRow();
$std_id = $student->id;
if(isset($session) && is_numeric($session)) {
    $session = (new \App\Models\Sessions())->find($session);
} else {
    $session = getSession();
}

?>
            <?php
            if($session && $student) {
                $semesters = $session->semesters;
                if(isset($semesters) && is_array($semesters) && count($semesters) > 0) {
                    $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
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
                            foreach ($subjects as $subj) {
                                foreach ($semesters as $item) {
                                    $res = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subj->id,$student->section->id);
                                    if($res && !empty($res) && $subj->optional == 0) {
                                        if(!isset($data_arr[$subj->id])){
                                            $data_arr[$subj->id] = $res;
                                        }else {
                                            $data_arr[$subj->id] += $res;
                                        }
                                    }else if ($res && !empty($res) && $subj->optional == 1){
                                        if(!isset($data_arr_opt[$subj->id])){
                                            $data_arr_opt[$subj->id] = $res;
                                        }else {
                                            $data_arr_opt[$subj->id] += $res;
                                        }
                                    }
                                }
                            }

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
                                    <td>
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
                                        $resultsModel = new \App\Libraries\YearlyResults($student->id,$session->id);
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
                <a href="javascript:void(0)" class="previous_cert">&laquo; Previous </a>
                <a href="javascript:void(0)" class="next_cert">Next &raquo;</a>
            </div>
</div>

<style>
    .next_cert {
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
    .previous_cert {
        background-color: #f1f1f1;
        color: black;
    }
</style>

<script>
    var sess = "<?php echo $session->id;?>";
    var student = "<?php echo $student->id;?>";
    $('.next_cert').on('click',function (){
        next(sess,student);
    })
    $('.previous_cert').on('click',function (){
        back(sess,student);
    })
    function next(session,student) {
        var d = {
            url: "<?php echo site_url(route_to('parent.student.certificate.evaluation')); ?>",
            loader: true,
            data: "year="+session+"&student="+student
        };
        ajaxRequest(d, function (data) {
            $('.certificate').html(data);
        })
    }
    function back(session,student) {
        var d = {
            url: "<?php echo site_url(route_to('parent.student.report-card')); ?>",
            loader: true,
            data: "year="+session+"&student="+student
        };
        ajaxRequest(d, function (data) {
            $('.certificate').html(data);
        })
    }
</script>