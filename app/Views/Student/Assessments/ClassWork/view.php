<?php
//d($classwork);
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Class Work : <?php echo $classwork->name; ?></h6>
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
            if($classwork->items) {
                ?>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="">
                    <?php
                    $n = 0;
                    foreach ($classwork->items as $item) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $classwork->subject->name; ?></td>
                            <td><?php echo $classwork->deadline->format('d/m/Y'); ?></td>
                            <td><?php
                                $model = new \App\Models\ClassWorkSubmissions();
                                if($existing_submission = $model->where('class_work', $classwork->id)
                                    //->where('classwork_item', $item->id)
                                    ->where('subject', $classwork->subject->id)->where('student_id', $student->id)->get()->getFirstRow('object')) {
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
                                <a class="btn btn-primary btn-sm" href="<?php echo site_url(route_to('student.assessments.classwork.do_classwork', $classwork->id, $classwork->id)); ?>">View Classwork</a>
                            </td>
                        </tr>
                        <?php
                    }

                    ?>
                    </tbody>
                </table>
                <?php
            }
            ?>

        </div>
    </div>
</div>
