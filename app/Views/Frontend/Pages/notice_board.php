
<section class="page-title page-title-3 bg-secondary">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h3 class="uppercase mb0">ASPIRE SCHOOL NOTICE BOARD</h3>
            </div>
        </div>

    </div>


</section><section>
    <div class="container">
        <?php
        $model = new \App\Models\Notices();
        $notices = $model->where('public', "1")->where('session',active_session())->orderBy('id', 'DESC')->findAll();
        if($notices && count($notices) > 0) {
            ?>
            <div class="row">
                <?php
                foreach ($notices as $notice) {
                    ?>
                    <div class="col-sm-4">
                        <h2 class="number bold text-center"><?php echo $notice->title; ?></h2>
                        <p class="text-muted text-center">Posted On <?php echo $notice->created_at->format('d/m/Y'); ?></p>
                        <p class="text-center">
                            <?php
                            echo word_limiter($notice->info, 15);
                            if ($notice->image) {
                                ?>
                                <img style="max-height: 300px; max-width: 300px" src="<?php echo $notice->image; ?>" />
                                <?php
                            }
                            ?>
                        </p>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center">
                                <button type="button" data-toggle="modal" data-target="#<?php echo $notice->id; ?>" class="mb48 mb-xs-32 btn btn-lg">View Details</button>
                                <hr>
                            </div>
                        </div>
                        <div class="modal fade" id="<?php echo $notice->id; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="feed-item mb96 mb-xs-48">
                                            <div class="row mb16 mb-xs-0">
                                                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center">
                                                    <h6 class="uppercase mb16 mb-xs-8"><?php echo $notice->created_at->format('d/m/Y'); ?></h6>
                                                    <h3><?php echo $notice->title; ?></h3>
                                                </div>
                                            </div>

                                            <div class="row mb32 mb-xs-16">
                                                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                                                    <p class="lead">
                                                        <?php
                                                        echo $notice->info;
                                                        ?>
                                                    </p>
                                                    <?php
                                                    if ($notice->image) {
                                                        ?>
                                                        <img class="img" src="<?php echo $notice->image; ?>" />
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center">
                                                    <button type="button" class="mb48 mb-xs-32 btn btn-lg" data-dismiss="modal">Close</button>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-warning">
                No notice board items at the moment
            </div>
            <?php
        }

        ?>

    </div>

</section>