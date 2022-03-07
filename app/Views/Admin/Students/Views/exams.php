<div class="card">
    <?php
    if($student->exams && count($student->exams) > 0) {
        ?>
        <div class="table-responsive pt-2">
            <table class="table" id="">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Exam</th>
                        <th>Semester</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($student->exams as $exam) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $exam->name; ?></td>
                        <td><?php echo $exam->semester ? $exam->semester->name : '-'; ?></td>
                        <td><?php echo $exam->class ? $exam->class->name : '-'; ?></td>
                        <td><?php echo $exam->section ? $exam->section->name : '-'; ?></td>
                        <td><?php echo $exam->starting_date; ?></td>
                        <td><?php echo $exam->ending_date; ?></td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.students.view.results', $student->id, $exam->id)); ?>">View Results</a>
                            <a class="btn btn-sm btn-info" href="<?php echo site_url(route_to('admin.exams.view.index', $exam->id)); ?>">View Exam</a>
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
            <div class="alert alert-warning">
                No exams found for this student
            </div>
        </div>
        <?php
    }
    ?>
</div>