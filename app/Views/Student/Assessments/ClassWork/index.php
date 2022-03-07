<?php
$model = (new \App\Models\ClassWorkItems())
    ->where('session', active_session());
    if($semester = \Config\Services::request()->getGet('semester')) {
        $model->where('semester', $semester);
    }
    $model->orderBy('id', 'DESC');
    $model->where('published', 1);
$classwork = $model->where('class', $student->class->id)->findAll();

//d($classwork);
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Class Work</h6>
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
            if($classwork) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Class Work</th>
                                <th>Subject</th>
                                <th>Semester</th>
                                <th>Given Date</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($classwork as $item) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $item->name; ?></td>
                                <td><?php echo $item->subject->name; ?></td>
                                <td><?php echo $item->semester ? $item->semester->name : ''; ?></td>
                                <td><?php echo $item->given->format('d/m/Y'); ?></td>
                                <td><?php echo $item->deadline->format('d/m/Y'); ?></td>
                                <td>
                                    <?php
                                    $model = new \App\Models\ClassWorkSubmissions();
                                    if($existing_submission = $model->where('class_work', $item->id)
                                        //->where('classwork_item', $item->id)
                                        ->where('subject', $item->subject->id)->where('student_id', $student->id)->get()->getFirstRow('object')) {
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
                                        echo $existing_submission->score.'/'.$item->out_of;
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if($existing_submission) {
                                        ?>
                                        <a class="btn btn-info btn-sm" href="<?php echo site_url(route_to('student.assessments.classwork.do_classwork', $item->id, $item->id)); ?>">View</a>
                                        <?php
                                    } else {
                                        ?>
                                        <a class="btn btn-info btn-sm" href="<?php echo site_url(route_to('student.assessments.classwork.do_classwork', $item->id, $item->id)); ?>">Submit</a>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-warning">
                    No Class Work found
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
