<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo ucwords($student->profile->name); ?></h6><br/>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('student_student_exam_results_action_buttons', $student, $exam); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div>Name: <b><?php echo $student->profile->name; ?></b></div>
                    <div>Admission Number: <b><?php echo $student->admission_number; ?></b></div>
                    <div>Exam: <b><?php echo $exam->name; ?></b></div>
                </div>
                <div class="col-md-6">
                    <div>Academic Session: <b><?php echo getSession()->name; ?></b></div>
                    <div>Starting Date: <b><?php echo $exam->starting_date; ?></b></div>
                    <div>Ending Date: <b><?php echo $exam->ending_date; ?></b></div>
                </div>
            </div>
            <br/>
            <div>
                <?php
                $resModel = new \App\Models\ExamResults();
                $exam_results = $resModel->getResultsAndRank($student->id, $exam->id);

                if ($exam_results) {
                    ?>
<!--                    <div class="table-responsive">-->
<!--                        <table class="table">-->
<!--                            <thead class="thead-light">-->
<!--                            <tr>-->
<!--                                <th>#</th>-->
<!--                                <th>Subject</th>-->
<!--                                <th>Pass Mark</th>-->
<!--                                <th>Score</th>-->
<!--                                <th>Remarks</th>-->
<!--                            </tr>-->
<!--                            </thead>-->
<!--                            <tbody>-->
<!--                            --><?php
//                            $n = 0;
//                            foreach ($student->class->subjects as $subject) {
//                                $rs = $resModel->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $student->class->id, 'subject' => $subject->id])->get()->getRowObject();
//                                $n++;
//                                $total_marks = $rs ? $rs->mark : 0;
//                                ?>
<!--                                <tr>-->
<!--                                    <td>--><?php //echo $n; ?><!--</td>-->
<!--                                    <td>--><?php //echo $subject->name; ?><!--</td>-->
<!--                                    <td>--><?php //echo $subject->pass_mark; ?><!--</td>-->
<!--                                    <td>--><?php //echo $rs ? $rs->mark : '-'; ?><!--</td>-->
<!--                                    <td>--><?php //echo $rs ? $rs->remark : '-'; ?><!--</td>-->
<!--                                </tr>-->
<!--                                --><?php
//                            }
//                            ?>
<!--                            <tr>-->
<!--                                <th></th>-->
<!--                                <th>Total</th>-->
<!--                                <td></td>-->
<!--                                <td>--><?php //echo @number_format($total_marks, 2); ?><!--</td>-->
<!--                                <td></td>-->
<!--                                <td></td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <th></th>-->
<!--                                <th>Average</th>-->
<!--                                <td></td>-->
<!--                                <td>--><?php //echo @number_format($total_marks/count($student->class->subjects), 2); ?><!--</td>-->
<!--                                <td></td>-->
<!--                                <td></td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <th></th>-->
<!--                                <th>Rank</th>-->
<!--                                <td></td>-->
<!--                                <td>--><?php //echo $resModel->getRank($student->id, $exam->id, $student->class->id); ?><!--</td>-->
<!--                                <td></td>-->
<!--                                <td></td>-->
<!--                            </tr>-->
<!--                            </tbody>-->
<!--                        </table>-->
<!--                    </div>-->
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Score</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            $total = 0;
                            $divisor = 0;
                            foreach ($exam_results['marks'] as $key=>$result) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $exam_results['labels'][$key]; ?></td>
                                    <td><?php echo $result; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td></td>
                                <th>Total</th>
                                <th><?php echo $exam_results['total_marks']; ?></th>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Average</th>
                                <th><?php echo $exam_results['average']; ?></th>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Rank</th>
                                <th><?php echo $exam_results['rank']; ?></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="alert alert-warning">
                        Results not found
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>
