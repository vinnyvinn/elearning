<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Writing Assignments</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.academic.assignments.create'))?>" class="btn btn-sm btn-neutral">New Assignment</a>
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
            url: "<?php echo site_url(route_to('admin.academic.assignments.written.get')); ?>",
            loader: true,
            data: "class="+classes+"&semester="+semester
        }

        ajaxRequest(e, function (data) {
            $('#ajaxContent').html(data);
        })
    }
</script>