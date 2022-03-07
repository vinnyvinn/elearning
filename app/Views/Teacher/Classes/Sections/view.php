<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Class Subjects</h3>
                    </div>
                </div>
            </div>
            <?php
            $subjects = $section->class->subjects();
            //d($subjects);
            if ($subjects && count($subjects) > 0) {
                ?>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush table-sm mb-8">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Pass Mark (%)</th>
                            <th>Optional</th>
                            <th>Teacher</th>
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
                                <td><a href="<?php echo site_url(route_to('teacher.subjects.view', $subject->id, $section->id)); ?>"><?php echo $subject->name; ?></a></td>
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
                                    <?php
                                    $tea = $subject->getTeacher($section->id);
                                    //d($tea);
                                    if($tea) {
                                        echo $tea->profile->name;
                                        //d($tea->profile->name);
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="<?php echo site_url(route_to('teacher.subjects.view', $subject->id, $section->id)); ?>">View</a>
                                            <a class="dropdown-item" href="<?php echo site_url(route_to('teacher.subjects.view', $subject->id, $section->id)); ?>">Lesson Plan</a>
                                            <a class="dropdown-item" href="<?php echo site_url(route_to('teacher.subjects.assignments', $subject->id, $section->id)); ?>">Assignments</a>
                                            <a class="dropdown-item" href="<?php echo site_url(route_to('teacher.subjects.notes', $subject->id, $section->id)); ?>">Notes</a>
                                            <a class="dropdown-item" href="#!" data-toggle="modal" data-target=".add_teacher<?php echo $subject->id; ?>">Update Teacher</a>
                                        </div>
                                    </div>
                                    <div class="modal fade add_teacher<?php echo $subject->id; ?>" tabindex="-1"
                                         role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                                      action="<?php echo site_url(route_to('teacher.subjects.add_teacher', $subject->id, $section->id)); ?>">
                                                    <?php
                                                    if($tea) {
                                                        ?>
                                                        <input type="hidden" name="id" value="<?php echo $tea->id; ?>" />
                                                        <?php
                                                    }
                                                    ?>
                                                    <input type="hidden" name="class_id" value="<?php echo $section->class->id; ?>"/>
                                                    <input type="hidden" name="section_id" value="<?php echo $section->id; ?>"/>
                                                    <input type="hidden" name="subject_id" value="<?php echo $subject->id; ?>"/>
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Subject Teacher</h6>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Teacher</label><br/>
                                                            <select class="form-control select2" name="teacher_id" required>
                                                                <option> -- Please Select -- </option>
                                                                <?php
                                                                $teachers = (new \App\Models\Teachers())->orderBy('id', 'DESC')->findAll();
                                                                if($teachers && count($teachers) > 0) {
                                                                    foreach ($teachers as $teacher) {
                                                                        ?>
                                                                        <option value="<?php echo $teacher->id ?>"><?php echo $teacher->profile->name; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
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
    </div>
</div>