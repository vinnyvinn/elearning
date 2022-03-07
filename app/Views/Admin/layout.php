<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aspire Youth Academy">
    <meta name="author" content="Bennito254">
    <title> <?php echo isset($site_title) ? $site_title.' | ' : '' ?><?php echo get_parent_option('system', 'site_title', 'School Information Management System'); ?></title>
    <!--  Social tags      -->
    <meta name="keywords"
          content="scholar">
    <meta name="description" content="Scholar">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Work+Sans&display=swap" media="all">
    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/nucleo/css/nucleo.css'); ?>" type="text/css"  media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert2/dist/sweetalert2.min.css'); ?>"
          type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/toastr/toastr.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css" media="all">

    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/fullcalendar/dist/fullcalendar.min.css'); ?>" type="text/css" media="all">
    <!-- Datatables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css'); ?>" type="text/css" media="all">

    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/dist/css/select2.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/plugins.bundle.css'); ?>" type="text/css" media="all">

    <?php do_action('admin_head'); ?>
    <script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js'); ?>"></script>

    <style>

        a {
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
        }

        a:hover {
            background-color: #ddd;
            color: black;
        }

        .previous {
            background-color: #f1f1f1;
            color: black;
        }

        .next {
            background-color: #04AA6D;
            color: white;
        }
    </style>

    <style>
        .bgi-no-repeat {
            background-repeat: no-repeat; }
        .bgi-size-cover {
            background-size: cover;
        }
        .pt-12, .py-12 {
            padding-top: 3rem !important;
        }
        .flex-center {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
    </style>
</head>




<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  d-flex  align-items-center">
            <a class="navbar-brand" href="<?php echo site_url(route_to('admin.index')); ?>">
                <?php $file = get_option('website_logo', FALSE); ?>
                <img src="<?php echo $file ? base_url('uploads/files/' . $file) : base_url('images/logo.png'); ?>" class="navbar-brand-img logo" alt="...">
            </a>
            <div class=" ml-auto ">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/dashboard', 'active'); ?>" href="<?php echo site_url(route_to('admin.index')); ?>" >
                            <i class="ni ni-settings text-primary"></i>
                            <span class="nav-link-text">DASHBOARD</span>
                        </a>
                    </li>
                    <?php do_action('admin_links_after_dashboard'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/users', 'active') ?>" href="#navbar-users" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/users', 'true', 'false') ?>" aria-controls="navbar-users">
                            <i class="fa fa-users text-primary"></i>
                            <span class="nav-link-text">USERS</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/users', 'show') ?>" id="navbar-users">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.admins.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> S </span>
                                        <span class="sidenav-normal"> Admin </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.teachers.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> T </span>
                                        <span class="sidenav-normal"> Teachers </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.parents.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> P </span>
                                        <span class="sidenav-normal"> Parents </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.students.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> S </span>
                                        <span class="sidenav-normal"> Students </span>
                                    </a>
                                </li>
                                <!--                                <li class="nav-item">-->
                                <!--                                    <a href="--><?php //echo site_url(route_to('admin.students.index')); ?><!--" class="nav-link">-->
                                <!--                                        <span class="sidenav-mini-icon"> S </span>-->
                                <!--                                        <span class="sidenav-normal"> Staff </span>-->
                                <!--                                    </a>-->
                                <!--                                </li>-->
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.users.roles.index')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-cogs"></i> </span>
                                        <span class="sidenav-normal"> User Roles </span>
                                    </a>
                                </li>
                                <?php do_action('admin_links_users'); ?>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_users'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/registration', 'active') ?>" href="#navbar-registration" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/registration', 'true', 'false') ?>" aria-controls="navbar-registration">
                            <i class="fa fa-user-plus text-primary"></i>
                            <span class="nav-link-text">REGISTRATION</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/registration', 'show') ?>" id="navbar-registration">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="#navbar-online-registration" class="nav-link <?php echo active_link('admin/registration/online', 'active') ?>" data-toggle="collapse" role="button" aria-expanded="<?php echo active_link('admin/registration/online', 'true', 'false') ?>" aria-controls="navbar-online-registration">
                                        <span class="sidenav-mini-icon"> O </span>
                                        <span class="sidenav-normal"> Online Registration </span>
                                    </a>
                                    <div class="collapse <?php echo active_link('admin/registration/online', 'show') ?>" id="navbar-online-registration" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.registration.online.teacher')); ?>" class="nav-link ">Teachers Recruitment</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.registration.online.admin')); ?>" class="nav-link ">Administration Recruitment</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.registration.online.students.new_students')); ?>" class="nav-link ">New Students</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.registration.online.students.existing')); ?>" class="nav-link ">Existing Students</a>
                                            </li>
                                            <?php do_action('admin_links_academic_attendance'); ?>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.admins.create')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Register Admin </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.registration.teachers.create')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Register Teacher </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.registration.classes.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Register Classes </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.registration.subjects.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Register Subjects </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.registration.departments')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Register Departments </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.registration.students.create')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Register Students </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.registration.groups')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Register Five to One groups </span>
                                    </a>
                                </li>
                                <!--                                <li class="nav-item">-->
                                <!--                                    <a href="--><?php //echo site_url(route_to('admin.registration.teachers')); ?><!--" class="nav-link">-->
                                <!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>-->
                                <!--                                        <span class="sidenav-normal"> Register Employees </span>-->
                                <!--                                    </a>-->
                                <!--                                </li>-->
                                <?php do_action('admin_links_registration'); ?>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/academic', 'active') ?>" href="#navbar-academic" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/academic', 'true', 'false') ?>" aria-controls="navbar-academic">
                            <i class="fa fa-check text-primary"></i>
                            <span class="nav-link-text">ACADEMIC</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/academic', 'show') ?> <?php echo active_link('admin/attendance', 'show') ?>" id="navbar-academic">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="#navbar-top-performers" class="nav-link <?php echo active_link('admin/attendance', 'active') ?>" data-toggle="collapse" role="button" aria-expanded="<?php echo active_link('admin/attendance', 'true', 'false') ?>" aria-controls="navbar-top-performers">
                                        <span class="sidenav-mini-icon"> A </span>
                                        <span class="sidenav-normal"> Top Performers </span>
                                    </a>
                                    <div class="collapse <?php echo active_link('admin/attendance', 'show') ?>" id="navbar-top-performers" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.exam.results-top-three')); ?>" class="nav-link ">Top 3 Students</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.exam.results-top-five')); ?>" class="nav-link ">Top 5 Students</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.exam.results-top-ten')); ?>" class="nav-link ">Top 10 Students</a>
                                            </li>
                                            <?php do_action('admin_links_academic_attendance'); ?>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="#navbar-attendance" class="nav-link <?php echo active_link('admin/attendance', 'active') ?>" data-toggle="collapse" role="button" aria-expanded="<?php echo active_link('admin/attendance', 'true', 'false') ?>" aria-controls="navbar-attendance">
                                        <span class="sidenav-mini-icon"> A </span>
                                        <span class="sidenav-normal"> Attendance </span>
                                    </a>
                                    <div class="collapse <?php echo active_link('admin/attendance', 'show') ?>" id="navbar-attendance" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.attendance.students')); ?>" class="nav-link ">Student Attendance</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.attendance.teachers')); ?>" class="nav-link ">Teachers Attendance</a>
                                            </li>
                                            <?php do_action('admin_links_academic_attendance'); ?>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.events.calendar')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Event Calendar </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.payments')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Payments </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.requirements')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Requirements </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#nav-schedule" class="nav-link" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="nav-schedule">
                                        <span class="sidenav-mini-icon"> CS </span>
                                        <span class="sidenav-normal"> Class Schedule </span>
                                    </a>
                                    <div class="collapse <?php echo active_link('academic/schedules', 'show') ?>" id="nav-schedule" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.regular_schedule')); ?>" class="nav-link ">Student Regular Schedule</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.asp_schedule')); ?>" class="nav-link ">Student ASP Schedule</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.teachers_schedule')); ?>" class="nav-link ">Teacher's Schedule</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.teachers_asp_schedule')); ?>" class="nav-link ">Teacher's ASP Schedule</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.lesson_plan')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Lesson Plan </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.assessments.manual.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Continous Assessments </span>
                                    </a>
                                </li>
                                <?php if (!is_quarter_session()):?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.semester_ranking')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Semester Ranking </span>
                                    </a>
                                </li>
                                <?php else:?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.quarter_ranking')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Quarter Ranking </span>
                                    </a>
                                </li>
                                <?php endif;?>
                                <li class="nav-item">
                                    <a href="#nav-analysis" class="nav-link" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="nav-schedule">
                                        <span class="sidenav-mini-icon"> Analysis </span>
                                        <span class="sidenav-normal"> Result Analysis </span>
                                    </a>
                                    <div class="collapse <?php echo active_link('admin.academic.semester_analysis', 'show') ?>" id="nav-analysis" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.semester_analysis')); ?>" class="nav-link ">Above & Below 50</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.semester_analysis_others')); ?>" class="nav-link ">Others</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.yearly_certificate')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Yearly Certificate </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.notes')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Notes </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.transport.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Transportation Routes </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.messages.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Messages </span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_attendance'); ?>
                    <li class="nav-item <?php echo active_link('admin/academic/exams', 'active') ?>">
                        <a href="#nav-exams_list" class="nav-link" data-toggle="collapse" role="button" aria-expanded="<?php echo active_link('admin/academic/exams', 'true', 'false') ?>" aria-controls="nav-exams_list">
                            <i class="fa fa-edit text-primary"></i>
                            <span class="sidenav-normal"> EXAMS </span>
                        </a>
                        <div class="collapse  <?php echo active_link('admin/academic/exams', 'show') ?>" id="nav-exams_list" style="">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.exam_list')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Exam List </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.exam_schedule')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Exam Schedule </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.exam.results')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check"></i> </span>
                                        <span class="sidenav-normal"> Exam Results </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#nav-assessment" class="nav-link <?php echo active_link('admin/home-school', 'active') ?>" data-toggle="collapse" role="button" aria-expanded="<?php echo active_link('admin/home-school', 'true', 'false') ?>" aria-controls="nav-assessment">
                            <i class="fa fa-home text-primary"></i>
                            <span class="sidenav-normal"> HOME SCHOOL </span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/home-school', 'show') ?>" id="nav-assessment" style="">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="#nav-assignment" class="nav-link" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="nav-assignment">
                                        <span class="sidenav-mini-icon"> CS </span>
                                        <span class="sidenav-normal"> Assignments </span>
                                    </a>
                                    <div class="collapse <?php echo active_link('admin.academic.assignments', 'show') ?>" id="nav-assignment" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                             <a href="<?php echo site_url(route_to('admin.academic.assignments')); ?>" class="nav-link ">Workout Assignments</a>
                                            </li>
                                            <li class="nav-item">
                                             <a href="<?php echo site_url(route_to('admin.academic.assignments.writing')); ?>" class="nav-link ">Writing Assignments</a>
                                            </li>
                                            <li class="nav-item">
                                             <a href="<?php echo site_url(route_to('admin.settings.answer-options')); ?>" class="nav-link ">Option Types</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.assessments.class_work')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-list"></i> </span>
                                        <span class="sidenav-normal"> Class Work </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.assessments.quizes.index')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-list"></i> </span>
                                        <span class="sidenav-normal"> Quizes </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.assessments.exam')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-list"></i> </span>
                                        <span class="sidenav-normal"> Exam </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.assessments.calculate_ca')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-list"></i> </span>
                                        <span class="sidenav-normal"> Calculate Assessment </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.assessments.calculate_fg')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-list"></i> </span>
                                        <span class="sidenav-normal"> Calculate Final Grade </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.assessments.home-school-results')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-list"></i> </span>
                                        <span class="sidenav-normal"> Results </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.academic.assessments.rank')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-list"></i> </span>
                                        <span class="sidenav-normal"> Rank </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#nav-notes_" class="nav-link" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="nav-notes_">
                                        <span class="sidenav-mini-icon"> CS </span>
                                        <span class="sidenav-normal"> Notes </span>
                                    </a>
                                    <div class="collapse <?php echo active_link('admin.academic.notes.elibrary', 'show') ?>" id="nav-notes_" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.notes.elibrary')); ?>" class="nav-link ">E-Library</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.academic.notes')); ?>" class="nav-link ">Notes</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_home_school'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/events', 'active') ?>" href="#navbar-events" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/events', 'true', 'false') ?>" aria-controls="navbar-events">
                            <i class="fa fa-check-square text-primary"></i>
                            <span class="nav-link-text">EVENTS</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/events', 'show') ?>" id="navbar-events">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.events.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-check-square"></i> </span>
                                        <span class="sidenav-normal"> Events </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.events.calendar')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-calendar"></i> </span>
                                        <span class="sidenav-normal"> Events Calendar </span>
                                    </a>
                                </li>
                                <?php do_action('admin_links_events'); ?>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_events'); ?>
                    <!--                    <li class="nav-item">-->
                    <!--                        <a class="nav-link --><?php //echo active_link('admin/classes', 'active'); ?><!-- --><?php //echo active_link('admin/exams', 'active'); ?><!--" href="#navbar-classes" data-toggle="collapse" role="button"-->
                    <!--                           aria-expanded="--><?php //echo active_link('admin/classes', 'true', 'false') ?><!-- --><?php //echo active_link('admin/exams', 'true', 'false'); ?><!--" aria-controls="navbar-classes">-->
                    <!--                            <i class="fa fa-book text-primary"></i>-->
                    <!--                            <span class="nav-link-text"> Academics</span>-->
                    <!--                        </a>-->
                    <!--                        <div class="collapse --><?php //echo active_link('admin/classes', 'show') ?><!-- --><?php //echo active_link('admin/exams', 'show') ?><!--" id="navbar-classes">-->
                    <!--                            <ul class="nav nav-sm flex-column">-->
                    <!--                                <li class="nav-item">-->
                    <!--                                    <a href="--><?php //echo site_url(route_to('admin.subjects.index')); ?><!--" class="nav-link">-->
                    <!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>-->
                    <!--                                        <span class="sidenav-normal"> Subjects </span>-->
                    <!--                                    </a>-->
                    <!--                                </li>-->
                    <!--                                <li class="nav-item">-->
                    <!--                                    <a href="--><?php //echo site_url(route_to('admin.classes.index')); ?><!--" class="nav-link">-->
                    <!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>-->
                    <!--                                        <span class="sidenav-normal"> Classes </span>-->
                    <!--                                    </a>-->
                    <!--                                </li>-->
                    <!--                                <li class="nav-item">-->
                    <!--                                    <a href="--><?php //echo site_url(route_to('admin.classes.assessments')); ?><!--"-->
                    <!--                                       class="nav-link">-->
                    <!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>-->
                    <!--                                        <span class="sidenav-normal"> Continuous Assessments </span>-->
                    <!--                                    </a>-->
                    <!--                                </li>-->
                    <!--                                <li class="nav-item">-->
                    <!--                                    <a href="--><?php //echo site_url(route_to('admin.exams.index')); ?><!--"-->
                    <!--                                       class="nav-link">-->
                    <!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>-->
                    <!--                                        <span class="sidenav-normal"> Exams </span>-->
                    <!--                                    </a>-->
                    <!--                                </li>-->
                    <!--                                <li class="nav-item">-->
                    <!--                                    <a href="--><?php //echo site_url(route_to('admin.promotion')); ?><!--"-->
                    <!--                                       class="nav-link">-->
                    <!--                                        <span class="sidenav-mini-icon"> <i class="fa fa-angle-up"></i> </span>-->
                    <!--                                        <span class="sidenav-normal"> Promotion </span>-->
                    <!--                                    </a>-->
                    <!--                                </li>-->
                    <!--                                --><?php //do_action('admin_links_classes'); ?>
                    <!--                            </ul>-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                    <?php do_action('admin_links_after_classes'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/school', 'active') ?>" href="#navbar-school" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/school', 'true', 'false') ?>" aria-controls="navbar-school">
                            <i class="fa fa-building text-primary"></i>
                            <span class="nav-link-text">SCHOOL</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/school', 'show') ?>" id="navbar-school">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.school.classes.index')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>
                                        <span class="sidenav-normal"> Class Management </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.school.semesters.index')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>
                                        <span class="sidenav-normal"> Semesters Management </span>
                                    </a>
                                </li>
                                <?php if (is_quarter_session()):?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.school.quarters.index')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>
                                        <span class="sidenav-normal"> Quarters Management </span>
                                    </a>
                                </li>
                                <?php endif;?>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.sessions.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>
                                        <span class="sidenav-normal"> Sessions Management </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.transport.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-road"></i> </span>
                                        <span class="sidenav-normal"> Transport Routes </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.promotion')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-angle-up"></i> </span>
                                        <span class="sidenav-normal"> Promotion </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.move')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-angle-up"></i> </span>
                                        <span class="sidenav-normal"> Move Student </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.students.departure')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-angle-up"></i> </span>
                                        <span class="sidenav-normal"> Departures </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.students.transcript')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-angle-up"></i> </span>
                                        <span class="sidenav-normal"> Transcript </span>
                                    </a>
                                </li>
                                <?php do_action('admin_links_school'); ?>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_school'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/accounting', 'active') ?>" href="#navbar-accounting" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/accounting', 'true', 'false') ?>" aria-controls="navbar-accounting">
                            <i class="fa fa-dollar-sign text-primary"></i>
                            <span class="nav-link-text">ACCOUNTING</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/accounting', 'show') ?>" id="navbar-accounting">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.accounting.index')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-dollar-sign"></i> </span>
                                        <span class="sidenav-normal"> Student Fees </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.accounting.fee.collect')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-dollar-sign"></i> </span>
                                        <span class="sidenav-normal"> Record Collected Fees </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.accounting.fee.history')); ?>"
                                       class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-dollar-sign"></i> </span>
                                        <span class="sidenav-normal"> Student Fee Information </span>
                                    </a>
                                </li>
                                <?php do_action('admin_links_accounting'); ?>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_accounting'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/messages', 'active'); ?>" href="#navbar-messages" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/messages', 'true', 'false'); ?>" aria-controls="navbar-messages">
                            <i class="fa fa-envelope text-info"></i>
                            <span class="nav-link-text">SMS &AMP; MESSAGES</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/messages', 'show') ?>" id="navbar-messages">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.messages.sms')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-envelope"></i> </span>
                                        <span class="sidenav-normal"> <i class="ni ni-chat-round"></i> SMS </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.messages.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-envelope"></i> </span>
                                        <span class="sidenav-normal"> <i class="ni ni-chat-round"></i> Messages </span>
                                    </a>
                                </li>
                                <li class="nav-item" style="display: none">
                                    <a href="<?php echo site_url(route_to('admin.messages.index_parent')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-envelope"></i> </span>
                                        <span class="sidenav-normal"> <i class="ni ni-chat-round"></i> Parent Messages </span>
                                    </a>
                                </li>
                                <?php do_action('admin_links_messages'); ?>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_messages'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/frontend', 'active'); ?>" href="#navbar-frontend" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/frontend', 'true', 'false'); ?>" aria-controls="navbar-frontend">
                            <i class="ni ni-planet text-primary text-orange"></i>
                            <span class="nav-link-text">FRONTEND SETTINGS</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/frontend', 'show') ?>" id="navbar-frontend">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.frontend.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-cog"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-cog"></i> SECTION 1 </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.frontend.notice_board')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-cog"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-cog"></i> SECTION 2 </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.frontend.homepage_index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-cog"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-cog"></i> SECTION 3 </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.frontend.events')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-cog"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-cog"></i> SECTION 4 </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.frontend.mission')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-cog"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-cog"></i> SECTION 5 </span>
                                    </a>
                                </li>
                                <?php do_action('admin_links_frontend'); ?>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_frontend'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('admin/settings', 'active'); ?>" href="#navbar-settings" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('admin/settings', 'true', 'false'); ?>" aria-controls="navbar-settings">
                            <i class="ni ni-settings text-primary text-orange"></i>
                            <span class="nav-link-text">SETTINGS</span>
                        </a>
                        <div class="collapse <?php echo active_link('admin/settings', 'show') ?>" id="navbar-settings">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.student-evaluation')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-plug"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-plug"></i> Student Evaluations </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.student-comment')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-plug"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-plug"></i> Student Comments </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#navbar-kg" class="nav-link <?php echo active_link('admin.settings.kg', 'active') ?>" data-toggle="collapse" role="button" aria-expanded="<?php echo active_link('admin.settings.kg', 'true', 'false') ?>" aria-controls="navbar-kg">
                                        <span class="sidenav-mini-icon"> O </span>
                                        <span class="sidenav-normal"> KG Evaluations </span>
                                    </a>
                                    <div class="collapse <?php echo active_link('admin.settings.kg', 'show') ?>" id="navbar-kg" style="">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.settings.kg-sub-categories')); ?>" class="nav-link ">Sub Categories</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.settings.kg-categories')); ?>" class="nav-link ">Categories</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="<?php echo site_url(route_to('admin.settings.kg')); ?>" class="nav-link ">Evaluations</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.student-id')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-plug"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-plug"></i> ID Card </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.grading')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-plug"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-plug"></i>GRADING SYSTEMS </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.background-image')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-image"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-image"></i> Background Image </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.promotion-rule')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-plug"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-plug"></i> Promotion Rule </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.no-of-exams')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-plug"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-plug"></i> No.Of exams seated  </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.modules.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-plug"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-plug"></i> Modules </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.sms')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-wrench"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-wrench"></i> SMS Settings </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.index')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-cogs"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-cogs"></i> System Settings </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('admin.settings.updates')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> <i class="fa fa-cogs"></i> </span>
                                        <span class="sidenav-normal"> <i class="fa fa-upload"></i> App Updates </span>
                                    </a>
                                </li>
                                <?php do_action('admin_links_settings'); ?>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('admin_links_after_settings'); ?>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Search form -->
                <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                    <div class="form-group mb-0">
                        <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" placeholder="Search" type="text">
                        </div>
                    </div>
                    <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </form>


                <!-- Navbar links -->
                <ul class="navbar-nav align-items-center  ml-md-auto">
                    <!--begin::Notifications-->
                    <li class="nav-item dropdown">
                        <!--begin::Toggle-->
                        <a class="nav-link toggle_dropdown" href="#" role="button" data-toggle="dropdown"  aria-haspopup="true"
                           aria-expanded="false">
                            <span class="label label-pill label-danger count" style="border-radius:10px;"></span> <i class="ni ni-bell-55" style="font-size: 18px"></i></span>

                        </a>
                        <!--end::Toggle-->
                        <!--begin::Dropdown-->
                        <div class="dropdown-menu p-0 m-0 dropdown-menu-left dropdown-menu-anim-up dropdown-menu-lg">
                                <form>
                                <!--begin::Header-->
                                <div class="d-flex flex-column bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url(/assets/images/bg-1.jpg)">
                                    <!--begin::Title-->
