<div class="row">
    <div class="col-md-4">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Pass Mark</h5>
                        <span class="h2 font-weight-bold mb-0"><?php use App\Models\Sessions;

                            echo $subject->pass_mark . '%'; ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-danger text-white rounded-circle shadow">
                            <i class="ni ni-chart-bar-32"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Teacher</h5>
                        <span class="h2 font-weight-bold mb-0">
                            <?php
                            $tea = $subject->getTeacher($section->id);
                            if ($tea) {
                                echo $tea->getProfile()->name;
                                //dd($tea);
                            } else {
                                echo '-';
                            }
                            ?>
                        </span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                            <i class="fa fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Academic Session</h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo (new Sessions())->find(active_session())->name; ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-indigo text-white rounded-circle shadow">
                            <i class="fa fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="mb--1 d-inline-block">Lesson Plan</h3>
        <span class="pull-right float-right">
            <a class="btn btn-sm btn-success"
               href="<?php echo site_url(route_to('admin.subjects.lesson_plan.create', $section->id, $subject->id)); ?>">Create Lesson Plan</a>
        </span>
    </div>
    <div class="card-header">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <select name="month" id="month_id" class="form-control form-control-sm select2"
                        data-toggle="select2" required>
                    <option value="">Select Month</option>
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>"><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
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
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-sm btn-default btn-block" onclick="getLessonPlan()"><i
                            class="fa fa-filter"></i> Filter
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="ajaxContent"></div>
        <?php
        //echo view('Classes/Subjects/lesson_plan/view');
        ?>
    </div>
</div>
<script>
    function getLessonPlan() {
        var section = "<?php echo $section->id; ?>";
        var subject = "<?php echo $subject->id; ?>";
        var month = $('#month_id').val();
        var week = $('#week_id').val();
        if(section == '' || subject == '' || month == '' || week == '') {
            toast("Error", "Please select all fields", 'error');
        } else {
            var e = {
                url: "<?php echo site_url(route_to('admin.academic.get_lesson_plan')); ?>",
                loader: true,
                data: "section=" + section + "&subject=" + subject + "&month=" + month + "&week=" + week
            };

            ajaxRequest(e, function (data) {
                $('#ajaxContent').html(data);
            })
        }
    }
</script>