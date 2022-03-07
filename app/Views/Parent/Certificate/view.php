<?php
$student = (new \App\Models\Students())->find($student);
$session = (new \App\Models\Sessions())->find($session);

if($session && $student) {

    $semesters = $session->semesters;
    if(isset($semesters) && is_array($semesters) && count($semesters) > 0) {
        $resultsModel = new \App\Libraries\YearlyResults($student->id, $session->id);
        ?>
        <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('parent.academic.yearly_certificate.print', $student->id)); ?>">Print</a>
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
                    </tr>
                </thead>
                <tbody>
                <?php
                $total_marks = [];
                $count = 0;
                $n = 0;
                $subjects = $student->class->subjects;
                foreach ($subjects as $subject) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $subject->name; ?></td>
                        <?php
                        foreach ($semesters as $item) {
                            ?>
                            <td><?php
                                //$result = $resultsModel->getSemesterContinuousAssessment($item->id, $subject->id);
                                $result = $resultsModel->getSemesterTotalResultsPerSubject($item->id, $subject->id);
//                                d($result)
                                if($result && !empty($result)) {
                                    echo $result;
                                } else {
                                    echo '-';
                                }
                                if(isset($total_marks[$item->id])) {
                                    $total_marks[$item->id] += is_numeric($result) ? $result : 0;
                                } else {
                                    $total_marks[$item->id] = is_numeric($result) ? $result : 0;
                                }

                                ?></td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
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
                </tr>
                <tr>
                    <td></td>
                    <th>Average</th>
                    <?php
                        foreach ($semesters as $item) {
                            ?>
                            <th><?php
                                $xt = 0;
                                if($n != 0) {
                                    $xt = $total_marks[$item->id]/$n;
                                }
                                echo number_format($xt, 2);
                                ?></th>
                            <?php
                        }
                    ?>
                </tr>
                <tr>
                    <td></td>
                    <th>Rank</th>
                    <?php
                        foreach ($semesters as $item) {
                            ?>
                            <th><?php echo $resultsModel->getSemesterRank($item->id).'/'.count($student->section->students); ?></th>
                            <?php
                        }
                    ?>
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
} else {
    ?>
    <div class="alert alert-danger">
        Invalid session or student
    </div>
    <?php
}
?>

<div style="text-align: right">
    <a href="<?php echo site_url(route_to('admin.academic.yearly_certificate.report-card', $student->id)); ?>" class="previous">&laquo; Previous </a>
    <a href="<?php echo site_url(route_to('parent.student.certificate.report-card', $student->id)); ?>" class="next">Next &raquo;</a>
</div>

<style>
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