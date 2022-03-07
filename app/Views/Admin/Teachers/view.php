<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo ucwords($teacher->profile->name); ?></h6>
                    <?php if (isSuperAdmin()):?>
                    <span class="ml-5" style="color: #fff">
                              Password: <?php echo $teacher->profile->usermeta('password', FALSE) ? $teacher->profile->usermeta('password', FALSE) : 'N/A' ?>
                        </span>
                    <?php endif;?>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.teachers.edit', $teacher->id)); ?>"
                       class="btn btn-sm btn-warning"><i class="fa fa-user-edit"></i> Edit</a>
                    <?php if (isSuperAdmin()):?>
                    <a href="<?php echo site_url(route_to('admin.teachers.delete', $teacher->id)); ?>"
                       data="action:delete|id:<?php echo $user->id ?>"
                       warning-title="Delete teacher" warning-message="Are you sure you want to completely remove this teacher"
                       url="<?php echo site_url(route_to('admin.teachers.delete', $teacher->id)); ?>"
                       warning-button="Yes, Delete!" loader="true"
                       class="btn btn-sm btn-danger send-to-server-click"><i class="fa fa-user-minus"></i> Delete </a>

                    <?php
                    endif;?>
                    <?php do_action('teacher_profile_quick_action_buttons', $teacher); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
<!--    <div class="card mb-3 mt--3 bg-white">-->
<!--        <div class="ct-example card-header-pills" style="padding-bottom: 0; margin-bottom: 0">-->
<!--            <ul class="nav nav-tabs-code nav-justified">-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?php //echo @$page == 'profile' ? 'active' : ''; ?><!--" href="--><?php //echo site_url(route_to('admin.teachers.view', $teacher->id)); ?><!--">Profile</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?php //echo @$page == 'exams' ? 'active' : ''; ?><!--" href="--><?php //echo site_url(route_to('admin.teachers.view.exams', $teacher->id)); ?><!--">Exams</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?php //echo @$page == 'assignments' ? 'active' : ''; ?><!--" href="--><?php //echo site_url(route_to('admin.teachers.view.assignments', $teacher->id)); ?><!--">Assignments</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?php //echo @$page == 'fees' ? 'active' : ''; ?><!--" href="--><?php //echo site_url(route_to('admin.teachers.view.fees', $teacher->id)); ?><!--">Student Fees</a>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </div>-->
<!--    --><?php
//    //echo @$html;
//    ?>
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-xl-4 order-xl-2">
                    <div class="card card-profile">
                        <img src="<?php echo base_url('assets/img/theme/img-1-1000x600.jpg'); ?>" alt="Image placeholder" class="card-img-top">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                        <img src="<?php echo $teacher->profile->avatar; ?>" style="max-height: 150px" class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <div class="d-flex justify-content-between">

                                <button class="btn btn-sm btn-default float-right" data-toggle="modal" data-target=".send_sms" >Message</button>
                            </div>
                        </div>
                        <div class="modal fade send_sms" role="dialog" aria-labelledby="modal-default"
                             style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                <div class="modal-content">
                                    <form class="ajaxForm" loader="true" method="post"
                                          action="<?php echo site_url(route_to('admin.teachers.send_sms', $teacher->id)); ?>">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="modal-title-default">Send SMS</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Message</label><br/>
                                                <textarea class="form-control" name="sms" rows="4" required ></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Send SMS</button>
                                            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="text-center">
                                <h5 class="h3">
                                    <?php echo $teacher->profile->name; ?>
                                </h5>
                                <div class="h5 font-weight-300">
                                    <i class="ni location_pin mr-2"></i><?php echo $teacher->profile->usermeta('woreda') ?>, <?php echo $teacher->profile->usermeta('subcity') ?>, <?php echo $teacher->profile->usermeta('house_number') ?>
                                </div>
                                <div>
                                    <i class="fa fa-phone-alt"></i>  <?php echo $teacher->profile->phone; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php

                    if(isset($teacher->contact) && $teacher->contact != '') {
                        ?>
                        <div class="card">
                            <div class="card-header">
                                <h3>Emergency Contact</h3>
                            </div>
                            <div class="card-body">
                                <div class="">
                                    <div>
                                        <i class="fa fa-user"></i> <b>Name</b>  <?php echo $teacher->contact->name; ?>
                                    </div>
                                    <div>
                                        <i class="fa fa-phone-alt"></i> <b>Mobile:</b>  <?php echo $teacher->contact->phone_mobile; ?>
                                    </div>
                                    <div>
                                        <i class="fa fa-phone-alt"></i> <b>Work:</b>  <?php echo $teacher->contact->phone_work; ?>
                                    </div>
                                    <div>
                                        <i class="fa fa-info-circle"></i> <b>Sub City:</b>  <?php echo $teacher->contact->subcity; ?>
                                    </div>
                                    <div>
                                        <i class="fa fa-info-circle"></i> <b>Woreda:</b>  <?php echo $teacher->contact->woreda; ?>
                                    </div>
                                    <div>
                                        <i class="fa fa-info-circle"></i> <b>House Number:</b>  <?php echo $teacher->contact->house_number; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }?>

                </div>
                <div class="col-xl-8 order-xl-1">
                    <div class="card">
                        <div class="card-header ">
                            <h4 class="mb-0">Teacher's Subjects
                            <span class="pull-right float-right">
                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target=".assign_subject">Assign Subject</button>
                            </span>
                            </h4>
                        </div>
