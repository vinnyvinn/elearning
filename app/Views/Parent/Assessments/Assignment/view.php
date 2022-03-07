<?php


?>
<?php
if(isset($assignments) && count($assignments) > 0) {
    ?>
    <div class="table-responsive" id="assessment">
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Assignment </th>
                    <th>Subject</th>
                    <th>Deadline</th>
                    <th>Score</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            foreach ($assignments as $assignment) {
                $n++;
                $submission = $assignment->getSubmission($student->id);
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo $assignment->description; ?></td>
                    <td><?php echo $assignment->subject->name; ?></td>
                    <td><?php echo $assignment->deadline; ?></td>
                    <td><?php
                        if($submission) {
                            echo $submission->marks_awarded;
                        } else {
                            echo '<span class="badge badge-danger">NOT SUBMITTED</span>';
                        }
                        ?></td>
                    <td><?php
                        if($submission) {
                            echo $submission->remarks;
                        } else {
                            echo '-';
                        }
                        ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <script>
        const a = document.getElementById('assessment');
        a.scrollIntoView();
    </script>

    <?php
} else {
    ?>
    <div class="alert alert-danger">
        No assignments have been posted for this student
    </div>
    <?php
}
?>

