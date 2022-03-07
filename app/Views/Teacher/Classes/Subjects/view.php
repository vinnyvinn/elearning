<div class="row">
    <div class="col-md-4">
        <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Pass Mark</h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo $subject->pass_mark.'%'; ?></span>
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
                            if($tea) {
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
                        <span class="h2 font-weight-bold mb-0"><?php echo (new \App\Models\Sessions())->find(active_session())->name; ?></span>
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
            <a class="btn btn-sm btn-success" href="<?php echo site_url(route_to('admin.subjects.lesson_plan.create', $section->id, $subject->id)); ?>">Update Lesson Plan</a>
        </span>
    </div>
    <div class="card-body">
        <?php
        echo view('Classes/Subjects/lesson_plan/view');
        ?>
    </div>
</div>