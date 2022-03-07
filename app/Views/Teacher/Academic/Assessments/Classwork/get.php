<div>
    <?php
    //dd($classworks);
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
                    <th>Out Of</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $n = 0;
            foreach ($classworks as $classwork) {
                $n++;
                ?>
                <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo $classwork->name; ?></td>
                    <td><?php echo $classwork->session->name; ?></td>
                    <td><?php echo $classwork->given->format('d/m/Y'); ?></td>
                    <td><?php echo $classwork->deadline->format('d/m/Y'); ?></td>
                    <td><?php echo $classwork->out_of; ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.academic.assessments.class_work.view', $classwork->id)); ?>">View</a>
                        <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.academic.assessments.class_work.results', $classwork->id)); ?>">Results</a>
                        <a class="btn btn-sm btn-warning" href="<?php echo site_url(route_to('teacher.academic.assessments.class_work.edit', $classwork->id)) ?>">Edit</a>
                <?php if (isSuperAdmin()):?>
                        <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('teacher.academic.assessments.class_work.delete', $classwork->id)); ?>" url="<?php echo site_url(route_to('teacher.academic.assessments.class_work.delete', $classwork->id)); ?>" data="action:delete|id:<?php echo $classwork->id; ?>" loader="true" warning-title="Delete Classwork" warning-message="This action will delete everything tied to this classwork; including the questions and results" warning-button="Delete">Delete</a>
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