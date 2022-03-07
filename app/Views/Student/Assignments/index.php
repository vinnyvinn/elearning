<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('student_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills nav-pill-bordered">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-pill1" data-toggle="pill" href="#pill1" aria-expanded="true" value="0999">
                            Workout Assignment
                        </a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link" id="base-pill2" data-toggle="pill" href="#pill2" aria-expanded="true" value="0999">
                        Written Assignment
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content px-1 pt-1">
        <div role="tabpanel" class="tab-pane active" id="pill1" aria-expanded="true" aria-labelledby="base-pill1">
        <?php
        (new \App\Models\Students());
            // Check assignments
            $assignments = $student->assignments;
            if($assignments && count($assignments)) {
                ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Assignments </h3>
                            </div>
                            <div class="col-4 text-right">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Deadline</th>
                                <th>Description</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Awarded</th>
                                <th>Out Of</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($assignments as $assignment) {
                                $n++;
                                $isSubmitted = $assignment->isSubmitted($student->id);
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $assignment->subject->name; ?></td>
                                    <td><?php echo $assignment->deadline; ?></td>
                                    <td>
                                        <?php echo $assignment->description; ?><br/>
                                        <b>Books to Cover</b>:
                                        <?php echo $assignment->books; ?>
                                    </td>
                                    <td><a href="<?php echo site_url(route_to('student.assignments.download', $assignment->id)); ?>">Download File</a></td>
                                    <td>
                                        <?php if($isSubmitted) {
                                            echo '<span class="badge badge-success">Submitted</span><br/>';
                                            ?>
                                            on <?php echo $isSubmitted->created_at->format('d/m/Y \a\t h:i A'); ?><br/>
                                            <a href="<?php echo site_url(route_to('student.assignments.download_submission', $assignment->id)); ?>">View Submitted Assignment</a>
                                            <?php
                                        } else {
                                            echo '<span class="badge badge-warning">Pending</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo ($isSubmitted && $isSubmitted->marks_awarded) ? $isSubmitted->marks_awarded : '-'; ?></td>
                                    <td><?php echo $assignment->out_of; ?></td>
                                    <td>
                                        <?php
                                        //d(date('d/m/Y', strtotime($assignment->deadline)));
                                        if(strtotime($assignment->deadline) >= time() && (!$isSubmitted || empty($isSubmitted->marks_awarded))) {
                                            ?>
                                            <?php
                                            if($isSubmitted) {
                                                ?>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target=".submit_assignment<?php echo $assignment->id; ?>">Update</button>
                                                <?php
                                            } else {
                                                ?>
                                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".submit_assignment<?php echo $assignment->id; ?>">Submit</button>
                                                <?php
                                            }
                                            ?>
                                            <div class="modal fade submit_assignment<?php echo $assignment->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                                    <div class="modal-content">
                                                        <form class="ajaxForm" method="post" action="<?php echo site_url(route_to('student.assignments.submit', $assignment->id)); ?>" loader="true">
                                                            <?php
                                                            if($isSubmitted) {
                                                                ?>
                                                                <input type="hidden" name="id" value="<?php echo $isSubmitted->id; ?>" />
                                                                <?php
                                                            }
                                                            ?>
                                                            <input type="hidden" name="student_id" value="<?php echo $student->id; ?>" />
                                                            <input type="hidden" name="assignment_id" value="<?php echo $assignment->id; ?>" />
                                                            <input type="hidden" name="subject_id" value="<?php echo $assignment->subject->id; ?>" />
                                                            <div class="modal-header">
                                                                <h6 class="modal-title" id="modal-title-default">Submit Assignment</h6>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Assigment File</label>
                                                                    <input type="file" name="assignment" class="form-control" required />
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            Deadline passed.<br/>Contact teacher
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
                </div>
                <?php
            } else {
                ?>
                <div class="row">
                    <div class="alert alert-warning">
                        No assignments for you at the moment
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="pill2" aria-expanded="true" aria-labelledby="base-pill2">
            <?php
            (new \App\Models\AssignmentItems());
            $assignments = $student->written_assignments;
            if ($assignments && count($assignments)):
            ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Starting Sate</th>
                            <th>Ending Date</th>
                            <th>Status</th>
                            <th>Awarded</th>
                            <th>Out Of</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($assignments as $assignment) {
                            $n++;
                            (new \App\Models\AssignmentItems());
                            $isSubmitted = $assignment->isSubmitted($student->id);
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $assignment->question; ?></td>
                                <td><?php echo $assignment->subject->name; ?></td>
                                <td><?php echo date('d/m/Y',strtotime($assignment->given)); ?></td>
                                <td><?php echo date('d/m/Y',strtotime($assignment->deadline)); ?></td>
                                <td>
                                    <?php if($isSubmitted) {
                                        echo '<span class="badge badge-success">Submitted</span><br/>';
                                        ?>
                                        on <?php echo $isSubmitted->created_at->format('d/m/Y \a\t h:i A'); ?><br/>
                                        <?php
                                    } else {
                                        echo '<span class="badge badge-warning">Pending</span>';
                                    }?>
                                </td>
                                <td><?php echo $student->marks_scored($assignment->id); ?></td>
                                <td><?php echo $assignment->out_of; ?></td>
                                <td>
                                 <?php if (!$isSubmitted)?>
                                    <a href="<?php echo site_url(route_to('student.assignments.submit_assignment',$student->id,$assignment->id))?>" class="btn btn-primary btn-sm"><?php if (!$isSubmitted):?>Submit<?php else:?>Redo<?php endif;?></a>
<!--                                --><?php
//                                    //d(date('d/m/Y', strtotime($assignment->deadline)));
//                                    if(strtotime($assignment->deadline) >= time() && (!$isSubmitted || empty($isSubmitted->marks_awarded))) {
//                                        ?>
<!--                                        --><?php
//                                        if($isSubmitted) {
//                                            ?>
<!--                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target=".submit_assignmentwr--><?php //echo $assignment->id; ?><!--">Update</button>-->
<!--                                            --><?php
//                                        } else {
//                                            ?>
<!--                                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".submit_assignmentwr--><?php //echo $assignment->id; ?><!--">Submit</button>-->
<!--                                            --><?php
//                                        }
//                                        ?>
<!--                                        <div class="modal fade submit_assignmentwr--><?php //echo $assignment->id; ?><!--" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">-->
<!--                                            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">-->
<!--                                                <div class="modal-content">-->
<!--                                                    <form class="ajaxForm" method="post" action="--><?php //echo site_url(route_to('student.assignments.submit', $assignment->id)); ?><!--" loader="true">-->
<!--                                                        --><?php
//                                                        if($isSubmitted) {
//                                                            ?>
<!--                                                            <input type="hidden" name="id" value="--><?php //echo $isSubmitted->id; ?><!--" />-->
<!--                                                            --><?php
//                                                        }
//                                                        ?>
<!--                                                        <input type="hidden" name="student_id" value="--><?php //echo $student->id; ?><!--" />-->
<!--                                                        <input type="hidden" name="assignment_id" value="--><?php //echo $assignment->id; ?><!--" />-->
<!--                                                        <input type="hidden" name="subject_id" value="--><?php //echo $assignment->subject->id; ?><!--" />-->
<!--                                                        <div class="modal-header">-->
<!--                                                            <h6 class="modal-title" id="modal-title-default">Submit Assignment</h6>-->
<!--                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                                                                <span aria-hidden="true">×</span>-->
<!--                                                            </button>-->
<!--                                                        </div>-->
<!--                                                        <div class="modal-body">-->
<!--                                                            <div class="form-group">-->
<!--                                                                <label>Assigment File</label>-->
<!--                                                                <input type="file" name="assignment" class="form-control" required />-->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                        <div class="modal-footer">-->
<!--                                                            <button type="submit" class="btn btn-primary">Submit</button>-->
<!--                                                            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>-->
<!--                                                        </div>-->
<!--                                                    </form>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        --><?php
//                                    } else {
//                                        ?>
<!--                                        Deadline passed.<br/>Contact teacher-->
<!--                                        --><?php
//                                    }
//                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <?php else:?>
            <div class="row">
                <div class="">
                    <div class="">
                        <div class="alert alert-warning">
                            No assignments for you at the moment
                        </div>
                    </div>
                </div>
            </div>
            <?php endif;?>
        </div>
</div>