<?php

use Config\Services;


?>
<!doctype html>
<html lang="en">
<head>
    <!--Meta Stuff-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="AYA School, Aspire Youth Academy"/>
    <meta name="description" content="<?php echo get_option('site_description', 'AYA School'); ?>">
    <meta name='copyright' content='2021'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Title -->
    <title><?php echo isset($site_title) ? $site_title : 'AYA School'; ?>
        | <?php echo get_option('site_name', 'AYA School'); ?></title>
    <!--End of Title-->
    <!-- CSS links -->
    <link href=" <?php echo base_url('assets/css/pe-icon-7-stroke.css'); ?>" rel="stylesheet" type="text/css"
          media="all">
    <link href=" <?php echo base_url('assets/css/et-line-icons.css'); ?>" rel="stylesheet" type="text/css"
          media="all">
    <link href=" <?php echo base_url('assets/css/font-awesome.min.css'); ?>" rel=" stylesheet
    " type="text/css" media="all">
    <link href=" <?php echo base_url('assets/css/themify-icons.css'); ?>" rel=" stylesheet
    " type="text/css" media="all" />
    <link href="<?php echo base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo base_url('assets/css/flexslider.css'); ?>" rel=" stylesheet
    " type="text/css" media="all" />
    <link href="  <?php echo base_url('assets/css/theme.css'); ?>" rel=" stylesheet
    " type="text/css" media="all" />
    <link href=" <?php echo base_url('assets/css/custom.css'); ?> " rel=" stylesheet
    " type="text/css" media="all" />
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400%7CRaleway:100,400,300,500,600,700%7COpen+Sans:400,500,600'
          rel='stylesheet' type='text/css'>
    <!--End of CSS links-->
    <script src="<?php echo base_url('assets/vendor/jquery/dist/jquery.min.js');  ?>"></script>

</head>
<!-- End of Head Start of Body -->
<body>
<?php
$phones = get_option('website_phone') ? json_decode(get_option('website_phone')) : '';

if ($phones)
    $phones = implode(',&nbsp;&nbsp;',$phones);
?>
<div class="nav-container">
    <!--Start of Nav Menu -->
    <nav>
        <div class="nav-utility">
            <div class="module left">
                <i class="ti-location-arrow">&nbsp;</i>
                <span class="sub"><?php echo get_option('website_location');?></span>
            </div>
            <!--            <div class="module left">-->
            <!--                <i class="ti-email">&nbsp;</i>-->
            <!--                <h4 class="upperclass bold">-->
            <!--                    <span>--><?php //echo safe_mailto(get_option('site_email')); ?><!--</span></h4>-->
            <!--            </div>-->
            <div class="module left">
                <i class="fa fa-phone">&nbsp;</i>
                <span>
                    <?php echo $phones?:'';?>
                    </span>
            </div>
            <div class="module right">
                <a class="btn btn-lg" href="<?php echo site_url(route_to('auth.login')); ?>">Login</a>
            </div>
        </div>
        <div class="nav-bar">
            <div class="module left">
                <?php
                $file = get_option('website_logo', FALSE);
                ?>
                <a href="<?php echo site_url(); ?>">
                    <img class="logo logo-dark" alt="Aspire School Logo"
                         src="<?php echo $file ? base_url('uploads/files/' . $file) : base_url('images/logo.png'); ?>">
                </a>
            </div>
            <div class="module widget-handle mobile-toggle right visible-sm visible-xs">
                <a href="<?php echo site_url(route_to('auth.login')); ?>">LOGIN</a>
            </div>
            <div class="module-group right">
                <div class="module left">
                    <ul class="menu hid">
                        <li class="hid">
                            <a href="<?php echo site_url(); ?>">HOME</a>
                        </li>
                        <li class="vpf hid">
                            <a href="<?php echo site_url(route_to('app.notice_board')); ?>">NOTICE BOARD</a>
                        </li>
                        <li class="vpf hid">
                            <a href="<?php echo site_url(route_to('app.student_registration')); ?>">STUDENT
                                REGISTRATION</a>
                        </li>
                        <li class="vpf hid">
                            <a href="<?php echo site_url(route_to('app.teacher_registration')); ?>">TEACHER
                                RECRUITMENT</a>
                        </li>

                        <li class="vpf hid">
                            <a href="<?php echo site_url(route_to('app.contact_us')); ?>">CONTACT US</a>
                        </li>
                        <li class="vpf">
                            <a href="<?php echo site_url(route_to('auth.login')); ?>">LOGIN</a>
                        </li>
                    </ul>
                </div>


            </div>

        </div>
    </nav>

    <!-- End of Nav Menu -->


