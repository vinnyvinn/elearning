<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Transport Routes</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('transport_quick_action_buttons', $parent); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
<!--            <div class="row align-items-center justify-content-center">-->
<!--                <div class="col-md-4">-->
<!--                    <select class="form-control select2" data-toggle="select2" id="student" onchange="getRoutes()"-->
<!--                            name="student">-->
<!--                        --><?php
//                        if (count($parent->students) > 1) {
//                            ?>
<!--                            <option value="">-- Select Student --</option>-->
<!--                            --><?php
//                        foreach ($parent->students as $student) {
//                            ?>
<!--                            <option value="--><?php //echo $student->id; ?><!--">--><?php //echo $student->profile->name; ?><!--</option>-->
<!--                        --><?php
//                        }
//                        ?>
<!--                        --><?php
//                        } else {
//                        ?>
<!--                            <option value="--><?php //echo $parent->students[0]->id; ?><!--">--><?php //echo $parent->students[0]->profile->name; ?><!--</option>-->
<!--                            <script>-->
<!--                                $(document).ready(function () {-->
<!--                                    getRoutes();-->
<!--                                })-->
<!--                            </script>-->
<!--                            --><?php
//                        }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!--                <div class="col-md-4">-->
<!--                    <button type="button" class="btn btn-sm btn-success btn-block" onclick="getRoutes()"><i-->
<!--                                class="fa fa-filter"></i> Filter-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
            <div>
                <?php
                if($students = $parent->studentsCurrent) {
                    ?>
                    <div class="row">
                        <?php
                        foreach ($students as $student) {
                            ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="d-inline-block pb-0 mb-0"><?php echo $student->profile->name; ?></h2>
                                        <button class="btn btn-sm btn-primary pull-right float-right" data-toggle="modal" data-target=".manage_<?php echo $student->id; ?>" ><i class="fa fa-cog"></i> Manage</button>
                                    </div>
                                    <div class="modal fade manage_<?php echo $student->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post"
                                                      action="<?php echo site_url(route_to('parent.transport.set_route')); ?>">
                                                    <input type="hidden" name="student" value="<?php echo $student->id; ?>" />
                                                    <div class="modal-header pb-0">
                                                        <h6 class="modal-title" id="modal-title-default">Transportation Route</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label><b>Transportation Route</b></label><br/>
                                                            <?php
                                                            $transid = $student->profile->usermeta('transportation_route');
                                                            $routes = (new \App\Models\TransportRoutes())->findAll();
                                                            if($routes && count($routes) > 0) {
                                                                foreach ($routes as $route) {
                                                                    ?>
                                                                    <label>
                                                                        <input type="radio" name="route" <?php echo @$transid == $route->id ? 'checked' : ''; ?> value="<?php echo $route->id ?>" />
                                                                        <span><?php echo $route->route; ?></span><br/>
                                                                        <b>Driver Name</b> : <span><?php echo $route->driver_name; ?></span><br/>
                                                                        <b>Licence Plate</b> : <span><?php echo $route->licence_plate; ?></span><br/>
                                                                        <b>Price: <?php echo fee_currency($route->price); ?></b>
                                                                    </label>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Save</button>
                                                        <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body justify-content-center align-content-center align-self-center">
                                        <?php
                                        if($transid = $student->profile->usermeta('transportation_route')) {
                                            $route = (new \App\Models\TransportRoutes())->find($transid);
                                            ?>
                                            <h4>Route Information</h4>
                                            <p>
                                                <?php echo $route->route; ?><br/>
                                                <b>Driver Name</b> : <span><?php echo $route->driver_name; ?></span><br/>
                                                <b>Licence Plate</b> : <span><?php echo $route->licence_plate; ?></span><br/>
                                            </p>
                                            <h5>Price: <?php echo fee_currency($route->price); ?></h5>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="alert alert-warning">
                                                This student has no transportation route set up
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    var getRoutes = function () {
        var student = $('#student').val();
        var d = {
            loader: true,
            data: 'student='+student,
            url: "<?php echo site_url(route_to('parent.transport.student_routes')); ?>"
        }
    }
</script>
