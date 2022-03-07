<?php
//$students = (new \App\Models\Sections())->find($section)->students;
$class = (new \App\Models\Classes())->find($class);
$section = (new \App\Models\Sections())->find($section);
$students = $section->students;

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
    if($students && count($students) > 0) {
        ?>
        <form method="POST" class="ajaxForm d-block" action="<?php echo site_url(route_to('admin.classes.assessments.save')); ?>">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr class="rot">
                        <th>Students' Name</th>
                        <th>Worksheet</th>
                        <th>Classwork-1</th>
                        <th>Classwork-2</th>
                        <th>Homework-1</th>
                        <th>Homework-2</th>
                        <th>Quiz-1</th>
                        <th>Ex. Book</th>
                        <th>Bonus</th>
                        <th>Conduct</th>
                        <th>Assignment</th>
                        <th>Quiz of 10</th>
                        <th>Total Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $session_id = active_session();
                    foreach($students as $student) {
                        //$student = $this->db->get_where('students', array('id' => $enroll['student_id']))->row_array();
                        //echo $this->user_model->get_user_details($student->id, 'name');
                        //$name = $this->user_model->get_user_details($student->id, 'name');
                        $student_results = (new \App\Models\Assessments())->where(['student_id' =>$student->id, 'class'=>$class->id,'section'=>$section->id,'subject'=>$subject,'month'=>$month,'week'=>$week,'session'=>active_session()])->get()->getLastRow('\App\Entities\Assessment');
                        //dd($student_results);
                        if($student_results) {
                            ?>
                            <input type="hidden" name="ass[<?php echo $student->id; ?>][id]" value="<?php echo $student_results->id; ?>" />
                            <?php
                        }
                        ?>
                        <input type="hidden" name="ass[<?php echo $student->id; ?>][session]" value="<?php echo active_session(); ?>" />
                        <input type="hidden" name="ass[<?php echo $student->id; ?>][student_id]" value="<?php echo $student->id; ?>" />
                        <input type="hidden" name="ass[<?php echo $student->id; ?>][class]" value="<?php echo $class->id; ?>" />
                        <input type="hidden" name="ass[<?php echo $student->id; ?>][section]" value="<?php echo $section->id; ?>" />
                        <input type="hidden" name="ass[<?php echo $student->id; ?>][subject]" value="<?php echo $subject; ?>" />
                        <input type="hidden" name="ass[<?php echo $student->id; ?>][month]" value="<?php echo $month; ?>" />
                        <input type="hidden" name="ass[<?php echo $student->id; ?>][week]" value="<?php echo $week; ?>" />
                        <tr title="<?php echo $student->profile->name.'('.$student->admission_number.')'; ?>">
                            <td><?php echo $student->profile->name; ?><br/>
                                <small><?php echo $student->admission_number; ?> </small>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->worksheet; ?>" name="ass[<?php echo $student->id; ?>][worksheet]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->classwork_1; ?>" name="ass[<?php echo $student->id; ?>][classwork_1]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->classwork_2; ?>" name="ass[<?php echo $student->id; ?>][classwork_2]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->homework_1; ?>" name="ass[<?php echo $student->id; ?>][homework_1]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->homework_2; ?>" name="ass[<?php echo $student->id; ?>][homework_2]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->quiz_1; ?>" name="ass[<?php echo $student->id; ?>][quiz_1]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->ex_book; ?>" name="ass[<?php echo $student->id; ?>][ex_book]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->bonus; ?>" name="ass[<?php echo $student->id; ?>][bonus]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->conduct; ?>" name="ass[<?php echo $student->id; ?>][conduct]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->assignment; ?>" name="ass[<?php echo $student->id; ?>][assignment]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->quiz_of_10; ?>" name="ass[<?php echo $student->id; ?>][quiz_of_10]" /></td>
                            <td><input type="text" class="form-control form-control-sm" value="<?php echo @$student_results->total; ?>" name="ass[<?php echo $student->id; ?>][total]" /></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <br/>
            <button type="submt" class="btn btn-success"><i class="fa fa-send"></i> Update Assessment</button>
        </form>
        <?php
    } else {
        ?>
        <div class="alert alert-warning">
            No student found for this section
        </div>
        <?php
    }
    ?>
</div>
<script>

</script>