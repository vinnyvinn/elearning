<?php
//d($exam);
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Exam : <?php echo $exam->name; ?></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <?php
            if($exam->items) {
                ?>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Starting Date</th>
                            <th>Ending Date</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="">
                    <?php
                    $n = 0;
                    foreach ($exam->items as $item) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $exam->subject->name; ?></td>
                            <td><?php echo $exam->starting_date; ?></td>
                            <td><?php echo $exam->ending_date; ?></td>
                            <td><?php
                                $model = new \App\Models\CatExamSubmissions();
                                if($existing_submission = $model->where('cat_exam', $exam->id)
                                    ->where('subject', $exam->subject->id)->get()->getFirstRow('object')) {
                                    ?>
                                    <span class="badge badge-success">Submitted</span>
                                    <?php
                                } else {
                                    ?>
                                    <span class="badge badge-warning">Not Submitted</span>
                                    <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($existing_submission) {
                                    echo $existing_submission->score;
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="<?php echo site_url(route_to('student.assessments.exam.do_exam', $exam->id, $exam->id)); ?>">View Exam</a>
                            </td>
                        </tr>
                        <?php
                    }

                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                ?>
                <div class="alert alert-danger">
                    No exams created
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>
