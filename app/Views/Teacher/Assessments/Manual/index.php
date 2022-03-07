<?php
$classes = (new \App\Models\Subjectteachers())->groupBy('class_id')->where('teacher_id',$teacher->id)->orderBy('id')->findAll();
$sections = array();
foreach ($classes as $class){
    array_push($sections,$class->section);
}
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Continuous Assessment</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header pb-0 mb--1">
            <div class="row justify-content-center">
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
                            onchange="getSections($(this).val())" required>
                        <option value="">--Class--</option>
                        <?php

                            if($classes && is_array($classes) && count($classes) > 0) {
                                foreach ($classes as $class) {
                                    echo '<option value="' . $class->class_id . '">' . (new \App\Models\Classes())->find($class->class_id)->name . '</option>';
                                }
                            }

                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-1">
                    <select name="section" id="subject_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Subject</option>

                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-secondary btn-block" onclick="getTheAssessments()">Filter</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="ajaxContent">
                Please use the filter above
            </div>
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
         var teacherId = "<?php echo $teacher->id;?>"
        var d = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects/"+teacherId,
            data: "class=" + classId,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#subject_id').html(data);
        });
    }

    function getTheAssessments() {
        var classes = $('#class_id').val();
        var subject = $('#subject_id').val();
        var semester = $('#semester_id').val();
        if(classes == '' || subject == '' || semester == '') {
            toast("Error", "Please select all fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('teacher.academic.assessments.manual.get_the_assessments')); ?>",
                loader: true,
                data: "class=" + classes + "&semester=" + semester + "&subject="+subject
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>