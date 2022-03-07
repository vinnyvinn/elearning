<div class="card">
    <?php
    $assignments = $student->assignments;
    if($assignments && count($assignments) > 0) {
        ?>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Books To Cover</th>
                    <th>Deadline</th>
                    <th>File</th>
                    <th>Submitted</th>
                    <th>Score</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($assignments as $assignment) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $assignment->description; ?></td>
                        <td><?php echo $assignment->books; ?></td>
                        <td><?php echo $assignment->deadline; ?></td>
                        <td><a href="<?php echo $assignment->file; ?>">Download File</a></td>
                        <td>
                            <?php
                            $isSubmitted = $assignment->isSubmitted($student->id);
                            if($isSubmitted) {
                                ?>
                                <span class="badge badge-success">Submitted</span>
                                <?php
                            } else {
                                ?>
                                <span class="badge badge-danger">Not Submitted</span>
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $isSubmitted ? $isSubmitted->marks_awarded : '-';
                            ?>
                        </td>
                        <td><a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.subjects.assignments.view', $assignment->subject->id, $assignment->id)); ?>">View</a></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        ?>
        <div class="card-body">
            <div class="alert alert-danger">
                No assignments found for this class
            </div>
        </div>
        <?php
    }
    ?>
</div>