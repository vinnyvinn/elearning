<?php
$section = (new \App\Models\Sections())->find($section);
$semester = (new \App\Models\Semesters())->find($semester);
$quarters = $semester->quarters;
$subjects = $section->class->subjects;
$students = $section->students;

$students_arr = array();

$resultsModel = new \App\Libraries\YearlyResults(79, active_session());

foreach ($section->students as $student) {
    $total_marks = 0;
    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());

    if (count($subjects) > 0) {
        foreach ($subjects as $subject) {
            $result = $resultsModel->getSemesterTotalResultsPerSubject($semester->id, $subject->id,$section->id);

            if ($subject->optional ==0){
            if (!empty($result) && is_numeric($result)) {
                if (!isset($students_arr[$student->id])) {
                    $students_arr[$student->id] = $subject->optional ==0 ? $result : 0;
                } else {
                    $students_arr[$student->id] += $subject->optional ==0 ? $result : 0;
                }
            }
         }
        }
    }
}
$student_ranks = array_rank($students_arr);
$counter = 0;
?>
<div class="table-responsive">
    <table class="table datatable" id="datatable">
        <thead>
        <tr>
            <th>#</th>
            <th>Student cool</th>
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
            <th>Average</th>
            <th>Total</th>
            <th>Rank</th>
            <th style="display: none"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $n = 0;
        $i = 0;
        foreach ($student_ranks as $student => $rank) {
            $n++;
            $total_marks = 0;
            $resultsModel = new \App\Libraries\YearlyResults($student, active_session());
            ?>
            <tr>
                <td><?php echo $n; ?></td>
                <td><?php echo getStudent($student); ?></td>
                <?php
                if(count($subjects) > 0) {
                    foreach ($subjects as $subject) {
                        $result = $resultsModel->getSemesterTotalResultsPerSubject($semester->id, $subject->id);
                        $result = is_numeric($result) ? number_format($result/count($quarters),2) : '';
                        ?>
                        <td>
                          <?php
                            if($result && !empty($result) && $subject->optional == 0) {
                                echo $result;
                            }elseif($subject->optional ==0) {
                                echo '-';
                            }
                            if ($subject->optional ==0){
                                $i++;
                                $total_marks += is_numeric($result) ? $result : 0;
                            }

                            if ($subject->optional == 1) {
                                $res = (new \App\Models\ClassSubjects())->find($subject->id);
                                if (!empty($res->grading) && $result) {
                                    $grade = json_decode($res->grading);
                                    foreach ($grade as $g) {
                                        $item = explode('-', $g->scale);
                                        if ($result >= min($item) && $result <= max($item)) {
                                            echo $g->grade;
                                            break;
                                        }

                                    }
                                }else{
                                    echo '-';
                                }
                            }
                            ?>
                        </td>
                        <?php
                    }
                }
                ?>
                <td>
                 <?php echo number_format($total_marks/$counter, 2); ?>
                </td>
                <td><?php echo number_format($total_marks, 2); ?></td>
                <td><?php echo $rank; ?></td>
                <td style="display:none;"></td>
            </tr>
            <?php
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