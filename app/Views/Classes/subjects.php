<div class="card">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Class Subjects</h3>
            </div>
            <div class="col text-right">
                <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_subject">New
                    Subject
                </button>
            </div>
        </div>
        <div class="modal fade new_subject" tabindex="-1" role="dialog" aria-labelledby="modal-default"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">
                    <form class="ajaxForm" loader="true" method="post"
                          action="<?php echo site_url(route_to('admin.subjects.create')); ?>">
                        <input type="hidden" name="class" value="<?php echo $class->id; ?>"/>
                        <div class="modal-header">
                            <h6 class="modal-title" id="modal-title-default">New Class Subject</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="sess">Subject Name</label>
                                <input type="text" class="form-control" name="name"
                                       value="<?php echo old('name') ?>" required/>
                            </div>
                            <div class="form-group">
                                <label for="sess">Pass Mark (%)</label>
                                <input type="number" min="0" max="100" class="form-control" name="pass_mark"
                                       value="<?php echo old('pass_mark', 40); ?>" required/>
                            </div>
                            <div class="form-group">
                                <label>Subject Optional?</label><br/>
                                <label class="custom-toggle">
                                    <input type="checkbox" name="optional"
                                           value="1" <?php echo old('optional', 0) == 1 ? 'checked' : ''; ?> />
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                          data-label-on="Yes"></span>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    $subjects = $class->subjects();
    if ($subjects && count($subjects) > 0) {
        ?>
        <div class="table-responsive">
            <!-- Projects table -->
            <table class="table align-items-center table-flush table-sm">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Pass Mark (%)</th>
                    <th>Optional</th>
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
                        <td><?php echo $subject->name; ?></td>
                        <td><?php echo $subject->pass_mark; ?></td>
                        <td>
                            <label class="custom-toggle disabled">
                                <input type="checkbox" disabled
                                       name="optional" <?php echo $subject->optional == 1 ? 'checked' : ''; ?> />
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                      data-label-on="Yes"></span>
                            </label>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="#!" data-toggle="modal"
                                       data-target=".edit_subject<?php echo $subject->id; ?>">Edit</a>
                                   <?php if (isSuperAdmin()):?>
                                    <a class="dropdown-item send-to-server-click text-danger" href="#!"
                                       url="<?php echo site_url(route_to('admin.subjects.delete', $subject->id)); ?>"
                                       data="action:delete|id:<?php echo $subject->id; ?>" loader="true"
                                       warning-title="Delete Subject"
                                       warning-message="Are you sure you want to delete this subject and all of its contents?">Delete</a>
                            <?php endif;?>
                                </div>
                            </div>
                            <div class="modal fade edit_subject<?php echo $subject->id; ?>" tabindex="-1"
                                 role="dialog" aria-labelledby="modal-default"
                                 style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                    <div class="modal-content">
                                        <form class="ajaxForm" loader="true" method="post"
                                              action="<?php echo site_url(route_to('admin.subjects.edit', $subject->id)); ?>">
                                            <input type="hidden" name="id" value="<?php echo $subject->id; ?>"/>
                                            <input type="hidden" name="class"
                                                   value="<?php echo $class->id; ?>"/>
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="modal-title-default">New Class
                                                    Subject</h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="sess">Subject Name</label>
                                                    <input type="text" class="form-control" name="name"
                                                           value="<?php echo old('name', $subject->name); ?>"
                                                           required/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sess">Pass Mark (%)</label>
                                                    <input type="number" min="0" max="100" class="form-control"
                                                           name="pass_mark"
                                                           value="<?php echo old('pass_mark', $subject->pass_mark); ?>"
                                                           required/>
                                                </div>
                                                <div class="form-group">
                                                    <label>Subject Optional?</label><br/>
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" name="optional"
                                                               value="1" <?php echo old('optional', $subject->optional) == 1 ? 'checked' : ''; ?> />
                                                        <span class="custom-toggle-slider rounded-circle"
                                                              data-label-off="No" data-label-on="Yes"></span>
                                                    </label>
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
                No subjects found for this class
            </div>
        </div>
        <?php
    }
    ?>
</div>