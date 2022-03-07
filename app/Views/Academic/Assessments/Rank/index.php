<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Rank</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
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
                    <select name="class" id="class_id" class="form-control form-control-sm select2"
                            data-toggle="select2"
                            onchange="getSections($(this).val())" required>
                        <option value="">Select a class</option>
                        <?php
                        $sess = getSession();
                        if ($sess) {
                            $classes = $sess->classes()->findAll();
                            if ($classes && count($classes) > 0) {
                                foreach ($classes as $class) {
                                    echo '<option value="' . $class->id . '">' . $class->name . '</option>';
                                }
                            }
                        }

                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-1">
                    <select name="section" id="section_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Select Section</option>

                    </select>
                </div>
                <div class="col-md-3 mb-1">
                    <select name="semester" id="semester_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Select Semester</option>
                        <?php
                        if ($sess) {
                            $sems = $sess->semesters;
                            if ($sems && count($sems) > 0) {
                                foreach ($sems as $sem) {
                                    echo '<option value="' . $sem->id . '">' . $sem->name . '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getCATS()"><i
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
        var data = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
            data: "session=" + classId,
            loader: true
        };
        ajaxRequest(data, function (data) {
            $('#section_id').html(data);
        });

    }

    function getCATS() {
        var classes = $('#class_id').val();
        var semester = $('#semester_id').val();
        var section = $('#section_id').val();
        if(classes == '' || semester == '') {
            toast("Error", "Please select a Class and Semester", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('admin.academic.assessments.get_rank')); ?>",
                loader: true,
                data: "class=" + classes + "&semester=" + semester + "&section=" + section
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>