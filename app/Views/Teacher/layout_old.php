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
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css'); ?>" type="text/css">

    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/dist/css/select2.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/acl.css'); ?>" type="text/css">
    <?php do_action('admin_head'); ?>
    <script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js'); ?>"></script>
</head>

<body>
<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  d-flex  align-items-center">
            <a class="navbar-brand" href="<?php echo site_url(route_to('admin.index')); ?>">
                <?php $file = get_parent_option('system', 'site_logo', FALSE); ?>
                <img src="<?php echo $file ? base_url('uploads/'.$file) : base_url('assets/img/brand/blue.png'); ?>" class="navbar-brand-img" alt="...">
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
                        <a class="nav-link <?php echo active_link('teachers/profile', 'active'); ?>" href="<?php echo site_url(route_to('teacher.index')); ?>">
                           <i class="fa fa-user text-primary"></i>
                            <span class="nav-link-text">DASHBOARD</span>
                        </a>
                    </li>
                    <?php do_action('teacher_links_after_dashboard'); ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url(route_to('teacher.attendance.students')); ?>"
                           class="nav-link">
                            <i class="fa fa-check-square text-primary"></i>
                            <span class="nav-link-text">STUDENT ATTENDANCE </span>
                        </a>
                    </li>
                    <?php do_action('teacher_links_after_attendance'); ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url(route_to('teacher.lesson_plan.index')); ?>"
                           class="nav-link">
                            <i class="fa fa-check-square text-primary"></i>
                            <span class="nav-link-text">LESSON PLAN </span>
                        </a>
                    </li>
                    <?php do_action('teacher_links_after_lesson_plan'); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo active_link('teachers/schedules', 'active') ?>" href="#navbar-schedules" data-toggle="collapse" role="button"
                           aria-expanded="<?php echo active_link('teachers/schedules', 'true', 'false') ?>" aria-controls="navbar-schedules">
                            <i class="fa fa-list-alt text-primary"></i>
                            <span class="nav-link-text">SCHEDULES</span>
                        </a>
                        <div class="collapse <?php echo active_link('teachers/schedules', 'show') ?>" id="navbar-schedules">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.schedules.student.regular')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> RS </span>
                                        <span class="sidenav-normal"> Student Regular Schedule </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.schedules.student.asp')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> ASP </span>
                                        <span class="sidenav-normal"> Student ASP Schedule </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.schedules.teacher.regular')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon"> TRS </span>
                                        <span class="sidenav-normal"> Teacher Regular Schedule </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.schedules.teacher.asp')); ?>" class="nav-link">
                                        <span class="sidenav-mini-icon">My ASP </span>
                                        <span class="sidenav-normal"> Teacher ASP Schedule </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('teacher_links_after_attendance'); ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url(route_to('teacher.classes')); ?>"
                           class="nav-link">
                            <i class="fa fa-building text-primary"></i>
                            <span class="nav-link-text">CLASSES </span>
                        </a>
                    </li>
                    <?php do_action('teacher_links_after_classes'); ?>
<!--                    <li class="nav-item">-->
<!--                        <a href="--><?php //echo site_url(route_to('teacher.exams.cats.index')); ?><!--"-->
<!--                           class="nav-link">-->
<!--                            <i class="fa fa-edit text-primary"></i>-->
<!--                            <span class="nav-link-text">CONTINUOUS ASSESSMENTS </span>-->
<!--                        </a>-->
<!--                    </li>-->
                    <li class="nav-item">
                        <a href="#nav-assessment" title="CONTINUOUS ASSESSMENTS" class="nav-link" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="nav-assessment">
                            <i class="fa fa-list-alt text-primary"></i>
                            <span class="sidenav-normal"> HOME SCHOOL </span>
                        </a>
                        <div class="collapse <?php echo active_link('teachers/assessments', 'show') ?>" id="nav-assessment" style="">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.academic.assessments.class_work')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> CW </span>
                                        Class Work
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.academic.assessments.quizes.index')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> Q </span>
                                        Quizes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.academic.assessments.exam')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> E </span>
                                        Exam
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.academic.assessments.calculate_ca')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> Calc </span>
                                        Calculate Continuous Assessment
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.academic.assessments.calculate_fg')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> CFG </span>
                                        Calculate Final Grade
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo site_url(route_to('teacher.academic.assessments.rank')); ?>" class="nav-link ">
                                        <span class="sidenav-mini-icon"> R </span>
                                        Rank
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php do_action('teacher_links_after_continuous_assessments'); ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url(route_to('teacher.academic.assessments.manual.index')); ?>"
                           class="nav-link">
                            <i class="fa fa-sticky-note text-primary"></i>
                            <span class="sidenav-normal">ASSESSMENTS </span>
                        </a>
                    </li>
                    <?php do_action('teacher_links_after_manual_assessments'); ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url(route_to('teacher.exams.index')); ?>"
                           class="nav-link">
                            <i class="fa fa-sticky-note text-primary"></i>
                            <span class="sidenav-normal">EXAMS </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo site_url(route_to('teacher.academic.yearly_certificate')); ?>"
                           class="nav-link">
                            <i class="fa fa-sticky-note text-primary"></i>
                            <span class="sidenav-normal">YEARLY CERTIFICATE </span>
                        </a>
                    </li>
                    <?php do_action('teacher_links_after_exams'); ?>
                    <li class="nav-item">
                        <a href="<?php echo site_url(route_to('teacher.messages')); ?>"
                           class="nav-link">
                            <i class="ni ni-chat-round text-primary"></i>
                            <span class="sidenav-normal">MESSAGES </span>
                        </a>
                    </li>
                    <?php do_action('teacher_links_after_exams'); ?>
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
                <ul class="navbar-nav align-items-center  ml-md-auto ">
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
                            <a href="<?php echo site_url(route_to('teacher.profile', $user->id)); ?>"
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
<!-- Datatables -->
<script src="<?php echo base_url('assets/vendor/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-buttons/js/buttons.print.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables.net-select/js/dataTables.select.min.js'); ?>"></script>
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
<!-- Argon JS -->
<script src="<?php echo base_url('assets/js/argon.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/app.js'); ?>"></script>
<?php do_action('admin_scripts_footer'); ?>
<?php
$session = session();
if($msg = $session->getFlashData('error')) {
    ?>
    <script>
        notify('Error', '<?php echo $msg; ?>', 'error');
    </script>
<?php
} else if($msg = $session->getFlashData('success')) {
?>
    <script>
        notify('Error', '<?php echo $msg; ?>', 'error');
    </script>
<?php
} else if ($msg = $session->getFlashData('message')) {
?>
    <script>
        notify('Error', '<?php echo $msg; ?>', 'error');
    </script>
    <?php
}
?>
</body>
</html>