<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Bennito254">
    <title> <?php echo isset($site_title) ? $site_title.' | ' : '' ?><?php echo get_parent_option('system', 'site_title', 'Scholar School Information Management System'); ?></title>
    <!--  Social tags      -->
    <meta name="keywords"
          content="scholar">
    <meta name="description" content="Scholar">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap">
    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/nucleo/css/nucleo.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert2/dist/sweetalert2.min.css'); ?>"
          type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/toastr/toastr.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css">

    <!-- Datatables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/fullcalendar/dist/fullcalendar.min.css'); ?>" type="text/css">


    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/dist/css/select2.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css">

    <?php do_action('student_head'); ?>
    <script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js'); ?>"></script>
    <style>
        .colmd2{
            width: 30% !important;
        }
        .mb10{
            margin-bottom: -10% !important;
        }
        .parent_web{
            display: block;
        }
        .parent_mobile{
            display: none;
        }
        @media only screen and (max-width: 600px) {
            .parent_mobile {
                display: block !important;
            }
            .parent_web{
                display: none !important;
            }
        }
        .white-color{
            color: #fff;
        }
        .red {
            background: red;
        }
        .yellow{
            background: yellow;
        }
        .blue{
            background: blue;
        }
    </style>
</head>

<body>
<!-- Sidenav -->
<!--<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">-->
<!--<nav class="navbar navbar-light bg-white" id="">-->
<!--    <div class="scrollbar-inner">-->
<!--        <div class="">-->
<!--            <a class="navbar-brand" href="--><?php //echo site_url(route_to('admin.index')); ?><!--">-->
<!--                --><?php //$file = get_parent_option('system', 'site_logo', FALSE); ?>
<!--                <img src="--><?php //echo $file ? base_url('uploads/'.$file) : base_url('assets/img/brand/blue.png'); ?><!--" class="navbar-brand-img" alt="...">-->
<!--            </a>-->
<!--            <div class=" ml-auto ">-->
<!--                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">-->
<!--                    <div class="sidenav-toggler-inner">-->
<!--                        <i class="sidenav-toggler-line"></i>-->
<!--                        <i class="sidenav-toggler-line"></i>-->
<!--                        <i class="sidenav-toggler-line"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="navbar-inner">-->
<!--            <div class="collapse navbar-collapse" id="sidenav-collapse-main">-->
<!--                <ul class="navbar-nav">-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('students/profile', 'active'); ?><!--" href="--><?php //echo site_url(route_to('student.index')); ?><!--">-->
<!--                           <i class="fa fa-user text-primary"></i>-->
<!--                            <span class="nav-link-text">DASHBOARD</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('students/requirements', 'active'); ?><!--" href="--><?php //echo site_url(route_to('student.requirements')); ?><!--">-->
<!--                           <i class="fa fa-list text-primary"></i>-->
<!--                            <span class="nav-link-text">REQUIREMENTS</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    --><?php //do_action('student_links_after_dashboard'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('students/class', 'active') ?><!--" href="#navbar-users" data-toggle="collapse" role="button"-->
<!--                           aria-expanded="--><?php //echo active_link('students/class', 'true', 'false') ?><!--" aria-controls="navbar-users">-->
<!--                            <i class="fa fa-users text-primary"></i>-->
<!--                            <span class="nav-link-text"> CLASS </span>-->
<!--                        </a>-->
<!--                        <div class="collapse --><?php //echo active_link('students/class', 'show') ?><!--" id="navbar-users">-->
<!--                            <ul class="nav nav-sm flex-column">-->
<!--                                <li class="nav-item">-->
<!--                                    <a href="--><?php //echo site_url(route_to('students.class.timetable')); ?><!--" class="nav-link">-->
<!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-list-alt"></i> </span>-->
<!--                                        <span class="sidenav-normal"> Class Schedule </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a href="--><?php //echo site_url(route_to('students.class.notes')); ?><!--" class="nav-link">-->
<!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-sticky-note"></i> </span>-->
<!--                                        <span class="sidenav-normal"> E-Library </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a href="--><?php //echo site_url(route_to('students.class.subjects')); ?><!--" class="nav-link">-->
<!--                                        <span class="sidenav-mini-icon"> S </span>-->
<!--                                        <span class="sidenav-normal"> Subjects </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a href="--><?php //echo site_url(route_to('students.class.asp')); ?><!--" class="nav-link">-->
<!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-list-alt"></i> </span>-->
<!--                                        <span class="sidenav-normal"> After School Program </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                --><?php //do_action('admin_links_users'); ?>
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    --><?php //do_action('student_links_after_class'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('students/home-school', 'active') ?><!--" href="#navbar-secondary-assessments" data-toggle="collapse" role="button"-->
<!--                           aria-expanded="--><?php //echo active_link('students/home-school', 'true', 'false') ?><!--" aria-controls="navbar-secondary-assessments">-->
<!--                            <i class="fa fa-chart-line text-primary"></i>-->
<!--                            <span class="nav-link-text"> HOME SCHOOL </span>-->
<!--                        </a>-->
<!--                        <div class="collapse --><?php //echo active_link('students/home-school', 'show') ?><!--" id="navbar-secondary-assessments">-->
<!--                            <ul class="nav nav-sm flex-column">-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" href="--><?php //echo site_url(route_to('student.assignments')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-list-alt"></i> </span>-->
<!--                                        <span class="sidenav-normal"> Assignments </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a href="--><?php //echo site_url(route_to('student.assessments.classwork.index')); ?><!--" class="nav-link">-->
<!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-list-alt"></i> </span>-->
<!--                                        <span class="sidenav-normal"> Class Work </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a href="--><?php //echo site_url(route_to('student.assessments.quizes.index')); ?><!--" class="nav-link">-->
<!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-list-alt"></i> </span>-->
<!--                                        <span class="sidenav-normal"> Quiz </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a href="--><?php //echo site_url(route_to('student.assessments.exams.index')); ?><!--" class="nav-link">-->
<!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-list-alt"></i> </span>-->
<!--                                        <span class="sidenav-normal"> Exams </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a href="--><?php //echo site_url(route_to('student.assessments.final_grade.index')); ?><!--" class="nav-link">-->
<!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-list-alt"></i> </span>-->
<!--                                        <span class="sidenav-normal"> Final Result </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                --><?php //do_action('admin_links_assessments'); ?>
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    --><?php //do_action('student_links_after_assessments'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('students/exams', 'active') ?><!--" href="#navbar-secondary-exams" data-toggle="collapse" role="button"-->
<!--                           aria-expanded="--><?php //echo active_link('students/exams', 'true', 'false') ?><!--" aria-controls="navbar-secondary-exams">-->
<!--                            <i class="fa fa-square text-primary"></i>-->
<!--                            <span class="nav-link-text"> EXAMS </span>-->
<!--                        </a>-->
<!--                        <div class="collapse --><?php //echo active_link('students/exams', 'show') ?><!--" id="navbar-secondary-exams">-->
<!--                            <ul class="nav nav-sm flex-column">-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link --><?php //echo active_link('students/exams', 'active'); ?><!--" href="--><?php //echo site_url(route_to('student.exams.index')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-square text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Exam Schedule</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link --><?php //echo active_link('exams/results', 'active'); ?><!--" href="--><?php //echo site_url(route_to('student.exam.results')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Exam Results</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    --><?php //do_action('student_links_after_exams'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('students/assessment-results', 'active'); ?><!--" href="--><?php //echo site_url(route_to('student.assessment_results')); ?><!--">-->
<!--                            <i class="fa fa-check-circle text-primary"></i>-->
<!--                            <span class="nav-link-text">ASSESSMENT RESULTS</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('students/certificate', 'active'); ?><!--" href="--><?php //echo site_url(route_to('student.certificate')); ?><!--">-->
<!--                            <i class="fa fa-certificate text-primary"></i>-->
<!--                            <span class="nav-link-text">YEARLY CERTIFICATE</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('messages', 'active'); ?><!--" href="--><?php //echo site_url(route_to('student.messages')); ?><!--"-->
<!---->
<!--                            >-->
<!--                            <i class="fa fa-envelope-open text-primary"></i>-->
<!--                            <span class="nav-link-text">MESSAGES</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</nav>-->
<!-- Main content -->
<div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Navbar links -->
                <a class="navbar-bran" href="<?php echo site_url(route_to('student.index')); ?>">
                    <?php $file = get_option( 'website_logo', FALSE);
                    ?>
                    <img src="<?php echo $file ? base_url('uploads/files/'.$file) : base_url('assets/img/brand/blue.png'); ?>" class="navbar-brand-img" style="width: 100%; max-height: 80px" alt="...">
                </a>
                <ul class="navbar-nav">
                    <?php if (isMobile()):?>
                        <li class="parent_mobile">
                            <a href="<?php echo site_url(route_to('student.index')); ?>">
                                <i class="fa fa-list fa-2x white-color"></i>
                                <span title="menu white-color" style="color: #fff">ማውጫ</span>
                            </a>
                        </li>
                    <?php else:?>
                        <li class="parent_web">
                            <a href="javascript:void();" onclick="return false" class="nav-link" data-toggle="modal" data-target="#menuModal">
                                <i class="fa fa-list"></i>
                                <span title="menu">ማውጫ</span>
                            </a>
                        </li>
                    <?php endif;?>
                </ul>
                <ul class="navbar-nav align-items-center  ml-md-auto ">
                    <?php
                    $notifications = apply_filters('admin_notifications', array());
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="ni ni-bell-55"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl  dropdown-menu-right  py-0 overflow-hidden">
                            <!-- Dropdown header -->
                            <div class="px-3 py-3">
                                <h6 class="text-sm text-muted m-0">You have <strong
                                            class="text-primary"><?php echo is_array($notifications) ? count($notifications) : '0'; ?></strong>
                                    notifications.</h6>
                            </div>
                            <!-- List group -->
                            <div class="list-group list-group-flush">
                                <?php
                                foreach ($notifications as $notification) {
                                    ?>
                                    <a href="<?php echo $notification['link'] ?? '#!'; ?>"
                                       class="list-group-item list-group-item-action">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <!-- Avatar -->
                                                <img alt="icon" src="<?php echo $notification['icon'] ?? null; ?>"
                                                     class="avatar rounded-circle">
                                            </div>
                                            <div class="col ml--2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="mb-0 text-sm"><?php echo $notification['sender'] ?? ''; ?></h4>
                                                    </div>
                                                    <div class="text-right text-muted">
                                                        <small><?php echo $notification['time'] ?? ''; ?></small>
                                                    </div>
                                                </div>
                                                <p class="text-sm mb-0"><?php echo $notification['message']; ?></p>
                                            </div>
                                        </div>
                                    </a>
                                    <?php
                                }
                                do_action('admin_notifications_items');
                                ?>
                            </div>
                            <?php
                            if (is_array($notifications) && count($notifications) > 0) {
                                ?>
                                <!-- View all -->
                                <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View
                                    all</a>
                                <?php
                            }
                            ?>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="<?php echo $user->avatar; ?>">
                  </span>
                                <div class="media-body  ml-2  d-none d-lg-block">
                                    <span class="mb-0 text-sm  font-weight-bold"><?php echo trim($user->username); ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu  dropdown-menu-right ">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome!</h6>
                            </div>
                            <a href="<?php echo site_url(route_to('student.index', $user->id)); ?>"
                               class="dropdown-item">
                                <i class="ni ni-single-02"></i>
                                <span>My profile</span>
                            </a>
                            <a href="<?php echo site_url(route_to('profile.password.change_password')); ?>" class="dropdown-item">
                                <i class="fa fa-key"></i>
                                <span>Change Password</span>
                            </a>
                            <?php
                            do_action('admin_profile_dropdown_links');
                            ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo site_url(route_to('auth.logout')); ?>" class="dropdown-item">
                                <i class="ni ni-user-run"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<!--    <button type="button" data-toggle="modal" data-target="#menuModal">OPEN MENU</button>-->
    <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <style>
                    #theNavMenu .col-md-2 {
                        /*border: 2px solid;*/
                        margin: 2px;
                    }
                    .pt5{
                        padding-top: 5% !important;
                    }
                </style>
                <div class="modal-body" id="theNavMenu">
                    <div class="p-3 parent_web">
                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.index')); ?>"><img title="dashboard" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/home.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.calendar')); ?>?navCalendar#navEventsCalendar"><img title="events calendar" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/calender.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.attendance')); ?>"><img title="attendance" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/attendance.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.requirements')); ?>"><img title="requirements" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/check list.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                            <a href="<?php echo site_url(route_to('student.payments')); ?>"><img title="Payments" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/payment.png') ?>" /> </a>
                           </div>

                        </div>
                        <hr/>
                        <h3 class="title">Class</h3>
                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('students.class.timetable')); ?>"><img title="class-schedule" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/class schedule.png') ?>" /> </a>
                            </div>
