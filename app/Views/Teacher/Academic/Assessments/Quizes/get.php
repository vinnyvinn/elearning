<div>
    <?php
    //dd($quizes);
    ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Session</th>
                    <th>Given Date</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            foreach ($quizes as $quiz) {
                $n++;
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo $quiz->name; ?></td>
                    <td><?php echo $quiz->session->name; ?></td>
                    <td><?php echo $quiz->given->format('d/m/Y'); ?></td>
                    <td><?php echo $quiz->deadline->format('d/m/Y'); ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.academic.assessments.quizes.view', $quiz->id)); ?>">View</a>
                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.academic.assessments.quizes.results', $quiz->id)); ?>">Results</a>
                        <a class="btn btn-sm btn-warning" href="<?php echo site_url(route_to('teacher.academic.assessments.quizes.edit_quiz', $quiz->id)) ?>">Edit</a>
                <?php if (isSuperAdmin()):?>
                        <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('teacher.academic.assessments.quizes.delete', $quiz->id)); ?>" url="<?php echo site_url(route_to('teacher.academic.assessments.quizes.delete', $quiz->id)); ?>" data="action:delete|id:<?php echo $quiz->id; ?>" loader="true" warning-title="Delete quiz" warning-message="This action will delete everything tied to this quiz; including the questions and results" warning-button="Delete">Delete</a>
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