<!-- Header -->
<div class="header py-6 pt-md-9">
    <div class="container">
        <div class="header-body text-center mb-4">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8 px-0">
                    <h1 class="text-white">Welcome!</h1>
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
<!-- Page content -->
<div class="container mt-0 pb-0">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card bg-secondary border-0 mb-0">
                <div class="card-header bg-transparent pb-0 mb--1">
                    <div class="text-center text-muted">
                        <h3>Sign in</h3>
                    </div>
                </div>
                <div class="card-body px-lg-5 py-lg-5">
                    <?php
                    $session = session();
                    if($s = $session->getFlashdata('error')) {
                        ?>
                        <div class="alert alert-danger" style="border-radius: 0">
                            <?php
                            if(is_array($s)) {
                                ?>
                                <ul>
                                    <?php
                                    foreach ($s as $item) {
                                        echo '<li>'.$item.'</li>';
                                    }
                                    ?>
                                </ul>
                                <?php
                            } else {
                                echo $s;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    if($s = $session->getFlashdata('message')) {
                        ?>
                        <div class="alert alert-info" style="border-radius: 0">
                            <?php
                            if(is_array($s)) {
                                ?>
                                <ul>
                                    <?php
                                    foreach ($s as $item) {
                                        echo '<li>'.$item.'</li>';
                                    }
                                    ?>
                                </ul>
                                <?php
                            } else {
                                echo $s;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    if($s = $session->getFlashdata('success')) {
                        ?>
                        <div class="alert alert-success" style="border-radius: 0">
                            <?php
                            if(is_array($s)) {
                                ?>
                                <ul>
                                    <?php
                                    foreach ($s as $item) {
                                        echo '<li>'.$item.'</li>';
                                    }
                                    ?>
                                </ul>
                                <?php
                            } else {
                                echo $s;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <form role="form" method="POST" action="<?php echo current_url(); ?>">
                        <div class="form-group mb-3">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                <input class="form-control" name="identity" placeholder="Username" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control" placeholder="Password" name="password" type="password">
                            </div>
                        </div>
                        <div class="custom-control custom-control-alternative custom-checkbox">
                            <input class="custom-control-input" name="remember" id=" customCheckLogin" type="checkbox">
                            <label class="custom-control-label" for=" customCheckLogin">
                                <span class="text-muted">Remember me</span>
                            </label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block my-4">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6">
<!--                    <a href="--><?php //echo site_url(route_to('auth.forgot_password')); ?><!--" class="text-light"><small>Forgot password?</small></a>-->
                </div>
                <div class="col-6 text-right">
<!--                    <a href="#" class="text-light"><small>Create new account</small></a>-->
                </div>
            </div>
        </div>
    </div>
</div>