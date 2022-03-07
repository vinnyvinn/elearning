<?php

if ($teachers && count($teachers) > 0) {


    $db = \Config\Database::connect();
    $builder = $db->table('teachers');
   $builder->select("users.surname,users.first_name,users.last_name,teachers.teacher_number,teachers.id as trID");
   $builder->join('users','users.id = teachers.user_id');
   $builder->orderBy('users.surname');
   $builder->where("teachers.session",active_session());
    $teachers = $builder->get()->getResult();
    ?>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th><span>Present?</span></th>
                    <th><span>Absent?</span></th>
                    <th><span>Permission?</span></th>
                    <th><span>Sick?</span></th>
                    <th><span>Late?</span></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                $attendance = new \App\Models\Attendance();
                foreach ($teachers as $teacher) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $teacher->surname.' '.$teacher->first_name.' '.$teacher->last_name; ?></td>
                        <td>
                            <?php
                            $present = $attendance->where('timestamp', strtotime($date))->where('teacher', $teacher->trID)->get()->getRowObject();
                            if($present) {
                                ?>
                                <input type="hidden" name="presence[<?php echo $teacher->trID; ?>]" value="<?php echo $present->id; ?>" />
                                <?php
                                $present = $present->status;
                            }
                            //d($present);
                            ?>
                            <label><input type="radio" name="attendance[<?php echo $teacher->trID; ?>]" value="1" <?php echo (!isset($present) || $present == 1) ? 'checked' : ''; ?> /></label>
                        </td>
                        <td>
                            <label><input type="radio" name="attendance[<?php echo $teacher->trID; ?>]" value="0" <?php echo (isset($present) && $present == 0) ? 'checked' : ''; ?> /></label>
                        </td>
                        <td>
                            <label><input type="radio" name="attendance[<?php echo $teacher->trID; ?>]" value="2" <?php echo (isset($present) && $present == 2) ? 'checked' : ''; ?> /></label>
                        </td>
                        <td>
                            <label><input type="radio" name="attendance[<?php echo $teacher->trID; ?>]" value="3" <?php echo (isset($present) && $present == 3) ? 'checked' : ''; ?> /></label>
                        </td>
                        <td>
                         <label><input type="radio" name="attendance[<?php echo $teacher->trID; ?>]" value="4" <?php echo (isset($present) && $present == 4) ? 'checked' : ''; ?> /></label>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success"> Save Attendance</button>
        </div>
        <script>
            $("#checkAllTeachers").on('change', function (e) {
                //$("#studentCheck").each(function () { this.checked = !this.checked; })
                //$("#studentCheck").prop('checked', $(this).prop("checked"));
                //$("input:checkbox").prop('checked', $(this).prop("checked"));
                $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
            });
        </script>
    <?php
} else {
    ?>
    <div class="card-body">
        <div class="alert alert-danger">
            No teachers found
        </div>
    </div>
    <?php
}