<!--                                    <h4 class="d-flex flex-center rounded-top">-->
<!--                                        <span class="text-white">User Notifications</span>-->
<!--                                        <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">23 new</span>-->
<!--                                    </h4>-->
                                    <!--end::Title-->
                                    <!--begin::Tabs-->
                                    <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3" role="tablist" style="padding-left: 1.5%">
                                         <li class="nav-item">
                                            <a class="nav-link active show topbar_notifications_requirements" data-toggle="tab" href="#topbar_notifications_requirements">Requirements
                                                <span class="badge badge-light count_requirements"></span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link topbar_notifications_attendance" data-toggle="tab" href="#topbar_notifications_attendance">Attendance
                                                <span class="badge badge-light count_attendance"></span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link topbar_notifications_payments" data-toggle="tab" href="#topbar_notifications_payments">Payments
                                                <span class="badge badge-light count_payments"></span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link topbar_notifications_exam_results" data-toggle="tab" href="#topbar_notifications_exam_results">Exam Result
                                                <span class="badge badge-light count_exam_results"></span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link topbar_notifications_assessment" data-toggle="tab" href="#topbar_notifications_assessment">Continuous Assessment
                                                <span class="badge badge-light count_assessment"></span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link topbar_notifications_routes" data-toggle="tab" href="#topbar_notifications_routes">Transport Routes
                                                <span class="badge badge-light count_routes"></span>
                                            </a>
                                        </li>
