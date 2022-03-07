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

    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/fullcalendar/dist/fullcalendar.min.css'); ?>" type="text/css">
    <!-- Datatables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css'); ?>" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/dist/css/select2.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css">
    <?php do_action('parent_head'); ?>
    <script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js'); ?>"></script>
    <style>
        .red{
           background: red;
        }
        .blue{
            background: blue;
        }
        .yellow{
            background: yellow;
        }
        .colmd2{
            width: 30% !important;
        }
         .pb10{
            padding-bottom: 5% !important;
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
    </style>
</head>

<body>
<!-- Sidenav -->
<!--<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">-->
<!--    <div class="scrollbar-inner">-->
<!--        <!-- Brand -->
<!--        <div class="sidenav-header  d-flex  align-items-center">-->
<!--            <a class="navbar-brand" href="--><?php //echo site_url(route_to('parent.index')); ?><!--">-->
<!--                --><?php //$file = get_parent_option('system', 'site_logo', FALSE); ?>
<!--                <img src="--><?php //echo $file ? base_url('uploads/'.$file) : base_url('assets/img/brand/blue.png'); ?><!--" class="navbar-brand-img" alt="...">-->
<!--            </a>-->
<!--            <div class=" ml-auto ">-->
<!--                <!-- Sidenav toggler -->
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
<!--            <!-- Collapse -->
<!--            <div class="collapse navbar-collapse" id="sidenav-collapse-main">-->
<!--                <!-- Nav items -->
<!--                <ul class="navbar-nav">-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('parents/profile', 'active'); ?><!--" href="--><?php //echo site_url(route_to('parent.index')); ?><!--">-->
<!--                           <i class="fa fa-user text-primary"></i>-->
<!--                            <span class="nav-link-text">DASHBOARD</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('parents/requirements', 'active'); ?><!--" href="--><?php //echo site_url(route_to('parent.requirements')); ?><!--">-->
<!--                           <i class="fa fa-user text-primary"></i>-->
<!--                            <span class="nav-link-text">PAYMENTS &amp; <br/>REQUIREMENTS</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    --><?php //do_action('parent_links_after_class'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('parents/schedules', 'active') ?><!--" href="#navbar-schedules" data-toggle="collapse" role="button"-->
<!--                           aria-expanded="--><?php //echo active_link('parents/schedules', 'true', 'false') ?><!--" aria-controls="navbar-schedules">-->
<!--                            <i class="fa fa-calendar-alt text-primary"></i>-->
<!--                            <span class="nav-link-text"> CLASS </span>-->
<!--                        </a>-->
<!--                        <div class="collapse --><?php //echo active_link('parents/schedules', 'show') ?><!--" id="navbar-schedules">-->
<!--                            <ul class="nav nav-sm flex-column">-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" href="--><?php //echo site_url(route_to('parent.schedules.regular')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Regular Schedule </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" href="--><?php //echo site_url(route_to('parent.schedules.asp')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> After School Program </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    --><?php //do_action('parent_links_after_schedules'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="--><?php ////echo site_url(route_to('parent.exams.continuous_assessment')); ?><!--">-->
<!--                            <i class="fa fa-edit text-primary"></i>-->
<!--                            <span class="nav-link-text"> CONTINUOUS ASSESSMENT </span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('parents/assessments', 'active') ?><!--" href="#navbar-accounting" data-toggle="collapse" role="button"-->
<!--                           aria-expanded="--><?php //echo active_link('parents/assessments', 'true', 'false') ?><!--" aria-controls="navbar-accounting">-->
<!--                            <i class="fa fa-check-circle text-primary"></i>-->
<!--                            <span class="nav-link-text"> HOME SCHOOL </span>-->
<!--                        </a>-->
<!--                        <div class="collapse --><?php //echo active_link('parents/assessments', 'show') ?><!--" id="navbar-accounting">-->
<!--                            <ul class="nav nav-sm flex-column">-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" href="--><?php //echo site_url(route_to('parent.continuous_assessments.classwork')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Class Work </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" href="--><?php //echo site_url(route_to('parent.continuous_assessments.assignment')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Assignments </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" href="--><?php //echo site_url(route_to('parent.continuous_assessments.quiz')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Quizes </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" href="--><?php //echo site_url(route_to('parent.continuous_assessments.exam')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Exams </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link" href="--><?php //echo site_url(route_to('parent.continuous_assessments.final_result')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Final Results </span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link --><?php //echo active_link('parents/exams', 'active') ?><!--" href="#navbar-secondary-exams" data-toggle="collapse" role="button"-->
<!--                           aria-expanded="--><?php //echo active_link('parents/exams', 'true', 'false') ?><!--" aria-controls="navbar-secondary-exams">-->
<!--                            <i class="fa fa-square text-primary"></i>-->
<!--                            <span class="nav-link-text"> EXAMS </span>-->
<!--                        </a>-->
<!--                        <div class="collapse --><?php //echo active_link('parents/exams', 'show') ?><!--" id="navbar-secondary-exams">-->
<!--                            <ul class="nav nav-sm flex-column">-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link --><?php //echo active_link('parents/exams', 'active'); ?><!--" href="--><?php //echo site_url(route_to('parent.exams.index')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-square text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Exam Schedule</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-item">-->
<!--                                    <a class="nav-link --><?php //echo active_link('exams/results', 'active'); ?><!--" href="--><?php //echo site_url(route_to('parent.exams.results')); ?><!--">-->
<!--                                        <span class="sidenav-mini-icon"><i class="fa fa-edit text-primary"></i></span>-->
<!--                                        <span class="sidenav-normal"> Exam Results</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="--><?php //echo site_url(route_to('parent.assessment.results')); ?><!--">-->
<!--                            <i class="fa fa-chart-bar text-primary"></i>-->
<!--                            <span class="nav-link-text"> ASSESSMENT RESULTS </span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    --><?php //do_action('parent_links_after_class'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="--><?php ////echo site_url(route_to('parent.rank.index')); ?><!--">-->
<!--                            <i class="fa fa-sort-numeric-down text-primary"></i>-->
<!--                            <span class="nav-link-text"> SEMESTER RANK </span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    --><?php //do_action('parent_links_after_class'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="--><?php //echo site_url(route_to('parent.transport.route')); ?><!--">-->
<!--                            <i class="fa fa-road text-primary"></i>-->
<!--                            <span class="nav-link-text"> TRANSPORTATION ROUTE </span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    --><?php //do_action('parent_links_after_class'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="--><?php //echo site_url(route_to('parent.student.certificate')); ?><!--">-->
<!--                            <i class="fa fa-certificate text-primary"></i>-->
<!--                            <span class="nav-link-text"> YEARLY CERTIFICATE </span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    --><?php //do_action('parent_links_after_class'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="--><?php //echo site_url(route_to('parent.messages')); ?><!--">-->
<!--                            <i class="fa fa-envelope text-primary"></i>-->
<!--                            <span class="nav-link-text"> MESSAGE </span>-->
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
                <a class="navbar-bran" href="<?php echo site_url(route_to('teacher.index')); ?>">
                    <?php $file = get_option( 'website_logo', FALSE);
                    ?>
                    <img src="<?php echo $file ? base_url('uploads/files/'.$file) : base_url('assets/img/brand/blue.png'); ?>" class="navbar-brand-img" style="width: 100%; max-height: 80px" alt="...">
                </a>
                <ul class="navbar-nav">
                    <?php if (isMobile()):?>
                    <li class="parent_mobile">
                        <a href="<?php echo site_url(route_to('teacher.index')); ?>">
                            <i class="fa fa-list fa-2x white-color"></i>
                            <span title="menu white-color" style="color: #fff">ማውጫ</span>
                        </a>
                    </li>
                    <?php else:?>
                        <li class="parent_web">
                            <a href="#" class="nav-link" data-toggle="modal" data-target="#menuModal">
                                <i class="fa fa-list"></i>
                                <span title="menu">ማውጫ</span>
                            </a>
                        </li>
                    <?php endif;?>
                </ul>

                <!-- Navbar links -->
                <ul class="navbar-nav align-items-center  ml-md-auto ">
                    <li class="nav-item d-xl-none parent_web">
                        <!-- Sidenav toggler -->
                        <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin"
                             data-target="#sidenav-main">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item d-sm-none">
                        <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                            <i class="ni ni-zoom-split-in"></i>
                        </a>
                    </li>
                    <?php
                    $notifications = apply_filters('admin_notifications', array());
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="ni ni-bell-55"></i><span class="rounded-circle bg-warning"
                                                               style="padding:2px"><?php echo is_array($notifications) ? count($notifications) : '0'; ?></span>
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
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="ni ni-ungroup"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default  dropdown-menu-right ">
                            <div class="row shortcuts px-4">
                                <?php
                                $apps = apply_filters('admin_apps_shortcuts', array());
                                if (is_array($apps) && count($apps) > 0) {
                                    foreach ($apps as $app) {
                                        ?>
                                        <a href="<?php echo $app['link'] ?? '#!' ?>" class="col-4 shortcut-item">
                                    <span class="shortcut-media avatar rounded-circle bg-gradient-red">
                                      <i class="ni ni-calendar-grid-58"></i>
                                    </span>
                                            <small><?php echo $app['name']; ?></small>
                                        </a>
                                        <?php
                                    }
                                } else {
                                    echo "<div class='align-content-center text-white'>Application Shortcuts</div>";
                                }
                                do_action('admin_apps_shortcuts_list');
                                ?>
                            </div>
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
                            <a href="<?php echo site_url(route_to('admin.users.profile', $user->id)); ?>"
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
    <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <img src="<?php echo base_url('assets/img/menu/menu.png'); ?>" style="max-height: 80px">
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <style>
                    #theNavMenu .col-md-2 {
                        /*border: 2px solid;*/
                        margin: 2px;
                    }
                </style>
                <div class="modal-body" id="theNavMenu">
                    <div class="p-3 parent_web">
                        <h3 class="title">Class</h3>
                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.index')); ?>"><img title="dashboard" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/home.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                             <a href="<?php echo site_url(route_to('teacher.calendar')); ?>"><img title="events calendar" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/calender.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.requirements.index')); ?>"><img title="requirements" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/check list.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.attendance.students')); ?>"><img title="attendance" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/attendance.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                             <a href="<?php echo site_url(route_to('teacher.lesson_plan.index')); ?>"><img title="lesson plan" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/lesson_plan.png') ?>" /> </a>
                            </div>
