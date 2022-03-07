<?php
//d($classwork);
//d($quizes);
//d($exams);

?>
<div>
    <form method="post" class="ajaxForm" loader="true" data-parsely-validate action="<?php echo site_url(route_to('teacher.academic.assessments.calculate_fg.calculate')) ?>">
        <input type="hidden" name="class" value="<?php echo $class->id; ?>">
        <input type="hidden" name="subject" value="<?php echo $subject; ?>">
        <input type="hidden" name="semester" value="<?php echo $semester; ?>">
        <div class="row">
            <div class="col-md-6">
                <?php
                if($assessments && count($assessments) > 0) {
                    ?>
                    <h4>Assessments</h4>
                    <?php
                    foreach ($assessments as $item) {
                        ?>
                        <div class="form-group">
                            <label><input type="checkbox" name="assessments[]" value="<?php echo $item->id; ?>" /> <?php echo $item->name; ?></label>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="col-md-6">
                <?php
                if($exams && count($exams) > 0) {
                    ?>
                    <h4>Exams</h4>
                    <?php
                    foreach ($exams as $exam) {
                        ?>
                        <div class="form-group">
                            <label><input type="checkbox" name="exams[]" value="<?php echo $exam->id; ?>" /> <?php echo $exam->name; ?></label>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <button type="submit" class="btn btn-block btn-primary">Calculate Final Grade</button>
    </form>
</div>
<script>

</script>
