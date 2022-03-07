<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Workout Assignments</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target=".new_assignment">New Assignment</button>
                </div>
            </div>
            <div class="modal fade new_assignment"
                 role="dialog" aria-labelledby="modal-default"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                              action="<?php echo site_url(route_to('admin.academic.new_assignment')); ?>">
                            <input type="hidden" name="session" value="<?php echo active_session(); ?>" />
                            <div class="modal-header">
                                <h6 class="modal-title" id="modal-title-default">New Assignment</h6>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Semester</label>
                                    <select name="semester" class="form-control form-control-sm" required>
                                        <option value="">--Select Semester--</option>
                                        <?php
                                        $semesters = @getSession()->semesters;
                                        if(!empty($semesters) && count($semesters) > 0) {
                                            foreach ($semesters as $semester) {
                                                ?>
                                                <option value="<?php echo $semester->id ?>"><?php echo $semester->name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Class</label>
                                    <select name="class" id="ass_class_id" class="form-control form-control-sm select2"
                                            data-toggle="select2"
                                            onchange="getAssSections($(this).val())" required>
                                        <option value="">Select class</option>
                                        <?php
                                        $classes = getSession()->classes()->findAll();

                                        foreach ($classes as $class) {
                                            echo '<option value="' . $class->id . '">' . $class->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Subject</label>
                                    <select name="subject" id="ass_subject_id" class="form-control form-control-sm select2"
                                            data-toggle="select2" required>
                                        <option value="">Select Subject</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Title</label>
                                    <input class="form-control" name="description" value="<?php echo old('description'); ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Books to Cover</label>
                                    <textarea class="form-control" name="books" rows="4"><?php echo old('books'); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="sess">File</label>
                                    <input type="file" class="form-control"
                                           name="file"
                                           required/>
                                </div>
                                <div class="form-group">
                                    <label>Out Of</label>
                                    <input type="number" name="out_of" class="form-control" min="0" max="100" required value="<?php echo old('out_of'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label>Deadline</label>
                                    <input type="text" name="deadline" class="form-control datepicker" id="datepicker" data-toggle="datepicker" required value="<?php echo old('deadline'); ?>" />
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
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <div class="row mt-3 justify-content-center" style="padding-left:1em;padding-right:1em">
                <div class="col-md-3 mb-1">
                    <select name="semester" id="semester_id" class="form-control form-control-sm" required>
                        <option value="">--Select Semester--</option>
                        <?php
                        $semesters = @getSession()->semesters;
                        if(!empty($semesters) && count($semesters) > 0) {
                            foreach ($semesters as $semester) {
                                ?>
                                <option value="<?php echo $semester->id ?>"><?php echo $semester->name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-1">
                    <select name="class" id="class_id" class="form-control form-control-sm select2"
                            data-toggle="select2"
                            onchange="getAssignments()" required>
                        <option value="">Select a class</option>
                        <?php
                        $classes = getSession()->classes()->findAll();

                        foreach ($classes as $class) {
                            echo '<option value="' . $class->id . '">' . $class->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getAssignments()"><i
                            class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="ajaxContent"></div>
        </div>
    </div>
</div>
<script>
    function getSections(classId) {
        //var data = {
        //    url: "<?php //echo site_url('ajax/class/') ?>//" + classId + "/sections",
        //    data: "session=" + classId,
        //    loader: true
        //};
        //ajaxRequest(data, function (data) {
        //    $('#section_id').html(data);
        //});

        var d = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects",
            data: "class=" + classId,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#subject_id').html(data);
        });
    }

    function getAssSections(classId) {
        //var data = {
        //    url: "<?php //echo site_url('ajax/class/') ?>//" + classId + "/sections",
        //    data: "session=" + classId,
        //    loader: true
        //};
        //ajaxRequest(data, function (data) {
        //    $('#ass_section_id').html(data);
        //});

        var d = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects",
            data: "class=" + classId,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#ass_subject_id').html(data);
        });
    }

    function getAssignments() {
        var classes = $('#class_id').val();
        var semester = $('#semester_id').val();
        var e = {
            url: "<?php echo site_url(route_to('admin.academic.assignments.get')); ?>",
            loader: true,
            data: "class="+classes+"&semester="+semester
        }

        ajaxRequest(e, function (data) {
            $('#ajaxContent').html(data);
        })
    }
</script>