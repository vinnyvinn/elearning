<?php
$sections = (new \App\Models\Subjectteachers())->groupBy('section_id')->orderBy('id')->where('teacher_id',$teacher->id)->findAll();
$active = $sections[0];
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Lesson Plan</h6>
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
                <div class="col-md-2 mb-1">
                    <select name="section" id="section_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option>-- Select Class --</option>
                          <?php foreach ($sections as $section):?>
                        <option value="<?php echo $section->section_id?>"><?php echo (new \App\Models\Classes())->find($section->class_id)->name.','.(new \App\Models\Sections())->find($section->section_id)->name?></option>
                        <?php endforeach;?>

                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="section" id="subject_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option>-- Select Subject --</option>

                    </select>
                </div>
                <div class="col-md-2">
                    <select name="month" id="month_id" class="form-control form-control-sm select2"
                            data-toggle="select2" required>
                        <option value="">Select Month</option>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"<?php if ($i==1):?>selected<?php endif;?>><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="week" id="week_id" class="form-control form-control-sm select2" data-toggle="select2"
                            required>
                        <option value="">Select Week</option>
                        <?php
                        for ($i = 1; $i <= 4; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>" <?php if ($i==1):?>selected<?php endif;?>><?php echo $i; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-sm btn-primary" onclick="getLessonPlan()"><i
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
    $('#section_id').on('change',function (){
     getSections($(this).val());
    })

    function getSections(section) {
        var teacherId = "<?php echo $teacher->id?>";
        var d = {
            url:"<?php echo site_url('ajax/section/') ?>" + section + "/subjects-section/"+teacherId,
            data: "section=" + section,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#subject_id').html(data);
        });
   }

    function getLessonPlan() {
        var section = $('#section_id').val();
        var subject = $('#subject_id').val();
        var month = $('#month_id').val();
        var week = $('#week_id').val();
        if(section == '' || subject == '' || month == '' || week == '') {
            toast("Error", "Please select all fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('teacher.lesson_plan.get_plan')); ?>",
                loader: true,
                data: "section=" + section + "&subject=" + subject + "&month=" + month + "&week=" + week
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>