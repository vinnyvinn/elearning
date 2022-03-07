<?php

use App\Models\Events;
use App\Models\Notices;
use App\Models\Slides;

$web_pics = get_option('website_pictures') ? json_decode(get_option('website_pictures')) : '';

$pictures = array();
if (!empty($web_pics)) {
    foreach ($web_pics as $pic) {
        array_push($pictures, $pic);
    }
}
?>
    <!--Start of Content-->
    <section class="image-slider slider-all-controls controls-inside parallax pt0 pb0 height-70">
        <ul class="slides">
            <?php if (!empty($pictures)):?>
            <?php foreach ($pictures as $pic):?>
            <li class="overlay image-bg">
                <div class="background-image-holder">
                    <img alt="Kids Parade 1" class="background-image"
                         src="<?php echo base_url('uploads/files/'.$pic); ?>">
                </div>
                <div class="container v-align-transform">
                        <div class="row">
                            <div class="col-md-10 text-center">
                             <h2 class=""><?php echo get_option('welcome_message','Welcome to Aspire Youth Academy')?></h2>
                            </div>
                            <div class="col-md-2">
                                <div class="btn-group">
                                    <a class="btn btn-success w-248" href="<?php echo site_url(route_to('app.pre_student_registration')); ?>">STUDENT
                                        REGISTRATION</a>
                                    <a class="btn btn-success w-248" href="<?php echo site_url(route_to('app.teacher_registration')); ?>">TEACHER
                                        APPLICATION</a>
                                    <a class="btn btn-success w-248" href="<?php echo site_url(route_to('app.administration_registration')); ?>">ADMINISTRATION
                                        APPLICATION</a>
                                    <a class="btn btn-success w-248" href="<?php echo site_url(route_to('app.information')); ?>">INFORMATION
                                        </a>
                                    <a class="btn btn-success w-248" href="<?php echo site_url(route_to('app.contact_us')); ?>">CONTACT US
                                        </a>
                                </div>
                            </div>
                        </div>

                </div>

            </li>
           <?php endforeach;endif;?>
        </ul>
    </section>

