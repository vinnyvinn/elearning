<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Messages</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('student_messages_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="">
                <?php
                $messages = (new \App\Models\Messages())->where('session',active_session())->where('student', $student->id)->groupBy('student')->orderBy('id', 'ASC')->findAll();
                if($messages && is_array($messages) && count($messages) > 0 ){
                    foreach ($messages as $message) {
                        $teacher = (new \App\Models\Teachers())->find($message->teacher);
                        //$student = (new \App\Models\Students())->find($message->for_student);
                        ?>
                        <a href="<?php echo site_url(route_to('student.messages.chat', (new \App\Models\Subjectteachers())->where('teacher_id', $message->teacher)->get()->getLastRow()->id)); ?>">
                            <div class="media media-comment">
                                <?php
                                if ($teacher->profile->avatar) {
                                    ?>
                                    <img alt="<?php echo $teacher->profile->name; ?>"
                                         class="avatar avatar-lg media-comment-avatar rounded-circle"
                                         src="<?php echo $teacher->profile->avatar; ?>">
                                    <?php
                                }
                                ?>
                                <div class="media-body">
                                    <div class="media-comment-text">
                                        <h6 class="h5 mt-0"><?php echo $teacher->profile->name; ?> </h6>
                                        <p class="text-sm lh-160"><?php //echo $message->message; ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-primary">
                        No New messages
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>