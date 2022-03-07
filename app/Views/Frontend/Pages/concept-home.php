
<!doctype html>
<html lang="en">
    <head>
    <!--Meta Stuff-->    
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="AYA School, Aspire Youth Academy" />
    <meta name="description" content="<?php echo get_option('site_description', 'AYA School'); ?>">
    <meta name='copyright' content='2021'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Title -->    
        <title><?php echo isset($site_title) ? $site_title : 'AYA School'; ?> | <?php echo get_option('site_name', 'AYA School'); ?></title>
    <!--End of Title-->    
    <!-- CSS links -->    
        <link href=" <?php echo base_url('revised-ui/css/pe-icon-7-stroke.css') ; ?>" rel="stylesheet" type="text/css" media="all">
        <link href=" <?php echo base_url('revised-ui/css/et-line-icons.css');?>" rel="stylesheet" type="text/css" media="all">
        <link href=" <?php echo base_url('revised-ui/css/font-awesome.min.css');?> rel="stylesheet" type="text/css" media="all">
        <link href=" <?php echo base_url('revised-ui/css/themify-icons.css');?> rel="stylesheet" type="text/css" media="all" />
        <link href=" <?php echo base_url('revised-ui/css/bootstrap.css');?> rel="stylesheet" type="text/css" media="all" />
        <link href=" <?php echo base_url('revised-ui/css/flexslider.css');?> rel="stylesheet" type="text/css" media="all" />
        <link href="  <?php echo base_url('revised-ui/css/theme.css');?> rel="stylesheet" type="text/css" media="all" />
        <link href=" <?php echo base_url('revised-ui/css/custom.css');?> rel="stylesheet" type="text/css" media="all" />
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400%7CRaleway:100,400,300,500,600,700%7COpen+Sans:400,500,600' rel='stylesheet' type='text/css'>
    <!--End of CSS links-->
    
    </head>
    <!-- End of Head Start of Body -->
    <body>
				
		<div class="nav-container">
	<!--Start of Nav Menu -->	    
		    <nav>
		        <div class="nav-utility">
		            <div class="module left">
		                <i class="ti-location-arrow">&nbsp;</i>
		                <span class="sub">Nefas Silk Lafto 16/17, Addis Ababa</span>
		            </div>
		            <div class="module left">
		                <i class="ti-email">&nbsp;</i>
		                <h4 class="upperclass bold">Send Message <span><?php echo safe_mailto(get_option('site_email')); ?></span></h4>
		            </div>
		            <div class="module right">
		                <a class="btn btn-lg" href="<?php echo site_url(route_to('auth.login')); ?>">Login</a>
		            </div>
		        </div>
		        <div class="nav-bar">
		            <div class="module left">
		              
		                <a href="<?php echo site_url(); ?>">
		                    <img class="logo logo-light" alt="Aspire School Logo" src="<?php echo $file ? base_url('uploads/'.$file) : base_url('images/logo.png'); ?>" >
		                    <img class="logo logo-dark" alt="Aspire School Logo" src="<?php echo $file ? base_url('uploads/'.$file) : base_url('images/logo.png'); ?>" >
		                </a>
		            </div>
		            <div class="module widget-handle mobile-toggle right visible-sm visible-xs">
		                <i class="ti-menu"></i>
		            </div>
		            <div class="module-group right">
		                <div class="module left">
		                    <ul class="menu">
		                        <li>
		                            <a href="<?php echo site_url(); ?>">HOME</a>
		                        </li><li class="vpf">
		                            <a href="<?php echo site_url(route_to('app.notice_board')); ?>">NOTICE BOARD</a>
		                        </li><li class="vpf">
		                            <a href="<?php echo site_url(route_to('app.student_registration')); ?>">STUDENT REGISTRATION</a>
		                        </li><li class="vpf">
		                            <a href="<?php echo site_url(route_to('app.teacher_registration')); ?>">TEACHER RECRUITMENT</a>
		                        </li>
		                        
		                        <li class="vpf">
		                            <a href="<?php echo site_url(route_to('app.contact_us')); ?>">CONTACT US</a>
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
		    
		    
		    
		<!--Start of Content-->			
			<section class="image-slider slider-all-controls controls-inside parallax pt0 pb0 height-70">
		        <ul class="slides">
		            <li class="overlay image-bg">
		                <div class="background-image-holder">
		                    <img alt="Kids Parade 1" class="background-image" src="img/kids-parade-flag.jpeg">
		                </div>
		                <div class="container v-align-transform">
		                    <div class="row text-center">
		                        <div class="col-md-10 col-md-offset-1">
		                            <h2 class="mb-xs-16">Welcome to Aspire Youth Academy</h2>
		                            
		                            <a class="btn btn-lg" href="<?php echo site_url(route_to('app.teacher_registration')); ?>">TEACHER REGISTRATION</a>
		                            <a class="btn btn-lg btn-filled" href="<?php echo site_url(route_to('app.student_registration')); ?>">STUDENT ReGISTRATION</a>
		                        </div>
		                    </div>
		                    
		                </div>
		                
		            </li>
		        </ul>
		    </section>
		    
		    
	<!-- Start of Notice Board -->
	<?php
