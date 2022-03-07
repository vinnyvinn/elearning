<?php


?>
<?php
if(isset($classwork) && count($classwork) > 0) {
    //d($classwork);
    ?>
    <div class="table-responsive" id="classwork">
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Classwork</th>
                    <th>Given Date</th>
                    <th>Deadline</th>
                    <th>Submitted On</th>
                    <th>Score</th>
                    <th>Out Of</th>
<!--                    <th>Actions</th>-->
                </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            foreach ($classwork as $item) {
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
                            echo date('d/m/Y H:i A', $submission->submitted_on);
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
<!--                        <a class="btn btn-sm btn-primary" href="--><?php //echo site_url(route_to('parent.continuous_assessments.classwork_results', $item->id, $student->id)); ?><!--">View</a>-->
<!--                    </td>-->
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <script>
        const a = document.getElementById('classwork');
        a.scrollIntoView();
    </script>

    <?php
} else {
    ?>
    <div class="alert alert-warning">
        No classwork for this student
    </div>
    <?php
}
