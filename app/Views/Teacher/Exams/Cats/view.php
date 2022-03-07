<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo $exam->name; ?>
                        : <?php echo @$title ? $title : 'Results'; ?></h6><br/>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a
                                        href="<?php echo site_url(route_to('teacher.exams.cats.index')); ?>">Assessment
                                    Tests</a></li>
                            <li class="breadcrumb-item"><a
                                        href="<?php echo site_url(route_to('teacher.exams.cats.view', $exam->id)); ?>"><?php echo $exam->name; ?></a>
                            </li>
                            <li class="breadcrumb-item active"
                                aria-current="page"><?php echo @$title ? $title : 'Time Tables'; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .card .ct-example .nav .nav-item .nav-link.active {
        /*background-color: #5e72e4 !important;*/
        /*color: white !important;*/
    }
</style>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <?php
        if($exam->section) {
            $students = $exam->section->students;
        } else {
            $students = $exam->class->students;
        }
        ?>
        <div class="card-body">
            <div class="row justify-content-center">
                <?php
                if(!$exam->section) {
                    ?>
                    <div class="col-md-3">
                        <select class="form-control form-control-sm select2" id="section" data-toggle="select2">
                            <option value=""> -- Please Select a section -- </option>
                            <?php
                            $sections = $exam->class->sections;
                            if($sections && count($sections) > 0) {
                                foreach ($sections as $section) {
                                    ?>
                                    <option value="<?php echo $section->id; ?>"><?php echo $section->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                }
                ?>
                <div class="col-md-3">
                    <select class="form-control form-control-sm select2" id="subject" data-toggle="select2">
                        <option value=""> -- Please Select a subject -- </option>
                        <?php
                        $subjects = $exam->class->subjects;
                        if($subjects && count($subjects) > 0) {
                            foreach ($subjects as $subject) {
                                ?>
                                <option value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-default btn-block" onclick="getStudents()"><i class="fa fa-filter"></i> Filter</button>
                </div>
            </div>
        </div>
        <div id="ajaxContent"></div>
    </div>
</div>

<script>
    var getStudents = function () {
        <?php if(!$exam->section) {
            ?>
             var section = false;
            <?php
        } else {
            ?>
            var section = "<?php echo $exam->section->id; ?>";
            <?php
        } ?>
        var classId = "<?php echo $exam->class->id; ?>";
        var sectionId = section ? section : $('#section').val();
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