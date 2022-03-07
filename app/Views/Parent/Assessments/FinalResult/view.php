<?php



?>
<?php
if(!$semester || !$session) {
    ?>
    <div class="alert alert-warning">
        An error occurred. Please notify your teacher
    </div>
    <?php
} else {
    ?>
    <div class="row" id="final">
        <div class="col-md-4">
            Name: <b><?php echo $student->profile->name; ?></b><br/>
            Admission Number: <b><?php echo $student->admission_number; ?></b><br/>
            Academic Session: <b><?php echo getSession() ? getSession()->name : ''; ?></b><br/>
        </div>
        <div class="col-md-4">
            Semester: <b><?php echo $semester->name; ?></b><br/>
            Class: <b><?php echo $student->class->name; ?></b><br/>
            Section: <b><?php echo $student->section->name; ?></b><br/>
        </div>
        <div class="col-md-4">
            <?php
            $session = getSession() ? getSession()->id : FALSE;
            if($fgRank = $student->getSemesterFinalRank($session, 1)) {
                ?>
                <h1>RANK: <?php echo $fgRank; ?>/<?php echo count($student->section->students); ?></h1>
                <?php
            }
            ?>

        </div>
    </div>
    <hr/>
    <div>
        <?php
        $results = $student->getSemesterFinalGrades($session, $semester->id);
        ?>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Score</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                $total = 0;
                $divisor = 0;
                foreach ($results as $result) {
                    $n++;
                    $total += $result->score;
                    $divisor += 1;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo @(new \App\Models\ClassSubjects())->find($result->subject)->name; ?></td>
                        <td><?php echo $result->score; ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td></td>
                    <th>Total</th>
                    <th><?php echo $total; ?></th>
                </tr>
                <tr>
                    <td></td>
                    <th>Average</th>
                    <th><?php echo ($divisor > 0 && $total > 0) ? number_format($total/$divisor, 2) : 0; ?></th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        const q = document.getElementById('final');
        q.scrollIntoView();
    </script>
    <?php
}
?>

