<?php



?>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Adm. #</th>
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Average Score</th>
                <th>Rank</th>
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
                <td><?php echo $student->admission_number; ?></td>
                <td><?php echo $student->profile->name; ?></td>
                <td><?php echo $student->class->name; ?></td>
                <td><?php echo $student->section->name; ?></td>
                <td><?php echo $student->assScore; ?></td>
                <td><?php echo $student->assRank.'/'.@count($student->class->getSingleSection($student->section->id)->students); ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>