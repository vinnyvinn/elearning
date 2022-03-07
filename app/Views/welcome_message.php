<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Bennito254">
    <title><?php echo get_parent_option('system', 'site_title', 'Scholar School Information Management System'); ?></title>
    <!-- Extra details for Live View on GitHub Pages -->
    <!-- Canonical SEO -->
    <!--  Social tags      -->
    <meta name="keywords" content="scholar">
    <meta name="description" content="Scholar">

    <!-- Favicon -->
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/nucleo/css/nucleo.css'); ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css">
    <!-- Page plugins -->
    <!-- Scholar CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/argon.min.css'); ?>" type="text/css">
</head>

<body>

<nav id="navbar-main" class="navbar navbar-horizontal navbar-main navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo site_url(route_to('home.index')); ?>">
            <?php $file = get_parent_option('system', 'site_logo', FALSE); ?>
            <img src="<?php echo $file ? base_url('uploads/'.$file) : base_url('assets/img/brand/blue.png'); ?>" style="max-height:50px" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
            <div class="navbar-collapse-header">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="<?php echo site_url(route_to('home.index')); ?>">
                            <img src="<?php echo $file ? base_url('uploads/'.$file) : base_url('assets/img/brand/blue.png'); ?>" style="max-height: 50px" />
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="<?php echo site_url(route_to('home.index')); ?>" class="nav-link">
                        <span class="nav-link-inner--text">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo site_url(route_to('auth.login')); ?>" class="nav-link">
                        <span class="nav-link-inner--text">Login</span>
                    </a>
                </li>
            </ul>
            <hr class="d-lg-none" />
        </div>
    </div>
</nav>
<!-- Main content -->
<div class="main-content">
    <!-- Header -->
    <div class="header bg-primary pt-5 pb-7">
        <div class="container">
            <div class="header-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="pr-5">
                            <h1 class="display-2 text-white font-weight-bold mb-0">Scholar PRO</h1>
                            <h2 class="display-4 text-white font-weight-light">A beautiful School Management System based on Bootstrap 4.</h2>
                            <p class="text-white mt-4">Scholar perfectly combines reusable HTML and modular CSS with a modern styling and beautiful markup throughout each HTML template in the pack.</p>
                            <div class="mt-5">
                                <a href="<?php echo site_url(route_to('auth.login')); ?>" class="btn btn-neutral my-2">Explore Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>
</div>
<!-- Footer -->
<footer class="py-5" id="footer-main">
    <div class="container">
        <div class="row align-items-center justify-content-xl-between">
            <div class="col-xl-6">
                <div class="copyright text-center text-xl-left text-muted">
                    &copy; <?php echo date('Y'); ?> <a href="https://bennito254.com/" class="font-weight-bold ml-1" target="_blank">Bennito254</a>
                </div>
            </div>
            <div class="col-xl-6">
                <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                    <li class="nav-item">
                        <a href="https://bennito254.com/" class="nav-link" target="_blank">Bennito254</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>


<!-- Core -->
<script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/js-cookie/js.cookie.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js'); ?>"></script>
<!-- Optional JS -->
<script src="<?php echo base_url('assets/vendor/chart.js/dist/Chart.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/chart.js/dist/Chart.extension.js'); ?>"></script>
<!-- Scholar JS -->
<script src="<?php echo base_url('assets/js/argon.min.js'); ?>"></script>
</body>
</html>