<!--                            <div class="col-md-2 col-sm-4 col-xs-6">-->
<!--                                <a href="--><?php //echo site_url(route_to('parent.schedules.asp')); ?><!--"><img title="asp schedule" class="img" style="max-height: 100px" src="--><?php //echo base_url('assets/img/menu/class schedule.png') ?><!--" /> </a>-->
<!--                            </div>-->
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.schedules.student.regular')); ?>"><img title="regular class-schedule" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/class schedule.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.exams.schedule')); ?>"><img title="exams" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/exam schedule.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.exams.results')); ?>"><img title="exam results" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/exam result.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.assessments.manual.index')); ?>"><img title="assessment results" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/continuous-improvement.png') ?>" /> </a>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (!is_quarter_session()):?>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.semester_ranking')); ?>"><img title="semester ranking" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/ranking.png') ?>" /> </a>
                            </div>
                            <?php else:?>
                                <div class="col-md-2 col-sm-4 col-xs-6">
                                    <a href="<?php echo site_url(route_to('teacher.academic.quarter_ranking')); ?>"><img title="semester ranking" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/ranking.png') ?>" /> </a>
                                </div>
                            <?php endif;?>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.yearly_certificate')); ?>"><img title="certificate" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/certificate.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.messages')); ?>"><img title="messages" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/message.png') ?>" /></a>
                            </div>
                        </div>
                        <hr/>
                        <h3 class="title">Home School</h3>
                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.assessments.calculate_ca')); ?>"><img title="assignments" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/assignment.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.assessments.class_work')); ?>"><img title="classwork" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/class work.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.assessments.quizes.index')); ?>"><img title="quiz" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/quiz.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.assessments.exam')); ?>"><img title="exams" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/exam.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.assessments.calculate_fg')); ?>"><img title="final grade" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/result.png') ?>" /> </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <a href="<?php echo site_url(route_to('teacher.academic.assessments.rank')); ?>"><img title="rank" class="img" style="max-height: 100px" src="<?php echo base_url('assets/img/menu/certificate.png') ?>" /> </a>
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
<?php do_action('admin_footer'); ?>
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
</body>
</html>