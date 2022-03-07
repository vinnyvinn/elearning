<?php



?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Assessment Results</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php use App\Models\Assignments;

                    do_action('student_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header mb-0">
            <form method="GET">
                <div class="row justify-content-center">
                    <div class="col-sm-4">
                        <select class="form-control form-control-sm" name="semester">
                            <?php
                            $sessions = getSession();
                            if(!empty($sessions) ) {
                                $semesters = $sessions->semesters;
                                if (!empty($semesters) && is_array($semesters) && count($semesters) > 0) {
                                    foreach ($semesters as $semester) {
                                        ?>
                                        <option value="<?php echo $semester->id ?>"><?php echo $semester->name; ?></option>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control form-control-sm" name="subject">
                            <?php
                                $subjects = $student->class->subjects;
                                if (!empty($subjects) && is_array($subjects) && count($subjects) > 0) {
                                    foreach ($subjects as $subject) {
                                        ?>
                                        <option value="<?php echo $subject->id ?>"><?php echo $subject->name; ?></option>
                                        <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary btn-block btn-sm">Filter</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <?php
            if($available) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Score</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = 0;
                        $n = 0;
                        if($keys && is_array($keys)) {
                            foreach ($keys as $key=>$val) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <th><?php echo $val; ?></th>
                                    <td><?php echo (is_array($marks) && isset($marks[$key])) ? @number_format($marks[$key], 2) : '-'; ?></td>
                                </tr>
                                <?php
                                $total += (is_array($marks) && isset($marks[$key])) ? $marks[$key] : 0;
                            }
                        }
                        ?>
                        <tr>
                            <th></th>
                            <th>Total</th>
                            <th><?php echo number_format($total, 2); ?></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-warning">
                    Assessment results have not been added for this subject
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>