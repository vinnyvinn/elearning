<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">School Exams</h6><br/>
                    <small class="text-white">School Exams</small>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    if(current_user_can('create_exam')) {
                        ?>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_exam"><i
                                class="fa fa-plus"></i> New Exam
                        </button>
                        <?php
                    }
                    do_action('exams_quick_action_buttons'); ?>
                </div>
                <?php
                if(current_user_can('create_exam')) {
                    ?>
                    <div class="modal fade new_exam" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                         style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                            <div class="modal-content">
                                <form class="ajaxForm" loader="true" method="post"
                                      action="<?php echo site_url(route_to('teacher.exams.create')); ?>">
                                    <input type="hidden" name="session" value="<?php echo active_session(); ?>" />
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="modal-title-default">New Exam</h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="sess">Exam Name</label>
                                            <input type="text" class="form-control" name="name"
                                                   value="<?php echo old('name') ?>" required/>
                                        </div>
                                        <div class="form-group">
                                            <label for="sess">Semester</label><br/>
                                            <select class="form-control select2" data-toggle="select2" name="semester" required >
                                                <option> -- Please select -- </option>
                                                <?php
                                                if($semesters && count($semesters) > 0) {
                                                    foreach ($semesters as $semester) {
                                                        ?>
                                                        <option value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Starting Date</label>
                                            <input type="text" class="form-control datepicker" name="starting_date" value="<?php echo old('starting_date'); ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label>Ending Date</label>
                                            <input type="text" class="form-control datepicker" name="ending_date" value="<?php echo old('ending_date'); ?>" required />
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
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <h4 class="h3 mb--1 pb-0">Current Session Exams</h4>
        </div>
        <?php
        $model = new \App\Models\Exams();
        $current_exams = $model->where('session', active_session())
            ->groupStart()
                ->where('class', NULL)
                ->orWhere('class', '')
            ->groupEnd()
            ->groupStart()
                ->where('section', NULL)
                ->orWhere('section', '')
            ->groupEnd()
            ->orderBy('id', 'DESC')->findAll();
        if($current_exams && count($current_exams) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table">
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
                    foreach ($current_exams as $current_exam) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><a href="<?php echo site_url(route_to('teacher.exams.view.index', $current_exam->id)); ?>"><?php echo $current_exam->name; ?></a></td>
                            <td><?php echo $current_exam->session ? $current_exam->session->name : '-'; ?></td>
                            <td><?php echo $current_exam->starting_date; ?></td>
                            <td><?php echo $current_exam->ending_date; ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.exams.view.index', $current_exam->id)); ?>">View</a>
                                <?php
                                if(current_user_can('create_exam')) {
                                    ?>
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".edit_exam<?php echo $current_exam->id; ?>"><i
                                            class="fa fa-cog"></i> Modify
                                    </button>
                                    <div class="modal fade edit_exam<?php echo $current_exam->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post"
                                                      action="<?php echo site_url(route_to('teacher.exams.create')); ?>">
                                                    <input type="hidden" name="id" value="<?php echo $current_exam->id; ?>" />
                                                    <input type="hidden" name="session" value="<?php echo active_session(); ?>" />
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Update Exam</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="sess">Exam Name</label>
                                                            <input type="text" class="form-control" name="name"
                                                                   value="<?php echo old('name', $current_exam->name); ?>" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sess">Semester</label><br/>
                                                            <select class="form-control select2" data-toggle="select2" name="semester" required >
                                                                <option> -- Please select -- </option>
                                                                <?php
                                                                if($semesters && count($semesters) > 0) {
                                                                    foreach ($semesters as $semester) {
                                                                        ?>
                                                                        <option <?php echo old('semester', @$current_exam->semester->id) == $semester->id ? 'selected' : ''; ?> value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Starting Date</label>
                                                            <input type="text" class="form-control datepicker" name="starting_date" value="<?php echo old('starting_date', $current_exam->starting_date); ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Ending Date</label>
                                                            <input type="text" class="form-control datepicker" name="ending_date" value="<?php echo old('ending_date', $current_exam->ending_date); ?>" required />
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
                                    <?php
                                }
                                if(current_user_can('delete_exam')) {
                                    ?>
                                    <?php if (isSuperAdmin()):?>
                                    <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('teacher.exams.delete', $current_exam->id)); ?>" url="<?php echo site_url(route_to('teacher.exams.delete', $current_exam->id)); ?>" data="action:delete|id:<?php echo $current_exam->id; ?>" warning-title="Delete Exam" warning-message="Do you really want to delete this exam and all of its data?" loader="true" ><i class="ni ni-fat-delete"></i> Delete</a>
                                    <?php endif;
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
            <div class="card-body">
                <div class="alert alert-danger">
                    No exams found
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="h3 mb--1 pb-0">Other Sessions' Exams</h4>
        </div>
        <?php
        $model = new \App\Models\Exams();
        $current_exams = $model->where('session !=', active_session())->orderBy('id', 'DESC')->findAll();
        if($current_exams && count($current_exams) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table">
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
                    foreach ($current_exams as $current_exam) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $current_exam->name; ?></td>
                            <td><?php echo $current_exam->session ? $current_exam->session->name : '-'; ?></td>
                            <td><?php echo $current_exam->starting_date; ?></td>
                            <td><?php echo $current_exam->ending_date; ?></td>
                            <td>
                                -
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
                    No exams found
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>