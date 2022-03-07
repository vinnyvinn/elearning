<?php
//d($classwork);
//d($quizes);
//d($exams);

?>
<div>
    <form method="post" class="ajaxForm" loader="true" data-parsely-validate action="<?php echo site_url(route_to('teacher.academic.assessments.calculate_cats')) ?>">
        <input type="hidden" name="class" value="<?php echo $class->id; ?>">
        <input type="hidden" name="subject" value="<?php echo $subject; ?>">
        <input type="hidden" name="semester" value="<?php echo $semester->id; ?>">
        <div class="row">
            <div class="col-md-3">
                <?php
                if($classwork && count($classwork) > 0) {
                    ?>
                    <h4>Classwork</h4>
                    <?php
                    foreach ($classwork as $item) {
                        //d($item);
                        //$out_of = @(new \App\Models\ClassWorkItems())->where('class_work', $item->id)->get()->getLastRow()->out_of;
                        if(true) {
                            ?>
                            <div class="form-group">
                                <label><input type="checkbox" name="classwork[]" value="<?php echo $item->id; ?>" /> <?php echo $item->name.' ('.$item->out_of.')'; ?></label>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="col-md-3">
                <?php
                if($quizes && count($quizes) > 0) {
                    ?>
                    <h4>Quizes</h4>
                    <?php
                    foreach ($quizes as $quiz) {
                        //$out_of = @(new \App\Models\QuizItems())->where('quiz', $quiz->id)->get()->getLastRow()->out_of;
                        if(true) {
                            ?>
                            <div class="form-group">
                                <label><input type="checkbox" name="quizes[]" value="<?php echo $quiz->id; ?>" /> <?php echo $quiz->name.' ('.$quiz->out_of.')'; ?></label>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="col-md-3">
                <?php
                if($exams && count($exams) > 0) {
                    ?>
                    <h4>Exams</h4>
                    <?php
                    foreach ($exams as $exam) {
                        //$out_of = @(new \App\Models\CatExamItems())->where('cat_exam', $exam->id)->get()->getLastRow()->out_of;
                        if(true) {
                            ?>
                            <div class="form-group">
                                <label><input type="checkbox" name="exams[]" value="<?php echo $exam->id; ?>" /> <?php echo $exam->name.' ('.$exam->out_of.')'; ?></label>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="col-md-3">
                <?php
                if($assignments && count($assignments) > 0) {
                    ?>
                    <h4>Assignments</h4>
                    <?php
                    foreach ($assignments as $assignment) {
                        ?>
                        <div class="form-group">
                            <label><input type="checkbox" name="assignment[]" value="<?php echo $assignment->id; ?>" /> <?php echo $assignment->description; ?></label>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Calculate Assessments</button>
    </form>
</div>
<script>

</script>