<!--                        <!- TODO: Activate the modal by changing the assign_subject  -->
                        <div class="modal fade assign_subject" tabindex="-1" role="dialog" aria-labelledby="modal-default">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form class="ajaxForm" loader="true" method="post" data-parsley-validate="" action="<?php echo site_url(route_to('admin.teachers.assign_subject', $teacher->id)); ?>" novalidate="">
                                        <input type="hidden" name="teacher_id" value="<?php echo $teacher->id; ?>">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="modal-title-default">Assign Subject</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Select Class</label><br/>
                                                <select class="form-control form-control-sm select2" name="class_id" onchange="getSections($(this).val())" required="">
                                                    <option value=""> -- Please Select -- </option>
                                                    <?php
                                                    $session = getSession();
                                                    if($session) {
                                                        $classes = $session->classes->findAll();
                                                        if($classes && is_array($classes)) {
                                                            foreach ($classes as $class) {
                                                                ?>
                                                                <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Select Class Section</label><br/>
                                                <select name="section_id" id="section_id" class="form-control form-control-sm select2"
                                                        data-toggle="select2" required>
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Select Class Subject</label><br/>
                                                <select name="subject_id" id="subject_id" class="form-control form-control-sm select2"
                                                        data-toggle="select2" required>
                                                    <option value="">Select Subject</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Save</button>
                                            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <?php
                                $subjects = $teacher->subjects;
                                if($subjects && count($subjects)) {
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table" id="datatable-basic">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Class</th>
                                                    <th>Section</th>
                                                    <th>Subject</th>
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
                                                    <td><a href="<?php echo site_url(route_to('admin.classes.view', $subject->class->id)); ?>"><?php echo $subject->class->name; ?></a></td>
                                                    <td><a href="<?php echo site_url(route_to('admin.class.sections.view', $subject->section->id)); ?>"><?php echo $subject->section->name; ?></a></td>
                                                    <td><a href="<?php echo site_url(route_to('admin.subjects.view', $subject->id, $subject->section->id)); ?>"><?php echo $subject->subject->name; ?></a></td>
                                                    <td><a class="btn btn-sm btn-warning" href="<?php echo site_url(route_to('admin.subjects.unassign', $subject->id)); ?>">Unassign Subject</a></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="alert alert-warning">
                                        No subjects assigned to this teacher.
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php

                       if (isset($teacher->files) && is_array($teacher->files) && count($teacher->files) > 0){
                            ?>
                            <div class="card-header">
                                <h3>Files</h3>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>File</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                            <?php foreach ($teacher->files as $file):?>
                                    <tr>
                                        <td><?php echo $file->description;?></td>
                                        <td><a href="#" onclick="downloadFile('<?php echo $file->file?>')"><i class="fa fa-download"></i> Download</a></td>
                                    </tr>
                             <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getSections(classId) {
        var data = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
            data: "session=" + classId,
            loader: true
        };
        ajaxRequest(data, function (data) {
            $('#section_id').html(data);
        });

        var d = {
            url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects",
            data: "class=" + classId,
            loader: true
        };
        ajaxRequest(d, function (data) {
            $('#subject_id').html(data);
        });

    }

    function downloadFile(file){
        window.location.href = file;
    }
</script>
