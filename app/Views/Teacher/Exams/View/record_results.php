<?php
$classes = (new \App\Models\Subjectteachers())->groupBy('section_id')->where('teacher_id',$teacher->id)->orderBy('id')->findAll();
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
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $exam->name; ?> : Record Results</h6><br/>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('teacher.exams.index')); ?>">Exams</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('teacher.exams.view.index', $exam->id)); ?>"><?php echo $exam->name; ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Record Results</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('exam_record_results_quick_action_buttons', $exam); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control" id="section" onchange="getSubjects()">
                            <option value=""> -- Select class --</option>
                                <?php
                                if($sections && count($sections) > 0) {
                                    foreach ($sections as $section) {
                                        ?>
                                        <option value="<?php echo $section->id; ?>"><?php echo $section->class->name.','.$section->name; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="subject">
                        <option>--Select Subject--</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="button" class="btn btn-primary btn-block" onclick="getStudents()"><i class="fa fa-filter"></i> Filter</button>
                </div>
            </div>
        </div>
        <div id="ajaxContent">
            <h5 class="text-center text-danger mt-3 mb-3">Please use the filter above to fetch data</h5>
        </div>
    </div>
</div>

<script>
    var getSubjects = function () {
        var sectionId = $('#section').val();
        var teacherId = "<?php echo $teacher->id?>";
        if (sectionId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/section/') ?>" + sectionId + "/subjects-section/"+teacherId,
                data: "section=" + sectionId+"&teacher="+teacherId,
                loader: true
            };
            ajaxRequest(data, function (data) {
             $('#subject').html(data);
            });
        }
    };

    var getStudents = function () {
        var classId = $('#class').val();
        var sectionId = $('#section').val();
        var subjectId = $('#subject').val();
        if (classId == '' || sectionId == '') {
            toast('Error', 'Please make sure all filter fields are selected', 'error');
        } else {
            var resultsURL = "<?php echo site_url('teachers/exams/'.$exam->id.'/get-students'); ?>";
            var data = {
                url: resultsURL,
                data: "class=" + classId + "&section=" + sectionId + "&subject=" + subjectId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ajaxContent').html(data);
            });
        }
    }
</script>