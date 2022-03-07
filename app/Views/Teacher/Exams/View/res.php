<?php
$class = (new \App\Models\Classes())->find($class);
$exam = (new \App\Models\Exams())->find($exam);
?>
<div class="">

</div>
<div class="table-responsive pt-2">
    <table class="table datatable" id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Section</th>
                <?php
                $subjects = $class->subjects();
                foreach ($subjects as $subject) {
                    ?>
                    <th><?php echo $subject->name; ?></th>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
        <?php
        $n = 0;
        foreach ($class->students() as $student) {
            $n++;
            ?>
            <tr>
                <td><?php echo $n; ?></td>
                <td><?php echo $student->profile->name; ?></td>
                <td><?php echo $student->section->name; ?></td>
                <?php
                $i = 0;
                foreach ($subjects as $subject) {
                    $rs = (new \App\Models\ExamResults())->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $class->id, 'subject' => $subject->id])->get()->getLastRow();
                    ?>
                    <td><?php echo $rs ? $rs->mark : '-'; ?></td>
                    <?php
                    $i++;
                }
                ?>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>