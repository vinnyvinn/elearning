<?php
$class = (new \App\Models\Classes())->find($class);
$exam = (new \App\Models\Exams())->find($exam);
?>
<div class="">

</div>
<div class="table-responsive pt-2">
    <table class="table" id="results_table">
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
            <th>Average</th>
            <th>Total</th>
            <th>Rank</th>
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
                $exams_model = new \App\Models\ExamResults();
                $i = 0;
                $total_marks = 0;
                foreach ($subjects as $subject) {
                    $rs = $exams_model->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $class->id, 'subject' => $subject->id])->get()->getLastRow();
                    ?>
                    <td><?php echo $rs ? $rs->mark : '-'; ?></td>
                    <?php
                    $i++;
                    $total_marks += is_numeric($rs->mark) ? $rs->mark : 0;
                }
                ?>
                <td><?php echo number_format(($exams_model->getScore($student->id, $exam->id, $class->id)/$i), 2); ?></td>
                <td><?php echo number_format($total_marks, 2); ?></td>
                <td>
                    <?php
                    echo $exams_model->getRank($student->id, $exam->id, $class->id).'/'.count($class->students);
                    //d($exams_model->getRank($student->id, $exam->id, $class->id));
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('#results_table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy'
                },
                {
                    extend: 'excel',
                },
                {
                    extend: 'pdf',
                },
                {
                    extend: 'print',
                },
            ],
            "aoColumnDefs": [
                { "sType": "numeric", "aTargets": [ 0, -1, -2 ] }
            ]
        });
    })
</script>