<!--                                        <li class="nav-item">-->
<!--                                            <a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs">Logs</a>-->
<!--                                        </li>-->
                                    </ul>
                                    <!--end::Tabs-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Content-->
                                <div class="tab-content">
                                    <!--begin::Tabpane-->
                                    <div class="tab-pane active show" id="topbar_notifications_requirements" role="tabpanel">
                                        <!--begin::Nav-->
                                        <div class="navi requirements navi-hover scroll my-4" data-scroll="true" data-height="300" data-mobile-height="200">

                                        </div>
                                        <!--end::Nav-->
                                    </div>
                                    <div class="tab-pane" id="topbar_notifications_attendance" role="tabpanel">
                                        <!--begin::Nav-->
                                        <div class="navi attendance navi-hover scroll my-4" data-scroll="true" data-height="300" data-mobile-height="200">

                                      </div>
                                        <!--end::Nav-->
                                    </div>
                                    <div class="tab-pane" id="topbar_notifications_payments" role="tabpanel">
                                        <!--begin::Nav-->
                                        <div class="navi payments navi-hover scroll my-4" data-scroll="true" data-height="300" data-mobile-height="200">

                                        </div>
                                        <!--end::Nav-->
                                    </div>
                                    <div class="tab-pane" id="topbar_notifications_exam_results" role="tabpanel">
                                        <!--begin::Nav-->
                                        <div class="navi exam_result navi-hover scroll my-4" data-scroll="true" data-height="300" data-mobile-height="200">

                                        </div>
                                        <!--end::Nav-->
                                    </div>
                                    <div class="tab-pane" id="topbar_notifications_assessment" role="tabpanel">
                                        <!--begin::Nav-->
                                        <div class="navi assessment navi-hover scroll my-4" data-scroll="true" data-height="300" data-mobile-height="200">

                                        </div>
                                        <!--end::Nav-->
                                    </div>
                                    <div class="tab-pane" id="topbar_notifications_routes" role="tabpanel">
                                        <!--begin::Nav-->
                                        <div class="navi routes navi-hover scroll my-4" data-scroll="true" data-height="300" data-mobile-height="200">

                                        </div>
                                        <!--end::Nav-->
                                    </div>

                                    <!--end::Tabpane-->
                                    <!--begin::Tabpane-->