$events = (new \App\Models\Notices())->orderBy('id', 'DESC')->where('public', 1)->limit(10)->findAll();
if ($events && count($events) > 0) {
    ?>
	    <section class="events section bg-secondary">
		        <div class="container">
		            <div class="row">
		                <div class="col-sm-8 col-sm-offset-2 text-center">
		                    <h3 class="uppercase color-primary mb64 mb-xs-24">SCHOOL NOTICE BOARD</h3>
		                    <div class="testimonials text-slider slider-arrow-controls">
		                        <ul class="slides">
		                            <?php
                        foreach ($events as $event) {
                            ?>
		                            <li class="single-event">
		                              <div class="event-content">  
		                                <p class="lead"><?php echo $event->info; ?></p>
		                                <div class="quote-author">
		                                    
		                                    <h6 class="uppercase">?php echo $event->created_at->format('M d, Y'); ?</h6>
		                                    
		                                </div>
		                               </div>
		                            </li>
		                            <?php
                        }
                        ?>
		                            
		                            
		                        </ul>
		                    </div>
		                </div>
		            </div>
		            
		        </div>
		        
		    </section>
		    
		    
	<!-- End of Events -->
	<?php
}
?>
		    
	<!--End of Notice Board -->	    
	<!-- Start of Vision & Mission -->	    
		    <section class="image-square left">
		        <div class="col-md-6 image">
		            <div class="background-image-holder">
		                <img alt="image" class="background-image" src="img/kids-graduation-1.jpeg">
		            </div>
		        </div>
		        <div class="col-md-6 col-md-offset-1 content">
		            <h3>Our Vision</h3>
		            <p class="mb0">
		                Our vision at Aspire Youth Academy is to empower students to acquire, demonstrate, articulate and value knowledge and skills that will support them, as life-long learners, to participate in and contribute to the global world and practise the core values of the school: respect, tolerance &amp; inclusion, and excellence.
		            </p>
		        </div>
		    </section>
            <section>
		        <div class="container">
		            <div class="row">
		                <div class="col-sm-12 text-center">
		                    <h4 class="uppercase mb16">Our Mission</h4>
		                    <p class="lead mb64">
		                        Our mission is to enable all learners access to learning through</p>
		                </div>
		            </div>
		            
		            <div class="row">
		                <div class="col-sm-12">
		                    <div class="image-slider slider-all-controls controls-inside">
		                        <ul class="slides">
		                            <li>
		                                <img alt="Kids Expo" src="img/kids-science-expo-1.jpeg">
		                            </li>
		                        </ul>
		                    </div>
		                    
		                </div>
		            </div>
		            
		        </div>
		        
		    </section>
		    
		    <!-- End of Vision & Mission -->
		    <!-- Start of USP-->
		    <section>
		        <div class="container">
		            <div class="row">
		                <div class="col-md-4 col-sm-6">
		                    <div class="feature feature-3 mb-xs-24 mb64">
		                        <div class="left">
		                            <i class="pe-7s-users icon-sm"></i>
		                        </div>
		                        <div class="right">
		                            <h5 class="uppercase mb16">Highly effective teachers</h5>
		                            <p>Focus on improving student outcomes, through their commitment to ongoing professional development, quality teaching, evidence based practices, coaching and mentoring and collaboration 
		                            </p>
		                        </div>
		                    </div>
		                    
		                </div>
		                <div class="col-md-4 col-sm-6">
		                    <div class="feature feature-3 mb-xs-24 mb64">
		                        <div class="left">
		                            <i class="ti-medall icon-sm"></i>
		                        </div>
		                        <div class="right">
		                            <h5 class="uppercase mb16">conducive learning environment</h5>
		                            <p>
		                                a quality inclusive learning environment that is responsive to student voice 
		                            </p>
		                        </div>
		                    </div>
		                    
		                </div>
		                <div class="col-md-4 col-sm-6">
		                    <div class="feature feature-3 mb-xs-24 mb64">
		                        <div class="left">
		                            <i class="fa fa-diamond icon-sm"></i>
		                        </div>
		                        <div class="right">
		                            <h5 class="uppercase mb16">enriching, engaging resources </h5>
		                            <p>
		                                We provide quality enriching, engaging resources to ensure the students are able to achieve their potential and enjoy learning&nbsp;</p>
		                        </div>
		                    </div>
		                    
		                </div>
		                <div class="col-md-4 col-sm-6">
		                    <div class="feature feature-3 mb-xs-24 mb64">
		                        <div class="left">
		                            <i class="et-line-globe icon-sm"></i>
		                        </div>
		                        <div class="right">
		                            <h5 class="uppercase mb16">PARTNERSHIPS</h5>
		                            <p>
		                                Opportunities for community and parents to participate in learning and decision making partnerships. 
		                            </p>
		                        </div>
		                    </div>
		                    
		                </div>
		                <div class="col-md-4 col-sm-6">
		                    <div class="feature feature-3 mb-xs-24">
		                        <div class="left">
		                            <i class="pe-7s-light icon-sm"></i>
		                        </div>
		                        <div class="right">
		                            <h5 class="uppercase mb16">LEARNING PROGRAMS</h5>
		                            <p>
		                                Differentiated, in-depth and cohesive learning programs aligned to year level content and achievement standards informed by the Ethiopian curriculum
		                            </p>
		                        </div>
		                    </div>
		                    
		                </div>
		                <div class="col-md-4 col-sm-6">
		                    <div class="feature feature-3 mb-xs-24">
		                        <div class="left">
		                            <i class="ti-dashboard icon-sm"></i>
		                        </div>
		                        <div class="right">
		                            <h5 class="uppercase mb16">Built for Performance</h5>
		                            <p>Student Registration Link</p>
		                        </div>
		                    </div>
		                    
		                </div>
		            </div>
		            
		        </div>
		        
		    </section>
		    <!-- End of USP -->
		    
		    <!--Start of Events Cards -->
