<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Class Work</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a class="btn btn-sm btn-success" href="<?php echo site_url(route_to('teacher.academic.assessments.class_work.new_classwork')); ?>"><i class="fa fa-plus"></i> New Class Work</a>
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
                    <select class="form-control form-control-sm select2" id="semester_id" required>
                        <option value="">Select semester</option>
                        <?php
                        $semesters = @getSession()->semesters;
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
                <div class="col-md-3 mb-1">
                    <select name="class" id="class_id" class="form-control form-control-sm select2"
                            data-toggle="select2"
                            onchange="getSubjects($(this).val())" required>
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
                    <select id="subject_id" class="form-control form-control-sm select2">

                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getClassWork()"><i
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
    var getSubjects = function (classId) {
        //var classId = $('#class').val();
        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            //getSections();
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects",
                data: "class=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#subject_id').html(data);
            });
        }
    };

    function getSections(classId) {
        var data = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
            data: "session=" + classId,
            loader: true
        };
        // ajaxRequest(data, function (data) {
        //     $('#section_id').html(data);
        // });
    }
    function getSelectSections(classId) {
        var data = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
            data: "session=" + classId,
            loader: true
        };
        // ajaxRequest(data, function (data) {
        //     $('#select_section_id').html(data);
        // });
    }

    function getClassWork() {
        var section = $('#class_id').val();
        var semester = $('#semester_id').val();
        var subject = $('#subject_id').val();
        if(section == '' || semester == '' || subject == '') {
            toast("Error", "Please select all fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('teacher.academic.assessments.class_work.get')); ?>",
                loader: true,
                data: "class=" + section + "&semester="+semester+"&subject="+subject
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>