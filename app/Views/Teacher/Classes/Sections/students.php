<div class="card">
    <?php
    $students = $section->students;
    if ($students && count($students) > 0) {
        ?>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Adm #</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Admission Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($students as $student) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td class="table-user">
                            <img src="<?php echo $student->profile->avatar; ?>" class="avatar rounded-circle mr-3">
                            <a href="<?php echo site_url(route_to('teacher.students.view', $student->id)); ?>">
                                <?php echo $student->profile->first_name.' '.$student->profile->last_name; ?>
                            </a>
                        </td>
                        <td><?php echo $student->admission_number; ?></td>
                        <td><?php echo $student->class->name; ?></td>
                        <td><?php echo $student->section->name; ?></td>
                        <td><?php echo $student->created_at->format('d/m/Y'); ?></td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="<?php echo site_url(route_to('teacher.students.view', $student->id)); ?>">View Profile</a>
                                    <?php do_action('student_action_links'); ?>
                                </div>
                            </div>
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
                No students found
            </div>
        </div>
        <?php
    }
    ?>
</div>