<!--                            <div class="col-md-2 col-sm-4 col-xs-6">-->
<!--                                <a href="--><?php //echo site_url(route_to('students.class.subjects')); ?><!--"><img title="subjects" class="img" style="max-height: 100px" src="--><?php //echo base_url('assets/img/menu/notes.png') ?><!--" /> </a>-->
<!--                            </div>-->
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.exams.index')); ?>"><img title="exams" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/exam schedule.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.exam-results')); ?>"><img title="exam results" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/exam result.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                           <a href="<?php echo site_url(route_to('student.assessment.results')); ?>"><img title="assessment results" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/continuous-improvement.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.certificate')); ?>"><img title="certificate" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/certificate.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.messages')); ?>"><img title="messages" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/message.png') ?>" /></a>
                            </div>
<!--                            <div class="col-md-2 col-sm-4 col-xs-6">-->
<!--                                <a href="--><?php //echo site_url(route_to('students.class.asp')); ?><!--"><img title="asp" class="img" style="max-height: 100px" src="--><?php //echo base_url('assets/img/menu/class schedule.png') ?><!--" /> </a>-->
<!--                            </div>-->
                        </div>
                        <hr/>
                        <h3 class="title">Home School</h3>
                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('students.class.notes')); ?>"><img title="notes" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/notes.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.assignments')); ?>"><img title="assignments" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/assignment.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.assessments.classwork.index')); ?>"><img title="classwork" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/class work.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.assessments.quizes.index')); ?>"><img title="quiz" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/quiz.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.assessments.exams.index')); ?>"><img title="exams" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/exam.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('student.assessments.final_grade.index')); ?>"><img title="final grade" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/result.png') ?>" /> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $_content; ?>
    <div class="container-fluid">
        <footer class="footer pt-0">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6">
                    <div class="copyright text-center text-lg-left text-muted">
                        &copy; <?php echo date('Y'); ?> <a href="https://bennito254.com/" class="font-weight-bold ml-1"
                                                           target="_blank"><?php echo get_option('site_title', ''); ?></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <?php
                        do_action('admin_footer_list_items');
                        ?>
                        <li class="nav-item">
                            <a href="https://www.bennito254.com/" class="nav-link" target="_blank"><?php echo get_option('site_title', ''); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="<?php echo base_url('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/js-cookie/js.cookie.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js'); ?>"></script>
