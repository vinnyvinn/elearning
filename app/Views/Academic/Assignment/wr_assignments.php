<div class="card">
    <?php
    $class = (new \App\Models\Classes())->find($class);
    if(!$class) {
        $assignments = FALSE;
    } else {
        $assignments = (new \App\Models\AssignmentItems())
            ->where('class', $class->id)
            ->where('session', active_session())
            ->where('semester', $semester)
            ->findAll();
    }
    if($assignments && count((array)$assignments) > 0) {
        ?>
        <div class="table-responsive">
            <table class="table">
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
                foreach ($assignments as $assignment) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $assignment->question; ?></td>
                        <td><?php echo getSession($assignment->session->id)->name; ?></td>
                        <td><?php echo date('d/m/Y',strtotime($assignment->given)); ?></td>
                        <td><?php echo date("d/m/Y",strtotime($assignment->deadline)); ?></td>
                        <td>
                            <?php
                            if ($assignment->published == '1') {
                                echo '<span class="badge badge-success">PUBLISHED</span>';
                            } else {
                                echo '<span class="badge badge-dark">DRAFT</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.academic.assignments.view', $assignment->id)); ?>">View</a>
                            <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.academic.assignments.writing.submissions', $assignment->id,$class->id)); ?>">Submissions</a>
                            <a class="btn btn-sm btn-warning" href="<?php echo site_url(route_to('admin.academic.assignments.edit', $assignment->id)); ?>"><i class="fa fa-edit"></i> Edit</a>
                            <?php if (isSuperAdmin()):?>
                            <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('admin.academic.assignments.delete', $assignment->id)); ?>" url="<?php echo site_url(route_to('admin.academic.assignments.delete', $assignment->id)); ?>" data="action:delete|id:<?php echo $assignment->id; ?>" loader="true" warning-title="Delete assignment" warning-message="This action will delete everything tied to this assignment; including the questions and results" warning-button="Delete">Delete</a>
                            <?php endif;?>
                        </td>
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