<!--                                    <div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">-->
                                        <!--begin::Nav-->
<!--                                        <div class="d-flex flex-center text-center text-muted min-h-200px">All caught up!-->
<!--                                            <br />No new notifications.</div>-->
                                        <!--end::Nav-->
<!--                                    </div>-->
                                    <!--end::Tabpane-->
                                </div>
                                <!--end::Content-->
                            </form>
                        </div>
                        <!--end::Dropdown-->
                    </li>
                    <!--end::Notifications-->
                    <li class="nav-item d-xl-none">
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
                        <div class="dropdown-menu  dropdown-menu-left">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.print.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-select/js/dataTables.select.min.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<!---->
<!---->
<!--<script src="--><?php //echo base_url('assets/vendor/js/calender/jquery-ui-1.8.23.custom.min.js'); ?><!--"></script>-->
<!--<script src="--><?php //echo base_url('assets/vendor/js/calender/jquery.Beka.EthCalDatePicker.js'); ?><!--"></script>-->
<!--<script src="--><?php //echo base_url('assets/vendor/js/calender/Beka.EthDate.js'); ?><!--"></script>-->

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
}
if($msg = $session->getFlashData('success')) {
    ?>
    <script>
      toast('Success', '<?php echo $msg; ?>', 'success');
    </script>
    <?php
}
if ($msg = $session->getFlashData('message')) {
    ?>
    <script>


        toast('Info', '<?php echo $msg; ?>', 'info');
    </script>
    <?php
}
?>

