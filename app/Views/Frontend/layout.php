<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="AYA School, Aspire Youth Academy" />
    <meta name="description" content="<?php echo get_option('site_description', 'AYA School'); ?>">
    <meta name='copyright' content='2020'>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title><?php echo isset($site_title) ? $site_title : 'AYA School'; ?> | <?php echo get_option('site_name', 'AYA School'); ?></title>
    <!-- Favicon -->

    <!-- Web Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css');  ?>">
    <!--Custom CSS-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom/custom.css');  ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom/bootstrap.css');  ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom/theme.css');  ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/pe-icon-7-stroke.css');  ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/et-line-icons.css');  ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/themify-icons.css');  ?>">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>" type="text/css">
    <!-- Fancy Box CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.fancybox.min.css');  ?>">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/owl.carousel.min.css');  ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/owl.theme.default.min.css');  ?>">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/animate.min.css');  ?>">
    <!-- Slick Nav CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/slicknav.min.css');  ?>">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/magnific-popup.css');  ?>">

    <!-- Learedu Stylesheet -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/normalize.css');  ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css');  ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/responsive.css');  ?>">

    <!-- Learedu Color -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/color/color1.css');  ?>">
<!--    <link rel="stylesheet" href="--><?php //echo base_url('assets/css/color/color2.css');  ?><!--">-->
    <!--<link rel="stylesheet" href="<?php echo base_url('assets/css/color/color3.css');  ?>">-->
    <!--<link rel="stylesheet" href="<?php echo base_url('assets/css/color/color4.css');  ?>">-->
    <!--<link rel="stylesheet" href="<?php echo base_url('assets/css/color/color5.css');  ?>">-->
    <!--<link rel="stylesheet" href="<?php echo base_url('assets/css/color/color6.css');  ?>">-->
    <!--<link rel="stylesheet" href="<?php echo base_url('assets/css/color/color7.css');  ?>">-->
    <!--<link rel="stylesheet" href="<?php echo base_url('assets/css/color/color8.css');  ?>">-->
    <!-- Jquery JS-->
    <script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js');  ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-migrate.min.js');  ?>"></script>
</head>
<body>

<!-- Book Preloader -->
<div class="book_preload">
    <div class="book">
        <div class="book__page"></div>
        <div class="book__page"></div>
        <div class="book__page"></div>
    </div>
</div>
<!--/ End Book Preloader -->

