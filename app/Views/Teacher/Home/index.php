<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('teacher_quick_action_buttons', $teacher); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="h3 mb-0">My Classes</h5>
                </div>
                <?php
                $subjects = $teacher->subjects;
                //d($subjects);
                if($subjects && count($subjects)) {
                    ?>
                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                  <tr>
                                    <th>#</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Subject</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $n = 0;
                                foreach ($subjects as $subject) {
                                    $n++;
                                    ?>
                                    <tr>
                                        <td><?php echo $n; ?></td>
                                        <td><?php echo $subject->class->name; ?></td>
                                        <td><?php echo $subject->section->name; ?></td>
                                        <td><?php echo $subject->subject->name; ?></td>
                                        <td>
                                            <?php //d($subject->subject); ?>
                                            <a href="<?php echo site_url(route_to('teacher.subjects.view', $subject->subject->id, $subject->section->id)); ?>" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            No class subjects have been assigned to you
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>