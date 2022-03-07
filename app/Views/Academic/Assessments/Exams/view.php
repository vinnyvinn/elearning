<?php
$options = (new \App\Models\AnswerOption())->findAll();
//$classid = $exam->class->id;
//$sectionid = $exam->section->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white mb-0">View Quiz</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item text-white">Quiz</li>
                            <li class="breadcrumb-item text-white"><?php echo $exam->name; ?></li>
                            <li class="breadcrumb-item text-white">View</li>
                        </ol>
                    </nav>
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
            <a  href="<?php echo site_url(route_to('admin.academic.assessments.exams.edit', $exam->id)) ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
            <a target="_blank" href="<?php echo site_url(route_to('admin.academic.assessments.exams.print_exam', $exam->id)) ?>" class="btn btn-info btn-sm"><i class="fa fa-print"></i> Print</a>

            <?php
            if  ($exam->published == '0') {
                //publish
                ?>
                <a href="<?php echo site_url(route_to('admin.academic.assessments.exams.publish', $exam->id)) ?>" class="btn btn-sm btn-success">PUBLISH</a>
                <?php
            } else {
                //mark draft
                ?>
                <a href="<?php echo site_url(route_to('admin.academic.assessments.exams.draft', $exam->id)) ?>" class="btn btn-sm btn-dark">MARK DRAFT</a>
                <?php
            }
            ?>
        </div>
        <div class="card-body">
            <div >
                <div class="row">
                    <div class="col-md-6">
                        <span>Marks out of <b><?php echo $exam->out_of; ?></b></span>
                    </div>
                    <div class="col-md-6">
                        <span>Duration: <b><?php echo @$exam->duration ? $exam->duration.' minutes' : '-UNDEFINED-'; ?></b></span>
                    </div>
                </div>
                <hr/>
                <div>
                    <?php
                    if($exam->items) {
                        $n = 0;
                        foreach ($exam->items as $item) {
                            $n++;
                            ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <span><b>Q<?php echo $item->question_number; ?>: </b><?php echo $item->question; ?></span>
                                    <div class="ml-5">
                                        <?php
                                        $naswers_ = array_flatten(json_decode($item->answers));
                                        $correct = array_flatten(json_decode($item->corrects))[0];

                                        foreach ($options as $option){
                                            foreach ($naswers_ as $key=>$answer) {
                                                $opt_name = $option['name'];
                                                if (isset($answer->$opt_name)){
                                                    ?>
                                                    <label>
                                                        <input type="radio" <?php echo isset($answer->$opt_name) && $correct == $opt_name ? 'checked' : ''; ?> disabled /> <?php echo $answer->$opt_name; ?>
                                                    </label><br/>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <img src="<?php echo @$item->image; ?>" class="img-responsive" style="max-height: 200px; width: auto; max-width: 300px;"/>

                                </div>
                            </div>
                            <div class="alert alert-neutral border-info">
                                <?php
                                echo @$item->explanation;
                                ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
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
        var d = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects",
            data: "class=" + classId,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#subject_id').html(data);
        });
    }

    function getCreateClassWork() {
        var classid = $('#class_id').val();
        var subject = $('#subject_id').val();
        if (classid == '' || subject == '') {
            toast("Error", "Please select all fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('admin.academic.assessments.exams.view_exam', $exam->id)); ?>",
                loader: true,
                data: "class=" + classid + "&subject=" + subject
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>