<!-- Header -->
<header class="header">
    <!-- Header Inner -->
    <div class="header-inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="logo">
                        <?php $file = get_parent_option('system', 'site_logo', FALSE); ?>
                        <a href="<?php echo site_url(); ?>"><img src="<?php echo $file ? base_url('uploads/'.$file) : base_url('images/logo.png'); ?>" alt="#"></a>
                    </div>
                    <div class="mobile-menu"></div>
                </div>
                <div class="col-lg-9 col-md-9 col-12">
                    <!-- Header Widget -->
                    <div class="header-widget">
                        <div class="single-widget">
                            <i class="fa fa-phone"></i>
                            <h4>Call Now<span><?php echo get_option('site_phone'); ?></span></h4>

                        </div>
                        <div class="single-widget">
                            <i class="fa fa-envelope"></i>
                            <h4>Send Message <span><?php echo safe_mailto(get_option('site_email')); ?></span></h4>
                        </div>
                        <div class="single-widget">
                            <i class="fa fa-map-marker"></i>
                            <h4>Our Location<span><?php echo get_option('site_location'); ?></span></h4>
                        </div>
                    </div>
                    <!--/ End Header Widget -->
                </div>
            </div>
        </div>
    </div>
    <!--/ End Header Inner -->
    <!-- Header Menu -->
    <div class="header-menu">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-default">
                        <div class="navbar-collapse">
                            <!-- Main Menu -->
                            <ul id="nav" class="nav menu navbar-nav">
                                <li><a href="<?php echo site_url(); ?>"><i class="fa fa-home"></i> Home</a></li>
                                <li><a href="<?php echo site_url(route_to('app.notice_board')); ?>">Notice Board</a></li>
                                <li><a href="<?php echo site_url(route_to('app.student_registration')); ?>">Student Registration</a></li>
                                <li><a href="<?php echo site_url(route_to('app.teacher_registration')); ?>">Teacher Recruitment</a></li>
                                <li><a href="<?php echo site_url(route_to('app.contact_us')); ?>">Contact</a></li>
                            </ul>
                            <!-- End Main Menu -->
                            <!-- button -->
                            <div class="button">
                                <a href="<?php echo site_url(route_to('auth.login')); ?>" class="btn"><i class="fa fa-pencil"></i>Login</a>
                            </div>
                            <!--/ End Button -->
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Header Menu -->
</header>
<!-- End Header -->
<?php
if($error = \Config\Services::session()->getFlashdata('error')) {
    ?>
    <section class="our-features section">
        <div class="container">
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        </div>
    </section>
    <?php
}
if($error = \Config\Services::session()->getFlashdata('success')) {
    ?>
    <section class="our-features section">
        <div class="container">
            <div class="alert alert-success">
                <?php echo $error; ?>
            </div>
        </div>
    </section>
    <?php
}
?>
<?php echo $_content; ?>
<!-- Footer -->
<footer class="footer overlay section" style="background-image: url('<?php echo base_url('assets/img/aspire-youth-academy.jpg'); ?>')">
    <!-- Footer Top -->
    <div class="footer-top">
        <div class="container">

        </div>
    </div>
    <!--/ End Footer Top -->
    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bottom-head">
                        <div class="row">
                            <div class="col-12">
                                <!-- Social -->
                                <ul class="social">
                                    <li><a href="#"><i class="fas fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fas fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fas fa-google-plus"></i></a></li>
                                    <li><a href="#"><i class="fas fa-linkedin"></i></a></li>
                                    <li><a href="#"><i class="fas fa-youtube"></i></a></li>
                                </ul>
                                <!-- End Social -->
                                <!-- Copyright -->
                                <div class="copyright">
                                    <p>© Copyright <?php echo date('Y'); ?> <?php echo get_option('site_copyright', '<a href="#">Aspire Youth Academy</a>. All Rights Reserved'); ?></p>
                                </div>
                                <!--/ End Copyright -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Footer Bottom -->
</footer>
<!--/ End Footer -->
<!-- Popper JS-->
<!--<script src="--><?php //echo base_url('assets/js/popper.min.js');  ?><!--"></script>-->
<!-- Bootstrap JS-->
<script src="<?php echo base_url('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js');  ?>"></script>
<!-- Jquery Steller JS -->
<script src="<?php echo base_url('assets/js/jquery.stellar.min.js');  ?>"></script>
<!-- Particle JS -->
<script src="<?php echo base_url('assets/js/particles.min.js');  ?>"></script>
<!-- Fancy Box JS-->
<script src="<?php echo base_url('assets/js/facnybox.min.js');  ?>"></script>
<!-- Magnific Popup JS-->
<script src="<?php echo base_url('assets/js/jquery.magnific-popup.min.js');  ?>"></script>
<!-- Masonry JS-->
<script src="<?php echo base_url('assets/js/masonry.pkgd.min.js');  ?>"></script>
<!-- Circle Progress JS -->
<script src="<?php echo base_url('assets/js/circle-progress.min.js');  ?>"></script>
<!-- Owl Carousel JS-->
<script src="<?php echo base_url('assets/js/owl.carousel.min.js');  ?>"></script>
<!-- Waypoints JS-->
<script src="<?php echo base_url('assets/js/waypoints.min.js');  ?>"></script>
<!-- Slick Nav JS-->
<script src="<?php echo base_url('assets/js/slicknav.min.js');  ?>"></script>
<!-- Counter Up JS -->
<script src="<?php echo base_url('assets/js/jquery.counterup.min.js');  ?>"></script>
<!-- Easing JS-->
<script src="<?php echo base_url('assets/js/easing.min.js');  ?>"></script>
<!-- Wow Min JS-->
<script src="<?php echo base_url('assets/js/wow.min.js');  ?>"></script>
<!-- Scroll Up JS-->
<script src="<?php echo base_url('assets/js/jquery.scrollUp.min.js');  ?>"></script>
<!-- Main JS-->
<script src="<?php echo base_url('assets/js/main.js');  ?>"></script>
</body>
</html>