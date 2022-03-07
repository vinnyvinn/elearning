<?php
//$students = (new \App\Models\Sections())->find($section)->students;
//$class = (new \App\Models\Classes())->find($class);
//$section = (new \App\Models\Sections())->find($section);
//$students = $section->students;

?>
<style>
    /*.table thead {*/
    /*    height: 110px;*/
    /*}*/
    /*.rot th {*/
    /*    transform: rotate(290deg);*/
    /*}*/
    /*.table tbody tr td {*/
    /*    min-width: 100px;*/
    /*}*/
</style>
<div class="card-body">
    <?php
    //d($_POST);
    $student = (new \App\Models\Students())->find($student);
    if($student) {
        $student_results = (new \App\Models\Assessments())->where(['student_id' =>$student->id,'subject'=>$subject,'month'=>$month,'week'=>$week,'session'=>active_session()])->get()->getLastRow('\App\Entities\Assessment');
        //dd($student_results);
        ?>
        <div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Category</th>
                            <th>Marks Awarded</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Worksheet</th>
                        <td><?php echo @$student_results->worksheet; ?></td>
                    </tr>
                    <tr>
                        <th>Classwork-1</th>
                        <td><?php echo @$student_results->classwork_1; ?></td>
                    </tr>
                    <tr>
                        <th>Classwork-2</th>
                        <td><?php echo @$student_results->classwork_2; ?></td>
                    </tr>
                    <tr>
                        <th>Homework-1</th>
                        <td><?php echo @$student_results->homework_1; ?></td>
                    </tr>
                    <tr>
                        <th>Homework-2</th>
                        <td><?php echo @$student_results->homework_2; ?></td>
                    </tr>
                    <tr>
                        <th>Quiz-1</th>
                        <td><?php echo @$student_results->quiz_1; ?></td>
                    </tr>
                    <tr>
                        <th>Ex. Book</th>
                        <td><?php echo @$student_results->ex_book; ?></td>
                    </tr>
                    <tr>
                        <th>Bonus</th>
                        <td><?php echo @$student_results->bonus; ?></td>
                    </tr>
                    <tr>
                        <th>Conduct</th>
                        <td><?php echo @$student_results->conduct; ?></td>
                    </tr>
                    <tr>
                        <th>Assignment</th>
                        <td><?php echo @$student_results->assignment; ?></td>
                    </tr>
                    <tr>
                        <th>Quiz of 10</th>
                        <td><?php echo @$student_results->quiz_of_10; ?></td>
                    </tr>
                    <tr>
                        <th>Total Score</th>
                        <td><?php echo @$student_results->total; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-warning">
            This student does not exist
        </div>
        <?php
    }
    ?>
</div>