<?php


?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white mb-0">Class Work Results</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item text-white">Class Work</li>
                            <li class="breadcrumb-item text-white"><?php echo $classwork->name; ?></li>
                            <li class="breadcrumb-item text-white">Results</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <?php
            //d($classwork->class);
            ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Score</th>
                        <th>Out Of</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    $cRes = (new \App\Models\ClassWorkSubmissions())->where('class_work', $classwork->id);
                    foreach ($classwork->class->students as $student) {
                        $n++;
                        $score = $cRes->where('student_id', $student->id)->where('subject', $classwork->subject->id)->get()->getFirstRow();
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $student->profile->name; ?></td>
                            <td><?php echo isset($score->score) ? $score->score.'%' : '-'; ?></td>
                            <td>100</td>
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
