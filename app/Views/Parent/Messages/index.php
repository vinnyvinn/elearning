<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Messages </h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('student_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="">
        <div class="">
            <div class="row">
                <?php
                foreach ($parent->studentsCurrent as $student) {
                    $teacher = isset($student->section->advisor) ? $student->section->advisor : '';
                    if ($teacher){
                        ?>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <a href="#" class="avatar rounded-circle">
                                                <img alt="Image placeholder" src="<?php echo $teacher->profile->avatar; ?>">
                                            </a>
                                        </div>
                                        <div class="col ml--2">
                                            <h4 class="mb-0">
                                                <a href="#!"><?php echo $teacher->profile->name; ?></a>
                                            </h4>
                                            <small>Advisor to <?php echo $student->profile->name; ?></small><br/>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="pull-right">
                                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('parent.message.teacher', $teacher->id, $student->id)); ?>">Message</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>