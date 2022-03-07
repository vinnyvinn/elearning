<div class="card">
    <?php
    $assignments = $section->getAssignments($subject->id);
    if($assignments && count($assignments) > 0) {
        ?>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Books To Cover</th>
                        <th>Deadline</th>
                        <th>File</th>
                        <th>Submissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($assignments as $assignment) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $assignment->description; ?></td>
                        <td><?php echo $assignment->books; ?></td>
                        <td><?php echo $assignment->deadline; ?></td>
                        <td><a href="<?php echo $assignment->file; ?>">Download File</a></td>
                        <td><a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.subjects.assignments.view', $subject->id, $assignment->id)); ?>">View</a></td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="#!" data-toggle="modal" data-target=".edit_assignment<?php echo $assignment->id; ?>">Edit</a>
                    <?php if (isSuperAdmin()):?>
                                    <a class="dropdown-item text-danger send-to-server-click" url="<?php echo site_url(route_to('teacher.subject.assignments.delete', $assignment->id)); ?>" data="action:delete|id:<?php echo $assignment->id ?>" warning-title="Delete Assignment" warning-message="Do you really want to delete this entry?" loader="true" href="<?php echo site_url(route_to('teacher.subject.assignments.delete', $assignment->id)); ?>">Delete</a>
                          <?php endif;?>
                                </div>
                            </div>
                            <div class="modal fade edit_assignment<?php echo $assignment->id; ?>" tabindex="-1"
                                 role="dialog" aria-labelledby="modal-default"
                                 style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                              action="<?php echo site_url(route_to('teacher.subjects.assignments', $subject->id, $section->id)); ?>">
                                            <input type="hidden" name="id" value="<?php echo $assignment->id; ?>"/>
                                            <input type="hidden" name="class" value="<?php echo $section->class->id; ?>"/>
                                            <input type="hidden" name="section" value="<?php echo $section->id; ?>"/>
                                            <input type="hidden" name="subject" value="<?php echo $subject->id; ?>"/>
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="modal-title-default">New Assignment</h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea class="form-control" name="description" rows="4" required><?php echo old('description', $assignment->description); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Books to Cover</label>
                                                    <textarea class="form-control" name="books" rows="4"><?php echo old('books', $assignment->books); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sess">File</label>
                                                    <input type="file" class="form-control"
                                                           name="file" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Deadline</label>
                                                    <input type="text" name="deadline" class="form-control datetimepicker datetime" required value="<?php echo old('deadline', $assignment->deadline); ?>" />
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
                No assignments found fot this class
            </div>
        </div>
        <?php
    }
    ?>
</div>