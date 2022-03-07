<?php

?>
<div class="container">
    <h1 class="title text-center mt-5" style="font-weight: 500">INFORMATION </h1>

    <div class="row">
        <div class="col-md-12">
            <?php use App\Models\Notices;

            if (get_option('teacher_information_file')):
                $docs = json_decode(get_option('teacher_information_file'));
                ?>
            <?php endif;?>
            <div class="image-slider slider-all-controls controls-inside">
                <ul class="slides">
                  <?php if (isset($docs)):
                  foreach ($docs as $doc):
                  ?>
                    <li>
                        <img alt="Kids Expo"
                             src="<?php echo base_url('uploads/files/'.$doc); ?>" class="mission_img">
                    </li>
                    <?php endforeach;endif;?>
                </ul>
            </div>
        </div>
    </div>
    <!-- Start of Notice Board -->
    <?php
    $events = (new Notices())->orderBy('id', 'DESC')->where('active',0)->where('public', 1)->limit(1)->findAll();
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
</div>