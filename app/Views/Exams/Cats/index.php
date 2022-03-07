<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Continuous Assessment Tests</h6><br/>
                    <small class="text-white">Continuous Assessment Tests</small>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_exam"><i
                            class="fa fa-plus"></i> New Test
                    </button>
                    <?php do_action('cats_quick_action_buttons'); ?>
                </div>
            </div>
            <div class="modal fade new_exam" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                    <div class="modal-content">
                        <form class="ajaxForm" loader="true" method="post"
                              action="<?php echo site_url(route_to('admin.exams.create')); ?>">
                            <input type="hidden" name="session" value="<?php echo active_session(); ?>" />
                            <div class="modal-header">
                                <h6 class="modal-title" id="modal-title-default">New Test</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="sess">Continuous Assessment Exam Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                           value="<?php echo old('name') ?>" required/>
                                </div>
                                <div class="form-group">
                                    <label for="sess">Semester <span class="text-danger">*</span></label><br/>
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
                                    <label for="sess">Class <span class="text-danger">*</span></label><br/>
                                    <select class="form-control select2" data-toggle="select2" name="class" id="class" onchange="getSections()" required >
                                        <option value=""> -- Please select -- </option>
                                        <?php
                                        $classes = (new \App\Models\Classes())->where('session', active_session())->findAll();
                                        if($classes && count($classes) > 0) {
                                            foreach ($classes as $class) {
                                                ?>
                                                <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sess">Section</label><br/>
                                    <select class="form-control select2" data-toggle="select2" name="section" id="section" >
                                        <option value=""> -- Please select -- </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Starting Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker" name="starting_date" value="<?php echo old('starting_date'); ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Ending Date <span class="text-danger">*</span></label>
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
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <h4 class="h3 mb--1 pb-0">Continuous Assessment Tests</h4>
        </div>
        <?php
        if($cats && count($cats) > 0) {
            ?>
            <div class="table-responsive pt-2">
                <table class="table datatable" id="datatable-buttons">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Session</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Starting Date</th>
                        <th>Ending Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($cats as $current_exam) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><a href="<?php echo site_url(route_to('admin.exams.cats.view.index', $current_exam->id)); ?>"><?php echo $current_exam->name; ?></a></td>
                            <td><?php echo $current_exam->session ? $current_exam->session->name : '-'; ?></td>
                            <td><?php echo $current_exam->class ? $current_exam->class->name : '-'; ?></td>
                            <td><?php echo $current_exam->section ? $current_exam->section->name : '-'; ?></td>
                            <td><?php echo $current_exam->starting_date; ?></td>
                            <td><?php echo $current_exam->ending_date; ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.exams.cats.view.index', $current_exam->id)); ?>">View</a>
                        <?php if (isSuperAdmin()):?>
                                <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('admin.exams.cats.delete', $current_exam->id)); ?>" url="<?php echo site_url(route_to('admin.exams.cats.delete', $current_exam->id)); ?>" data="action:delete|id:<?php echo $current_exam->id; ?>" warning-title="Delete Test" warning-message="Do you really want to delete this continuous test and all of its data?" loader="true" ><i class="ni ni-fat-delete"></i> Delete</a>
                        <?php endif;?>
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
                    No Continuous Assessment Tests found
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
    var getSections = function () {
        var classId = $('#class').val();
        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#section').html(data);
            });
        }
    };
</script>