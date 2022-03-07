<?php
if(isset($section) && $section) {
    $students = $section->students;
} else {
    $students = $class->students;
}
if(isset($subject) && $subject) {
    if($students && count($students) > 0) {

        $db = \Config\Database::connect();
        $builder = $db->table('sections');
        $builder->select("users.surname,users.first_name,users.last_name,students.admission_number,students.id as stdID,classes.id as classID");
        $builder->join('students','students.section = sections.id');
        $builder->join('users','users.id = students.user_id');
        $builder->join('classes','classes.id = students.class');
        $builder->where('students.section',$section->id);
        $builder->where('students.session',active_session());
        $builder->orderBy('users.surname');
        $students = $builder->get()->getResult();
        ?>
        <div class="card-body">
            <h4>Results for <?php echo $subject->name; ?>, <?php echo $exam->name;?></h4>
        </div>
        <form method="post" class="ajaxForm" loader="true" action="<?php echo site_url(route_to('teacher.exam.record.results.form', $exam->id)); ?>">
            <input type="hidden" name="exam" value="<?php echo $exam->id; ?>" />
            <input type="hidden" name="subject" value="<?php echo $subject->id; ?>" />
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Adm. No.</th>
                        <th>Marks</th>
                        <th>Remark</th>
                        <th>Not Seated For Exam ?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($students as $student) {
                        $resObj = $exam->resultObject->where('subject', $subject->id)->where('student', $student->stdID)->get()->getRowObject();
                        $n++;
                        ?>
                        <input type="hidden" name="class[<?php echo $student->stdID; ?>]" value="<?php echo $student->classID; ?>" />
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $student->surname.' '. $student->first_name.' '.$student->last_name; ?></td>
                            <td><?php echo $student->admission_number; ?></td>
                            <td><input type="number" min="0" max="100" step="0.01" name="mark[<?php echo $student->stdID; ?>]" value="<?php echo old('mark.'.$student->stdID, $resObj ? $resObj->mark : ''); ?>" required /></td>
                            <td><input type="text" name="remark[<?php echo $student->stdID ?>]" value="<?php echo old('remark.'.$student->stdID, $resObj ? $resObj->remark : ''); ?>" /></td>
                            <td>
                                <input type="checkbox" name="not_seated_for_exam[<?php echo $student->stdID?>]" value="1" <?php if (isset($resObj->not_seated_for_exam) && ($resObj->not_seated_for_exam==1)):?> checked<?php endif;?>>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Save Results</button>
            </div>
        </form>
        <?php
    } else {
        ?>
        <div class="card-body">
            <div class="alert alert-danger">
                No students found for this class section
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="card-body">
        <div class="alert alert-danger">
            Subject was not found
        </div>
    </div>
    <?php
}