<script>
    $(function (){
        setTimeout(()=>{
            load_unseen_notification();
        },2000)

        $('.topbar_notifications_attendance').on('click',function (){
            markReadNotifications('attendance');
        })

        $('.topbar_notifications_requirements').on('click',function (){
            markReadNotifications('requirements');
        })
        $('.topbar_notifications_payments').on('click',function (){
            markReadNotifications('payments');
        })
        $('.topbar_notifications_assessment').on('click',function (){
            markReadNotifications('assessment');
        })
        $('.topbar_notifications_exam_results').on('click',function (){
            markReadNotifications('exam_results');
        })
        $('.topbar_notifications_routes').on('click',function (){
            markReadNotifications('routes');
        })
    })
    function markReadNotifications(type){
        var d = {
            url: "<?php echo site_url(route_to('admin.notifications.read')) ?>",
            data: "type=" + type,
            loader: true
        };
        ajaxRequest(d, function () {
            load_unseen_notification()
        });
    }
    function load_unseen_notification(view = ''){
        var d = {
            url: "<?php echo site_url(route_to('admin.notifications')) ?>",
            data: "view=" + view,
            loader: false
        };
        ajaxRequest(d, function (data) {
            console.log('cool')
            const output = JSON.parse(data);
            $('.requirements').html(output.requirements.data);
            $('.count_requirements').html(output.requirements.count);
            $('.attendance').html(output.attendance.data);
            $('.count_attendance').html(output.attendance.count);
            $('.payments').html(output.payments.data);
            $('.count_payments').html(output.payments.count);
            $('.exam_result').html(output.exam_result.data);
            $('.count_exam_results').html(output.exam_result.count);
            $('.assessment').html(output.assessment.data);
            $('.count_assessment').html(output.assessment.count);
            $('.routes').html(output.routes.data);
            $('.count_routes').html(output.routes.count);

            let total_count = (output.requirements.count+output.attendance.count+output.payments.count+output.exam_result.count+output.assessment.count+output.routes.count);
            if (total_count > 0)
                $('.count').html(addCommas(total_count));
            else
                $('.count').html("");
        });

    }
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    $(function (){
        // var calendarEl = document.getElementById('calendar');
        // var calendar = new FullCalendar.Calendar(calendarEl, {
        //     initialView: 'dayGridMonth'
        // });
        // calendar.render();
    })
</script>
</body>
</html>