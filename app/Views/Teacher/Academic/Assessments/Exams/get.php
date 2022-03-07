<div>
    <?php
    d($exams);
    ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Session</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            foreach ($exams as $exam) {
                $n++;
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo $exam->name; ?></td>
                    <td><?php echo $exam->session->name; ?></td>
                    <td><?php echo $exam->given->format('d/m/Y'); ?></td>
                    <td><?php echo $exam->deadline->format('d/m/Y'); ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.academic.assessments.exams.view', $exam->id)); ?>">View</a>
                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.academic.assessments.exams.results', $exam->id)); ?>">View</a>
                        <a class="btn btn-sm btn-warning" href=""><i class="fa fa-edit"></i> Edit</a>
                       <?php if (isSuperAdmin()):?>
                        <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('teacher.academic.assessments.exams.delete', $exam->id)); ?>" url="<?php echo site_url(route_to('teacher.academic.assessments.exams.delete', $exam->id)); ?>" data="action:delete|id:<?php echo $exam->id; ?>" loader="true" warning-title="Delete exam" warning-message="This action will delete everything tied to this exam; including the questions and results" warning-button="Delete">Delete</a>
                      <?php endif;?>
                        <div class="modal fade edit_exam<?php echo $exam->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                             style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                <div class="modal-content">
                                    <form class="ajaxForm" loader="true" method="post" data-parsley-validate=""
                                          action="<?php echo site_url(route_to('teacher.academic.assessments.exam.create')); ?>">
                                        <input type="hidden" name="id" value="<?php echo $exam->id; ?>" />
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="modal-title-default">Update Class Work</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="sess">Name</label>
                                                <input type="text" class="form-control" name="name"
                                                       value="<?php echo old('name', $exam->name); ?>" required/>
                                            </div>
                                            <div class="form-group">
                                                <label>Semester</label>
                                                <select class="form-control select2" name="semester" required>
                                                    <option value="">Select semester</option>
                                                    <?php
                                                    $semesters = @getSession()->semesters;
                                                    if($semesters && count($semesters) > 0) {
                                                        foreach ($semesters as $semester) {
                                                            ?>
                                                            <option <?php echo $semester->id == $exam->semester ? 'selected' : ''; ?> value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Class</label>
                                                <select name="class" id="cls_class_id" class="form-control form-control-sm select2"
                                                        data-toggle="select2"
                                                        onchange="getSelectSections($(this).val())" required>
                                                    <option value="">Select a class</option>
                                                    <?php
                                                    $classes = getSession()->classes()->findAll();

                                                    foreach ($classes as $class) {
                                                        ?>
                                                        <option <?php echo $class->id == $exam->class->id ? 'selected' : ''; ?> value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="sess">Starting Date</label>
                                                <input type="text" class="form-control datepicker" name="starting_date"
                                                       value="<?php echo old('given', $exam->starting_date); ?>" required/>
                                            </div>
                                            <div class="form-group">
                                                <label for="sess">Deadline</label>
                                                <input type="text" class="form-control datepicker" name="ending_date"
                                                       value="<?php echo old('deadline', $exam->ending_date); ?>" required/>
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
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>