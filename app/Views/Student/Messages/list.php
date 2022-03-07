<?php
use App\Models\Messages;

$messages = (new Messages())->where('session',active_session())->where(['student' => $student->id, 'teacher' => $teacher->id])->orderBy('id', 'ASC')->limit(100)->findAll();
if ($messages && count($messages) > 0) {

    foreach ($messages as $message) {
        if($message->direction == 'r') {
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