</div>

<div class="main-container">
    <?php
    if ($error = Services::session()->getFlashdata('error')) {
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
    if ($error = Services::session()->getFlashdata('success')) {
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

    <!--Start of footer -->
    <footer class="footer-2 text-center-xs" style="background: #0c85d0">
        <div class="container">
            <div class="row">
                <div class="col-md-1">
                    <?php if (get_option('logo1'))?>
                    <img alt="Logo1"   src="<?php echo base_url('uploads/files/'.get_option('logo1')); ?>" class="footer_logo">
                </div>
                <div class="col-md-3 col-sm-6 white-color" style="line-height: 2;line-break: anywhere">
                    <h4 style="margin-bottom: 0" class="white-color">Address:</h4>
                    <?php
                    $phone1 = get_option('phone1') ? json_decode(get_option('phone1')) : '';
                    ?>

                    <?php if (is_array($phone1)):
                    foreach ($phone1 as $p):
                    ?>
                        <p style="margin-top: 1px;margin-bottom: 1px"><?php echo $p;?></p>
                     <?php endforeach;endif;?>

                            <p style="margin-top: 1px;margin-bottom: 1px"><?php echo get_option('address1');?></p>
                </div>
                <div class="col-md-1">
                    <?php if (get_option('logo2'))?>
                    <img alt="Logo2"   src="<?php echo base_url('uploads/files/'.get_option('logo2')); ?>" class="footer_logo">
                </div>
                <div class="col-md-3 -col-sm-6 white-color" style="line-height: 2">
                    <h4 style="margin-bottom: 0;" class="white-color">Address:</h4>
                    <?php
                    $phone2 = get_option('phone2') ? json_decode(get_option('phone2')) : '';
                    ?>
                    <?php if (is_array($phone2)):
                        foreach ($phone2 as $p):
                            ?>
                            <p style="margin-top: 1px;margin-bottom: 1px"><?php echo $p;?></p>
                        <?php endforeach;endif;?>

                        <p style="margin-top: 1px;margin-bottom: 1px"><?php echo get_option('address2');?></p>

                </div>
                <div class="col-md-4 text-right text-center-xs">
                    <h4 class="white-color">Social Media</h4>
                    <ul class="list-inline social-list">
                        <li>
                            <a  href="<?php  echo (get_option('twitter_link') ? get_option('twitter_link') :'#')?>" style="color: #212529">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                    <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a  href="<?php  echo (get_option('facebook_link') ? get_option('facebook_link') :'#')?>" style="color: #212529">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                </svg>
                                </i></a>
                        </li>
                        <li>
                            <a  href="<?php  echo (get_option('telegram_link') ? get_option('telegram_link') :'#')?>" style="color: #212529">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
                                </svg>
                                </i></a>
                        </li>
                        <li>
                            <a  href="<?php  echo (get_option('youtube_link') ? get_option('youtube_link') :'#')?>" style="color: #212529">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                                    <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                                </svg>
                                </i></a>
                        </li>

                    </ul>
                    <p>
                        <a href="#" class="white-color" style="font-weight: 900"> Copyright Reserved Solid System Solutions</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</div>


<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/flexslider.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/parallax.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/scripts.js'); ?>"></script>
</body>
</html>
<!-- End of footer -->