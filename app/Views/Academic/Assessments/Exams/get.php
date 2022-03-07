<div>
    <?php
    //d($exams);
    ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Session</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            foreach ($exams as $exam) {
                $n++;
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo $exam->name; ?></td>
                    <td><?php echo $exam->session->name; ?></td>
                    <td><?php echo $exam->given->format('d/m/Y'); ?></td>
                    <td><?php echo $exam->deadline->format('d/m/Y'); ?></td>
                    <td>
                        <?php
                        if ($exam->published == '1') {
                            echo '<span class="badge badge-success">PUBLISHED</span>';
                        } else {
                            echo '<span class="badge badge-dark">DRAFT</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.academic.assessments.exams.view', $exam->id)); ?>">View</a>
                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.academic.assessments.exams.results', $exam->id)); ?>">Results</a>
                        <a class="btn btn-sm btn-warning" href="<?php echo site_url(route_to('admin.academic.assessments.exams.edit', $exam->id)) ?>"><i class="fa fa-edit"></i> Edit</a>
                <?php if (isSuperAdmin()):?>
                        <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('admin.academic.assessments.exams.delete', $exam->id)); ?>" url="<?php echo site_url(route_to('admin.academic.assessments.exams.delete', $exam->id)); ?>" data="action:delete|id:<?php echo $exam->id; ?>" loader="true" warning-title="Delete exam" warning-message="This action will delete everything tied to this exam; including the questions and results" warning-button="Delete">Delete</a>
                    <?php endif;?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>