<!-- Start of Notice Board -->
<?php
$events = (new Notices())->orderBy('id', 'DESC')->where('active',1)->where('public', 1)->limit(1)->findAll();
if ($events && count($events) > 0) {
    ?>
    <section class="events section bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h3 class="uppercase color-primary mb64 mb-xs-24">SCHOOL NOTICE BOARD</h3>
                    <div class="testimonials text-slider slider-arrow-controls">
                        <ul class="slides">
                            <?php
                            foreach ($events as $event) {
                                ?>
                                <li class="single-event">
                                    <div class="event-content">
                                        <h2><?php echo $event->title;?></h2>
                                        <p class="lead"><?php echo $event->info;?></p>
                                        <?php
                                        $images = json_decode(get_option('notice_'.$event->id));
                                        ?>
                                        <?php if (isset($images[0])) {?>
                                            <a href="#" style="color:#fff" data-toggle="modal"
                                               data-target=".edit_<?php echo $event->id;?>">
                                                <img alt="slider" src="<?php echo base_url('uploads/'.$images[0]); ?>">
                                            </a>
                                        <?php }?>
                                    </div>

                                    <div class="quote-author" style="margin-top: 0">
                                    <h6 class="uppercase"><?php echo $event->created_at->format('M d, Y'); ?></h6>
                                    </div>

                                </li>
                                <?php if(is_array($images) && count($images) > 1):?>
                            <div class="modal  edit_<?php echo $event->id;?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close" style="opacity: 1">
                                            <i class="fa fa-times fa-2x" aria-hidden="true" style="color: red"></i>
                                        </button>
                                        <div class="modal-header">

                                        </div>
                                        <div class="modal-body">
                                            <div id="myCarousel" class="carousel slide">
                                                <!-- Indicators -->
                                                <ol class="carousel-indicators">
                                                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                                    <li data-target="#myCarousel" data-slide-to="1"></li>
                                                    <li data-target="#myCarousel" data-slide-to="2"></li>
                                                </ol>

                                                <!-- Wrapper for slides -->
                                                <div class="carousel-inner">
                                                    <?php $active = true; ?>
                                                    <?php foreach ($images as $img):?>
                                                    <div class="item <?php echo ($active == true)?"active":"" ?>">
                                                        <img src="<?php echo base_url('uploads/'.$img);?>" alt="Notice" style="width:100%;">
                                                    </div>
                                                        <?php $active = false; ?>
                                                  <?php endforeach;?>
                                                </div>

                                                <!-- Left and right controls -->
                                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <?php endif;?>
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
    <!-- Video Here-->
<?php if (get_option('home_videos')):?>
    <section>
      <div class="container">
          <div class="row">
              <?php $docs = get_option('home_videos') ? json_decode(get_option('home_videos')) : ''?>
               <?php if (is_array($docs) && !empty($docs)):?>
              <div class="flexslider2">
                  <ul class="slides" style="width:100%; height: 600px !important;">
                                <?php
                                    $active = true;
                                    foreach ($docs as $doc):?>
                                   <li class="<?php echo ($active == true)?"active":"" ?>">
                                      <div>
                                       <h3 style="text-decoration: underline"><?php echo $doc->title;?></h3>
                                      </div>
                                         <div>
                                             <video  height="490" muted controls>
                                                 <source src="<?php echo base_url('uploads/files/'.$doc->video)?>" type="video/mp4">
                                                 Your browser does not support HTML video.
                                             </video>
                                         </div>
                                      <p class="lead"><?php echo $doc->description;?></p>
                                   </li>
                                <?php $active = false; ?>
                                <?php endforeach;?>
                                  </ul>

                                  </div>
              <?php endif;?>
          </div>
      </div>

    </section>
<?php endif;?>

    <!--Start of Events Cards -->
<?php
$events = (new Events())->where('public', 1)->findAll();
if ($events && count($events) > 0) {
    ?>
    <section>
        <div class="container">
            <div class="row">
              <div class="text-center" style="margin-bottom: 5%">
                  <div style="background: #0b90a8;">
                      <h1 class="uppercase color-primary text-center" style="font-weight: 900;color: #fff !important;">EVENTS</h1>
                  </div>
              </div>



                <div class="col-md-12">
                    <div class="flexslider">
                        <ul class="slides">
                            <?php
                            foreach ($events as $event):?>
                                <li class="card" data-duration="10000">
                                    <div class="card-header text-center" style="background: #0b90a8;color: #fff !important;">
                                        <h3 style="color: #fff;font-weight: 900;font-size: 40px;"><?php echo date('d',strtotime($event->starting_date));?>
                                            <br>
                                            <?php echo date('M',strtotime($event->starting_date));?>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="text-primary lead text-center" style="text-transform: capitalize;font-weight: 800"><?php echo $event->name;?></h5>
                                        <hr>
                                        <p class="lead card-text"><?php echo $event->description;?></p>
                                    </div>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

    </section>



    <!--End of Events Cards -->
    <?php
}  ?>

<?php if (get_option('mission_statement')):?>
    <section>
       <div class="container">
           <div class="row">
            <div class="col-md-12">
                <div class="overlay-slide">
                    <img src="<?php echo base_url('uploads/'.get_option('mission_statement_file'));?>" alt="Mission" class="mission_img2">
                    <div>
                        <header>
                            <h2>Mission</h2>
                        </header>
                        <p><?php echo get_option('mission_statement')?></p>
                    </div>
                </div>

            </div>
           </div>
       </div>
    </section>
<?php endif;?>

<!--Vision -->
<?php if (get_option('vision_statement_file')):?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="overlay-slide">
                    <img src="<?php echo base_url('uploads/'.get_option('vision_statement_file'));?>" alt="Vision" class="mission_img2">
                    <div>
                        <header>
                            <h2>Vision</h2>
                        </header>
                        <p><?php echo get_option('vision_statement')?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<?php endif;?>

<!--Goal -->
<?php if (get_option('goal_statement_file')):?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="overlay-slide">
                    <img src="<?php echo base_url('uploads/'.get_option('goal_statement_file'));?>" alt="Vision" class="mission_img2">
                    <div>
                        <header>
                            <h2>Goal</h2>
                        </header>
                        <p><?php echo get_option('goal_statement')?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<?php endif;?>


<!--Footer Items -->
<section class="vg-page page-funfact mt-5" style="background-image: url(<?php echo '/uploads/'.get_option('footer_file');?>);">
    <div class="container">
        <div class="row section-counter">
            <?php
            $footer_items = get_option('footer_items') ? json_decode(get_option('footer_items')) : '';
            if (is_array($footer_items)):
            foreach ($footer_items as $item):
            ?>
            <div class="col-md-6 col-lg-3 py-4 wow fadeIn">
                <div class="vertLine">
                    <h2 class="number" style="margin-bottom: 3%"><?php echo $item->number;?></h2>
                    <p><?php echo $item->description;?></p>
                </div>
            </div>
            <?php endforeach;endif;?>
        </div>
    </div>
</section>

<script>
    $('#video').on('ended', function () {
        $('.flex-active-slide').carousel('next');
    });
    $(document).ready(function() {
        $('.flexslider').flexslider({
            animation: "slide",
            animationLoop: true,
            itemWidth: 210,
            itemMargin: 3,
            minItems: 1,
            maxItems: 3
        });
    });


    $(function() {
        $('.flexslider2').flexslider({
            animation: "slide",
            slideshow: false, // Stop Auto Slide Changes
            directionNav: false,
            start: function() {
                updateTimeVideoTimeAction();
            }, // Fires when the slider loads the first slide
            before: function() {
                // Stop all Video if any Playback left
                $('.slides video').each(function() {
                    $(this).get(0).pause();
                    $(this).get(0).currentTime = 0;
                });
            }, // Fires asynchronously with each slider animation
            after: function() {
                updateTimeVideoTimeAction();
            }, // Fires after each slider animation completes
        });
    });

    function updateTimeVideoTimeAction() {
        $('.flexslider2').flexslider("pause");
        var video = $(".flex-active-slide video").get(0);
        //video.play();
        video.addEventListener('timeupdate', (event) => {
            if (video.ended == true) {
                $('.flexslider2').flexslider("next");
            }
        });
    }

</script>