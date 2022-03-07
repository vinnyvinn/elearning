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
    <div class="">
        <div class="">
            <?php
            // Check assignments
            $assignments = (new \App\Models\Assignments())->where('class', $student->class->id)
                ->groupStart()
                ->where('section', $student->section->id)
                ->orWhere('section', '')
                ->groupEnd()
                ->findAll();
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
                                    <td><a href="<?php echo $assignment->file; ?>">Download File</a></td>
                                    <td><?php echo $isSubmitted ? '<span class="badge badge-success">Submitted</span>' : '<span class="badge badge-warning">Pending</span>'; ?></td>
                                    <td><?php echo ($isSubmitted && $isSubmitted->marks_awarded) ? $isSubmitted->marks_awarded : '-'; ?></td>
                                    <td><?php echo $assignment->out_of; ?></td>
                                    <td>
                                        <?php
                                        if(strtotime($assignment->deadline) >= time() && $isSubmitted->marks_awarded == '') {
                                            ?>
                                            <?php
                                            if($isSubmitted) {
                                                ?>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target=".submit_assignment">Update</button>
                                                <?php
                                            } else {
                                                ?>
                                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".submit_assignment">Submit</button>
                                                <?php
                                            }
                                            ?>
                                            <div class="modal fade submit_assignment" tabindex="-1" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                                    <div class="modal-content">
                                                        <form class="ajaxForm" method="post" action="<?php echo site_url(route_to('student.assignments.submit', $assignment->id)); ?>" loader="true">
                                                            <?php
                                                            if($isSubmitted) {
                                                                ?>
                                                                <input type="hidden" name="id" value="<?php echo $student->id; ?>" />
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
                                            echo "-";
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
                <div class="container-fluid mt--6">
                    <div class="alert alert-warning">
                        No assignments for you at the moment
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>