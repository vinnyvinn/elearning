<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo ucwords($student->profile->name); ?></h6><br/>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.students.view', $student->id)); ?>"><?php echo ucwords($student->profile->name); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo site_url(route_to('admin.students.view.exams', $student->id)); ?>">Exams</a></li>
                            <li class="breadcrumb-item"><a href="#!"><?php echo $exam->name; ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Results</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.students.edit', $student->id)); ?>"
                       class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
                    <?php do_action('admin_student_exam_results_action_buttons', $student, $exam); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div>Name: <b><?php echo $student->profile->name; ?></b></div>
                    <div>Admission Number: <b><?php echo $student->admission_number; ?></b></div>
                    <div>Exam: <b><?php echo $exam->name; ?></b></div>
                </div>
                <div class="col-md-6">
                    <div>Academic Session: <b><?php echo getSession()->name; ?></b></div>
                    <div>Starting Date: <b><?php echo $exam->starting_date; ?></b></div>
                    <div>Ending Date: <b><?php echo $exam->ending_date; ?></b></div>
                </div>
            </div>
            <br/>
            <div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Pass Mark</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($student->class->subjects as $subject) {
                            $rs = (new \App\Models\ExamResults())->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $student->class->id, 'subject' => $subject->id])->get()->getRowObject();
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $subject->name; ?></td>
                                <td><?php echo $subject->pass_mark; ?></td>
                                <td><?php echo $rs ? $rs->mark : '-'; ?></td>
                                <td>
                                    <?php
                                    if($rs) {
                                        if($rs->mark >= $subject->pass_mark) {
                                            ?>
                                            <span class="badge badge-success">Pass</span>
                                            <?php
                                        } else {
                                            ?>
                                            <span class="badge badge-danger">Fail</span>
                                            <?php
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $rs ? $rs->remark : '-'; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
