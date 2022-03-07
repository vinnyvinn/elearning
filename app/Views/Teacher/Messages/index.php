<?php
$sections = (new \App\Models\Sections())->where('advisor',$teacher->id)->findAll();
$students = array();
foreach ($sections as $section){
    foreach ($section->students as $student){
        array_push($students,$student);
    }
}

$parents_arr = array();
$parents = array();
foreach ($students as $student){
    if (isset($student->parent->id) && !isset($parents_arr[$student->parent->id])){
        $parents_arr[$student->parent->id] = true;
        array_push($parents,$student->parent);
    }
}
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Messages </h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('teacher_messages_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills nav-pill-bordered">
            <li class="nav-item">
                <a class="nav-link active" id="base-pill1" data-toggle="pill" href="#pill1" aria-expanded="true" value="0999">Parents </a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" id="base-pill2" data-toggle="pill" href="#pill2" aria-expanded="true" value="0999">Students </a>
                </li>
            </ul>
            <br>
            <div class="tab-content px-1 pt-1">
                    <div role="tabpanel" class="tab-pane active" id="pill1" aria-expanded="true" aria-labelledby="base-pill1">
                        <div class="table-responsive">
                            <table class="table" id="parents_table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Parent Name</th>
                                    <th>Student Name</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $n1 = 0;
                                foreach ($students as $std) {
                                    $n1++;
                                    ?>
                                    <tr>
                                        <td><?php echo $n1; ?></td>
                                        <td><?php echo $std->parent->name ?></td>
                                        <td><?php echo $std->profile->name; ?></td>
                                        <td><?php echo $std->parent->phone; ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.message.parent', $std->parent->id, $std->id)); ?>">Message</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <div role="tabpanel" class="tab-pane" id="pill2" aria-expanded="true" aria-labelledby="base-pill2">
                    <div class="table-responsive">
                        <table class="table" id="students-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Admission Number</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($students as $student) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $student->profile->name ?></td>
                                    <td><?php echo $student->admission_number; ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="<?php echo site_url(route_to('teacher.message.student', $student->id)); ?>">Message</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-none">
                <?php
                $messages = (new \App\Models\Messages())->where('session',active_session())->groupBy('for_student')->groupBy('student')->orderBy('id', 'ASC')->findAll();

                if($messages && is_array($messages) && count($messages) > 0 ){
                    foreach ($messages as $message) {
                        if(isset($message->parent)) {
                            $parent = (new \App\Models\Parents())->find($message->parent);
                            $student = (new \App\Models\Students())->find($message->for_student);
                            if (empty($parent) || empty($student)) {
                                ?>
                                <div class="alert alert-primary">
                                    No New messages
                                </div>
                                <?php
                            } else {
                                ?>
                                <a href="<?php echo site_url(route_to('teacher.message.parent', $parent->id, $student->id)); ?>">
                                    <div class="media media-comment">
                                        <?php
                                        if ($parent->avatar) {
                                            ?>
                                            <img alt="<?php echo $parent->name; ?>"
                                                 class="avatar avatar-lg media-comment-avatar rounded-circle"
                                                 src="<?php echo $parent->avatar; ?>">
                                            <?php
                                        }
                                        ?>
                                        <div class="media-body">
                                            <div class="media-comment-text">
                                                <h6 class="h5 mt-0"><?php echo $parent->name; ?> <small>parent to</small> <?php echo $student->profile->name.' ('.$student->admission_number.')'; ?></h6>
                                                <p class="text-sm lh-160"><?php //echo $message->message; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <?php
                            }
                        } else {
                            //$parent = (new \App\Models\Parents())->find($message->parent);
                            $student = (new \App\Models\Students())->find($message->student);
                            if(empty($student)) {
                                ?>
                                <div class="alert alert-primary">
                                    No New messages
                                </div>
                                <?php
                            } else {
                                ?>
                                <a href="<?php echo site_url(route_to('teacher.message.student', $student->id)); ?>">
                                    <div class="media media-comment">
                                        <?php
                                        if ($student->profile->avatar) {
                                            ?>
                                            <img alt="<?php echo $student->profile->name; ?>"
                                                 class="avatar avatar-lg media-comment-avatar rounded-circle"
                                                 src="<?php echo $student->profile->avatar; ?>">
                                            <?php
                                        }
                                        ?>
                                        <div class="media-body">
                                            <div class="media-comment-text">
                                                <h6 class="h5 mt-0"><?php echo $student->profile->name; ?> <small>of class</small> <?php echo $student->class->name.' ('.$student->admission_number.')'; ?></h6>
                                                <p class="text-sm lh-160"><?php //echo $message->message; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <?php
                            }
                        }
                    }
                } else {
                    ?>
                    <div class="alert alert-primary">
                        No New messages
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#students-table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
            ],
        });

        $('#parents_table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
            ],
        });
    })
</script>