<?php
$events = (new Events())->where('public', 1)->findAll();
if ($events && count($events) > 0) {
    ?>		    
		    <section>
		        <div class="container">
		            <div class="row">
		                <div class="col-sm-10 col-sm-offset-1 text-center">
		                    <h3 class="uppercase color-primary mb40 mb-xs-24">School EVENTS</h3>
		                    <p class="lead">Here is a calendar of events happening in the school</p>
		                </div>
		            </div>
		            
		        </div>
		        
		    </section>
		    
		    <section>
		        <div class="container">
		            <div class="row">
		                <?php
                        foreach ($events as $event) {
                            ?>
		                <div class="col-md-4 col-sm-6 single-event">
		                    <div class="image-tile outer-title text-center">
		                        
		                        <div class="title mb16">
		                            <h5 class="uppercase mb0">Event Details</h5>
		                            <span><?php echo $event->starting_date->format('M d, Y'); ?></span>
		                        </div>
		                        <p class="mb0">
		                            <?php echo $event->description; ?>
		                        </p>
		                    </div>
		                </div>
		                
		                <?php
                        }
                        ?>
		                
		            </div>
		            
		        </div>
		        
		    </section>
		    
		    <!--End of Events Cards -->
<?php
}
?>		    
		    <!--Start of Gallery Slides -->
		    <section>
		       <div class="container">
		            <div class="row">
		                <div class="col-sm-12 text-center">
		                    <h4 class="uppercase mb16">SCHOOL GALLERY</h4>
		                    <p class="lead mb64">
		                        Photos from our school and school events</p>
		                </div>
		            </div>
		            
		            <div class="row">
		                <div class="col-sm-12">
		                    <div class="image-slider slider-all-controls controls-inside">
		                        <ul class="slides">
		                            <li>
		                                <img alt="Image" src="img/cover14.jpg">
		                            </li>
		                            <li>
		                                <img alt="Image" src="img/cover15.jpg">
		                            </li>
		                            <li>
		                                <img alt="Image" src="img/cover16.jpg">
		                            </li>
		                        </ul>
		                    </div>
		                    
		                </div>
		            </div>
		            
		        </div>
		        
		    </section>
		    <!--End of Gallery Slides -->
		    
		    <footer class="footer-2 bg-dark text-center-xs">
				<div class="container">
					<div class="row">
						<div class="col-sm-4">
							<a href="#"><img class="image-xxs fade-half" alt="Aspire Logo" src="img/aspire-logo-clear.png"></a>
						</div>
					
						<div class="col-sm-4 text-center">
							<span class="fade-half">
								© Copyright Aspire School - All Rights Reserved
							</span>
						</div>
					
						<div class="col-sm-4 text-right text-center-xs">
							<ul class="list-inline social-list">
								<li><a href="#"><i class="ti-twitter-alt"></i></a></li>
								<li><a href="#"><i class="ti-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-youtube-play"></i></a></li>
								
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
		
				
	<script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/flexslider.min.js"></script>
        <script src="js/parallax.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
				