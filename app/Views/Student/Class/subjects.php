<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Subjects</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('student_subjects_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <?php
        //dd($student->section);
        $subjects = $student->class->subjects();
        if($subjects && count($subjects) > 0) {
            ?>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>Name</th>
                        <th>Teacher</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($subjects as $subject) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $subject->name; ?></td>
                        <?php
                        $teacher = $subject->getTeacher($student->section->id);
                        ?>
                        <td><?php echo $teacher ? $teacher->profile->name : ''; ?></td>
                        <td>
<!--                            <a href="#!--><?php //echo site_url(route_to('student.subjects.lesson_plan', $subject->id, $student->section->id)); ?><!--" class="btn btn-sm btn-primary">Lesson Plan</a>-->
                            <a href="#!<?php echo site_url(route_to('student.subjects.notes', $subject->id, $student->section->id)); ?>" class="btn btn-sm btn-info">Notes</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <div class="card-body">
                <div class="alert alert-danger">
                    No subjects found for this class
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>