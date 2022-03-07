<?php
$section = (new \App\Models\Sections())->find($section);
$subjects = $section->class->subjects;
$students = $section->students;

$session = (new \App\Models\Sessions())->find(active_session());
$semesters = $session->semesters;

$student_arr_ =  array();
foreach ($students as $student) {
    $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
    $subjects = $student->class->subjects;

    foreach ($subjects as $subj) {
        foreach ($semesters as $item) {
            $res = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subj->id, $student->section->id);
            if ($subj->optional == 0) {
                if (!isset($student_arr_[$student->id])) {
                    $student_arr_[$student->id] = ($res && !empty($res) && $subj->optional == 0) ? $res : 0;
                } else {
                    $student_arr_[$student->id] += ($res && !empty($res) && $subj->optional == 0) ? $res : 0;
                }
            }
        }
    }
}
$ranks = array_rank($student_arr_);
$counter = 0;

?>
<div class="table-responsive">
    <table class="table datatable" id="">
        <thead>
        <tr>
            <th colspan="2">Student</th>
            <?php
            if(count($subjects) > 0) {
                foreach ($subjects as $subject) {
                    if ($subject->optional == 0)
                        $counter++;
                    ?>
                    <th><?php echo $subject->name; ?></th>
                    <?php
                }
            }
            ?>
            <!--            <th>Average</th>-->
            <!--            <th>Total</th>-->
            <!--            <th>Rank</th>-->
            <!--            <th style="display: none"></th>-->
        </tr>
        </thead>
        <tbody>
        <?php
        $student_result = array();
        $total_marks_score = [];
        $total_marks_score_opt = [];
        $subs_arr_ = [];
        $subs_arr_opt = [];

        foreach ($semesters as $item) {
            foreach ($ranks as $student=>$rank) {
                if (!isset($student_result[$student.'.'.$item->id])){
                    $std = (new \App\Models\Students())->find($student);
                    $subject_score = [];
                    foreach ($section->class->subjects as $subject) {
                        $resultsModel = new \App\Libraries\YearlyResults($student, active_session());
                        $result = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subject->id, $std->section->id);

                        if (isset($subject_score[$subject->id.'.'.$item->id.'.'.$student])) {
                            $subject_score[$subject->id.'.'.$item->id.'.'.$student] += is_numeric($result) ? $result : 0;
                        } else {
                            $subject_score[$subject->id.'.'.$item->id.'.'.$student] = is_numeric($result) ? $result : 0;
                        }
                        $student_result[$student.'.'.$item->id] = [
                            'rank' =>$rank,
                            'student' =>$student,
                            'semester' => $item->id,
                            'subjects_score' =>$subject_score
                        ];

                        if (!isset($subs_arr_[$student.'.'.$subject->id])){
                            $subs_arr_[$student.'.'.$subject->id] = is_numeric($result) ? $result : 0;
                        }else {
                            $subs_arr_[$student.'.'.$subject->id] += is_numeric($result) ? $result : 0;
                        }
                    }

                }

            }

        }
        // echo '<pre>';
        // var_dump($subs_arr_);
        //var_dump('=========================');
        //   var_dump($student_result);
        //exit();
        $i = -1;
        foreach ($student_result as $key=>$value) {
            $std = (new \App\Models\Students())->find($value['student']);
            $i+=1;
            ?>
            <tr>
                <th colspan="2" rowspan="<?php echo $i % 2 == 0 ? 2 : ''?>" scope="rowgroup"><?php echo $std->profile->name.' i= '.$i; ?></th>
                <?php
                foreach ($section->class->subjects as $subject) {
                    ?>
                    <!--                            <tr>-->
                    <td><?php echo $subject->id;?></td>
                    <td><?php echo $subject->id;?></td>

                    <!--                            </tr>-->
                <?php }?>

                <!--                <td>--><?php //echo 5000;?><!--</td>-->
                <!--                <td>--><?php //echo 6; ?><!--</td>-->
                <!--                <td style="display:none;"></td>-->
            </tr>
            <?php
            ;
        }
        ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('#datatable').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy'
                },
                {
                    extend: 'excel',
                },
                {
                    extend: 'pdf',
                },
                {
                    extend: 'print',
                },
            ],
            "aoColumnDefs": [
                { "sType": "numeric", "aTargets": [ 0, -1 ] }
            ]
        });
    })
</script>