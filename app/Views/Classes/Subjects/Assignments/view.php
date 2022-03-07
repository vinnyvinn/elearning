<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-8 col-9">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $subject->name; ?>: <?php echo @$title; ?></h6><br/>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.classes.index')); ?>">Classes</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.classes.view', $assignment->class->id)); ?>"><?php echo $assignment->class->name; ?></a></li>
                            <?php
                            if($section = $assignment->section) {
                                ?>
                                <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.class.sections.view', $section->id)); ?>"><?php echo $section->name; ?></a></li>
                                <?php
                            }
                            ?>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $subject->name; ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 col-3 text-right">
                    <?php do_action('subject_assignment_actions', $subject, $assignment); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h5>Assignment Description</h5>
                    <p><?php echo $assignment->description; ?></p>
                </div>
                <div class="col-md-4">
                    <h5>Books to Cover</h5>
                    <p><?php echo $assignment->books; ?></p>
                </div>
                <div class="col-md-4">
                    <h5>File</h5>
                    <?php
                    if($assignment->file) {
                        ?>
                        <a class="btn btn-primary btn-sm" href="<?php echo site_url(route_to('admin.academic.assignment.download', $assignment->id)); ?>"> <i class="fa fa-cloud-download-alt"></i> Download File</a>
                        <?php
                    } else {
                        echo "Not Found";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <?php
        if($submissions && count($submissions) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Adm #</th>
                            <th>Submission Date</th>
                            <th>File</th>
                            <th>Marks Awarded</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($submissions as $submission) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $submission->student->profile->first_name.' '.$submission->student->profile->last_name; ?></td>
                            <td><?php echo $submission->student->admission_number; ?></td>
                            <td><?php echo $submission->updated_at; ?></td>
                            <td><a href="<?php echo $submission->file; ?>">View File</a></td>
                            <td><?php echo $submission->marks_awarded ? $submission->marks_awarded : '-' ; ?></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".award<?php echo $submission->id; ?>"><?php echo $submission->marks_awarded ? 'Update Marks' : 'Award' ; ?></button>
                              <?php if (isSuperAdmin()):?>
                                <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('admin.subjects.assignments.delete_submission', $subject->id, $submission->id)); ?>" url="<?php echo site_url(route_to('admin.subjects.assignments.delete_submission', $subject->id, $submission->id)); ?>" data="action:delete_submission|id:<?php echo $submission->id; ?>" warning-title="Confirm" warning-message="Are you sure you want to delete this student's assignment?" loader="true">Delete</a>
                             <?php endif;?>
                                <div class="modal fade award<?php echo $submission->id; ?>" tabindex="-1"
                                     role="dialog" aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                                  action="<?php echo site_url(route_to('admin.subjects.assignments.award_marks', $subject->id, $assignment->id)); ?>">
                                                <input type="hidden" name="id" value="<?php echo $submission->id; ?>"/>
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modal-title-default">Award Marks to <?php echo $submission->student->profile->first_name.' '.$submission->student->profile->last_name; ?></h6>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Marks</label>
                                                        <input type="number" class="form-control" min="0" max="<?php echo $assignment->out_of; ?>" name="marks_awarded" value="<?php echo old('marks_awarded', $submission->marks_awarded); ?>" required />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                    <button type="button" class="btn btn-link  ml-auto"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
            <div class="card-body">
                <div class="alert alert-danger">
                    No student has submitted the assignments yet
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>