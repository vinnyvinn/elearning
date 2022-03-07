<?php



?>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Results</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <?php
            //d($quiz);
            ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    Name: <b><?php echo $student->profile->name; ?></b><br/>
                    Name: <b><?php echo $student->admission_number; ?></b><br/>
                    Class Work: <b><?php echo $quiz->name; ?></b><br/>
                </div>
                <div class="col-md-6">
                    Academic Session: <b><?php echo getSession() ? getSession()->name : ''; ?></b><br/>
                    Date Given: <b><?php echo $quiz->given->format('d/m/Y'); ?></b><br/>
                    Deadline: <b><?php echo $quiz->deadline->format('d/m/Y'); ?></b><br/>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Out Of</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($quiz->items as $item) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $item->subject->name; ?></td>
                            <td><?php echo $item->out_of; ?></td>
                            <td><?php echo $item->getSubmission($student->id) ? $item->getSubmission($student->id)->score : '-'; ; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>