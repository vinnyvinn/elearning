<?php


use App\Libraries\YearlyResults; ?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo ucwords($student->profile->name); ?></h6>
                    <br/>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('student_student_exam_results_action_buttons', $student); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <?php
            $session = getSession();
            $semesters = $session->semesters;
            if (isset($semesters) && is_array($semesters) && count($semesters) > 0) {
                $resultsModel = new YearlyResults($student->id, $session->id);
                ?>
                <a class="btn btn-sm btn-warning" target="_blank"
                   href="<?php echo site_url(route_to('student.certificate.print', $student->id)); ?>">Print</a>
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
                                        if ($result && !empty($result)) {
                                            echo $result;
                                        } else {
                                            echo '-';
                                        }
                                        if (isset($total_marks[$item->id])) {
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
                                    if ($n != 0) {
                                        $xt = $total_marks[$item->id] / $n;
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
                                <th><?php echo $resultsModel->getSemesterRank($item->id) . '/' . count($student->class->students); ?></th>
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
            ?>

        </div>
    </div>
</div>
