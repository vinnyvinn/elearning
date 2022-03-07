<?php
use App\Models\Messages;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Messages</h6>
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
    <div class="card card-translucent">
        <div class="card-header">

        </div>
        <div class="card-body">
            <div class="card">
                <div class="card-header">
                    <h5 class="h3 mb-0">Messages Feed</h5>
                </div>
                <div class="card-body">
                    <div class="mb-1">
                        <div style="max-height: 500px; overflow-y: scroll" id="messageArea" class="p-2 pb-3">
                            <?php
                            $messages = (new Messages())->where('session',active_session())->where(['teacher' => $teacher->id, 'student' => $student->id])->orderBy('id', 'ASC')->limit(100)->findAll();
                            if ($messages && count($messages) > 0) {

                                foreach ($messages as $message) {
                                    if($message->direction == 'r'){
                                        ?>
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
                                                    <h6 class="h5 mt-0"><?php echo $teacher->profile->name; ?></h6>
                                                    <p class="text-sm lh-160"><?php echo $message->message; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="media media-comment">
                                            <?php
                                            if ($student->profile->avatar) {
                                                ?>
                                                <img alt="<?php echo $student->profile->name; ?>"
                                                     class="avatar avatar-lg media-comment-avatar rounded-circle"
                                                     src="<?php echo $student->profile->avatar; ?>">
                                                <?php
                                            }
                                            ?>
                                            <div class="media-body">
                                                <div class="media-comment-text">
                                                    <h6 class="h5 mt-0"><?php echo $student->profile->name; ?></h6>
                                                    <p class="text-sm lh-160"><?php echo $message->message; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }

                            } else {
                                ?>
                                <div class="alert alert-default">
                                    No messages
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <hr>
                        <div class="media align-items-center">
                            <?php
                            if ($teacher->profile->avatar) {
                                ?>
                                <img alt="<?php echo $teacher->profile->name; ?>" class="avatar avatar-lg rounded-circle mr-4"
                                     src="<?php echo $teacher->profile->avatar; ?>">
                                <?php
                            }
                            ?>
                            <div class="media-body">
                                <form method="post" class="ajaxForm"
                                      action="<?php echo site_url(route_to('teacher.message.send_student')); ?>" loader="true">
                                    <input type="hidden" name="teacher" value="<?php echo $teacher->id; ?>"/>
                                    <input type="hidden" name="student" value="<?php echo $student->id; ?>"/>
                                    <div class="form-group">
                                        <textarea class="form-control" placeholder="Write your message" rows="3"
                                                  name="message"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success"><i class="ni ni-send"></i> Send
                                        Message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#messageArea').animate({ scrollTop: $('#messageArea').prop("scrollHeight")}, 1000);
    });
    function reloadMessages() {
        var d = {
            loader: false,
            data: "teacher=<?php echo $teacher->id; ?>&student=<?php echo $student->id; ?>",
            url: "<?php echo site_url(route_to('teacher.message.ajax_fetch_student')); ?>"
        }
        ajaxRequest(d, function (data) {
            $('#messageArea').html(data);
            $('textarea').val('');
            $('#messageArea').animate({ scrollTop: $('#messageArea').prop("scrollHeight")}, 1000);
        })
    }

    setInterval(function () {
        var d = {
            loader: false,
            data: "teacher=<?php echo $teacher->id; ?>&student=<?php echo $student->id; ?>",
            url: "<?php echo site_url(route_to('teacher.message.ajax_fetch_student')); ?>"
        };
        ajaxRequest(d, function (data) {
            $('#messageArea').html(data);
            //$('textarea').val('');
            $('#messageArea').animate({scrollTop: $('#messageArea').prop("scrollHeight")}, 1000);
        })
    }, 5000);
</script>
