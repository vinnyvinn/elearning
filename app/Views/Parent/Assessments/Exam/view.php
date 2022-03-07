<?php


?>
<?php
if(isset($exam) && count($exam) > 0) {
    //d($exam);
    ?>
    <div class="table-responsive" id="exam_">
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Exam</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Submitted On</th>
                    <th>Score</th>
                    <th>Out Of</th>
<!--                    <th>Actions</th>-->
                </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            foreach ($exam as $item) {
                $n++;
                $submission = $item->getSubmission($student->id);
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo $item->name; ?></td>
                    <td><?php echo $item->given->format('d/m/Y'); ?></td>
                    <td><?php echo $item->deadline->format('d/m/Y'); ?></td>
                    <td>
                        <?php
                        if($submission) {
                            //echo date('d/m/Y', $submission->submitted_on);
                            ?> <span class="badge badge-success">Submitted</span> <?php
                        } else {
                            ?> <span class="badge badge-danger">Not Submitted</span> <?php
                        }
                        ?>
                    </td>
                    <td><?php
                        if($submission) {
                            echo $submission->score;
                        } else {
                            echo '-';
                        }
                        ?></td>
                    <td><?php echo $item->out_of; ?></td>
<!--                    <td>-->
<!--                        <a class="btn btn-sm btn-primary" href="--><?php //echo site_url(route_to('parent.continuous_assessments.exam_results', $item->id, $student->id)); ?><!--">View</a>-->
<!--                    </td>-->
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <script>
        const q = document.getElementById('exam_');
        q.scrollIntoView();
    </script>
    <?php
} else {
    ?>
    <div class="alert alert-warning">
        No exam has been posted for this student
    </div>
    <?php
}?>


