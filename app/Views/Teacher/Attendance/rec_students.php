<?php

$students = $section->students;
if ($students && count($students) > 0) {
    ?>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Adm. No.</th>
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
                foreach ($students as $student) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $student->profile->name; ?></td>
                        <td><?php echo $student->admission_number; ?></td>
                        <td>
                            <?php
                            $present = $attendance->where('timestamp', strtotime($date))->where('student', $student->id)->get()->getRowObject();
                            if($present) {
                                ?>
                                <input type="hidden" name="presence[<?php echo $student->id; ?>]" value="<?php echo $present->id; ?>" />
                                <?php

                                $present = $present->status;
                            }
                            ?>
                            <label><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="1" <?php echo (!isset($present) || $present == 1) ? 'checked' : ''; ?> /></label>
                        </td>
                        <td>
                            <label><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="0"  <?php echo (isset($present) && $present == 0) ? 'checked' : ''; ?> /></label>
                        </td>
                        <td>
                            <label><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="2"  <?php echo (isset($present) && $present == 2) ? 'checked' : ''; ?> /></label>
                        </td>
                        <td>
                            <label><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="3"  <?php echo (isset($present) && $present == 3) ? 'checked' : ''; ?> /></label>
                        </td>
                        <td>
                            <label><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="4"  <?php echo (isset($present) && $present == 4) ? 'checked' : ''; ?> /></label>
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
            $("#checkAllStudents").on('change', function (e) {
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
            No students found for this class
        </div>
    </div>
    <?php
}
