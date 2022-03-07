<?php
//d($classwork);
//d($quizes);
//d($exams);

?>
<div>
    <form method="post" class="ajaxForm" loader="true" data-parsely-validate action="<?php echo site_url(route_to('admin.academic.assessments.calculate_fg.calculate')) ?>">
        <input type="hidden" name="class" value="<?php echo $class->id; ?>">
        <input type="hidden" name="subject" value="<?php echo $subject; ?>">
        <input type="hidden" name="semester" value="<?php echo $semester; ?>">
        <div class="row">
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
            <div class="col-md-6">
                <?php
                $calculated_assessments = (new \App\Models\AssessmentResults())->where('class', $class->id)
                    ->where('subject', $subject)->where('semester', $semester)->groupBy('name')->findAll();
                if ($calculated_assessments && count($calculated_assessments) > 0) {
                    ?>
                    <h4>Assessments</h4>
                    <?php
                    $n = 0;
                    foreach ($calculated_assessments as $calculatedAssessment) {
                        $n++;
                        ?>
                        <div class="form-group">
                            <label><input type="checkbox" name="assessments[]" value="<?php echo $calculatedAssessment->name; ?>" /> Calculated Assessment (<?php echo $calculatedAssessment->created_at->format('d-m-Y'); ?>) </label>
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