<!-- Optional JS -->
<script src="<?php echo base_url('assets/vendor/chart.js/dist/Chart.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/chart.js/dist/Chart.extension.js'); ?>"></script>
<!-- Datepicker -->
<script src="<?php echo base_url('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'); ?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('assets/vendor/select2/dist/js/select2.min.js'); ?>"></script>
<!-- Swal -->
<script src="<?php echo base_url('assets/vendor/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/toastr/toastr.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/parsley/parsley.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/vendor/moment/min/moment.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/fullcalendar/dist/fullcalendar.min.js'); ?>"></script>
<!-- Datatables -->
<script src="<?php echo base_url('assets/vendor/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.print.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-select/js/dataTables.select.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/vendor/chart.js/dist/Chart.min.js'); ?>"></script>

<!-- Argon JS -->
<script src="<?php echo base_url('assets/js/argon.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/app.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.tinytimer.min.js'); ?>"></script>

<?php do_action('student_footer'); ?>
<?php
$session = session();
if($msg = $session->getFlashData('error')) {
    ?>
    <script>
        toast('Error', '<?php echo $msg; ?>', 'error');
    </script>
<?php
} else if($msg = $session->getFlashData('success')) {
?>
    <script>
        toast('Success', '<?php echo $msg; ?>', 'success');
    </script>
<?php
} else if ($msg = $session->getFlashData('message')) {
?>
    <script>
        toast('Information', '<?php echo $msg; ?>', 'info');
    </script>
    <?php
}
?>

<!--<script>-->
<!--    CKEDITOR.replace( 'answer[1]' );-->
<!--</script>-->
</body>
</html>