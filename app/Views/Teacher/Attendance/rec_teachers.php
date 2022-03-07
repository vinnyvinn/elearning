<?php

if ($teachers && count($teachers) > 0) {
    ?>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th><span><input type="checkbox" id="checkAllTeachers" /> Present?</span></th>
                    <th></th>
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
                        <td><?php echo $teacher->profile->name; ?></td>
                        <td>
                            <?php
                            $present = $attendance->where('timestamp', strtotime($date))->where('teacher', $teacher->id)->get()->getRowObject();
                            if($present) {
                                ?>
                                <input type="hidden" name="presence[<?php echo $teacher->id; ?>]" value="<?php echo $present->id; ?>" />
                                <?php
                                $present = $present->status;
                            } else {
                                $present = 0;
                            }
                            ?>
                            <label><input type="checkbox" name="attendance[<?php echo $teacher->id; ?>]" value="1" <?php echo @$present == 1 ? 'checked' : ''; ?> /></label>
                        </td>
                        <td></td>
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
