<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php use App\Models\Assignments;

                    do_action('student_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header mb-0">
            <h3 class="h3">My Exams</h3>
        </div>
        <?php
        if($student->exams && count($student->exams) > 0) {
            ?>
            <div class="table-responsive pb-2">
                <table class="table datatable" id="datatable-buttons">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Exam</th>
                        <th>Semester</th>
                        <th>Class</th>
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
                            <td><?php echo $exam->starting_date; ?></td>
                            <td><?php echo $exam->ending_date; ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('student.exam.view.results', $exam->id)); ?>">View Results</a>
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
                No exam records found
            </div>
            <?php
        }
        ?>
    </div>
</div>
