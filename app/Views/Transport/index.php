<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Transport Routes</h6>
                    <a href="<?php echo site_url(route_to('admin.transport.pdf'))?>" target="_blank" class="btn btn-danger btn-sm"><i class="fa fa-download">Pdf</i></a>
                    <a href="<?php echo site_url(route_to('admin.transport.excel'))?>" target="_blank" class="btn btn-danger btn-sm"><i class="fa fa-file-excel">Excel</i></a>
                    <a href="<?php echo site_url(route_to('admin.transport.print'))?>" target="_blank" class="btn btn-danger btn-sm"><i class="fa fa-print">Print</i></a>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target=".new_route" > <i class="fa fa-plus-square"></i> New Route</button>

                    <?php do_action('admin_transport_routes_quick_action_buttons'); ?>
                </div>
                <div class="modal fade new_route" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post" data-parsley-validate=""
                                  action="<?php echo site_url(route_to('admin.transport.save')); ?>">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Route</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Driver Name</label>
                                        <input type="text" class="form-control" name="driver_name"
                                               value="<?php echo old('driver_name') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Driver Phone</label>
                                        <input type="text" class="form-control" name="driver_phone"
                                               value="<?php echo old('driver_phone') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Vehicle Registration Number</label>
                                        <input type="text" class="form-control" name="licence_plate"
                                               value="<?php echo old('licence_plate') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Route</label>
                                        <input type="text" class="form-control" name="route"
                                               value="<?php echo old('route') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Price</label>
                                        <input type="number" min="0" class="form-control" name="price"
                                               value="<?php echo old('price') ?>" required/>
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
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <?php
            $routes = (new \App\Models\TransportRoutes())->orderBy('id', 'DESC')->findAll();
            if($routes && count($routes) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="routes-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Driver</th>
                                <th>Phone</th>
                                <th>Vehicle</th>
                                <th>Route</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($routes as $route) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $route->driver_name; ?></td>
                                <td><?php echo $route->driver_phone; ?></td>
                                <td><?php echo $route->licence_plate; ?></td>
                                <td><?php echo $route->route; ?></td>
                                <td><?php echo fee_currency($route->price); ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target=".edit_route<?php echo $route->id; ?>">Edit</button>
                                    <a href="<?php echo site_url(route_to('admin.transport.view', $route->id)); ?>" class="btn btn-info btn-sm">View</a>
                            <?php if (isSuperAdmin()):?>
                                    <a class="btn btn-sm btn-danger send-to-server-click" data="action:delete|id:<?php echo $route->id; ?>" url="<?php echo site_url(route_to('admin.transport.delete', $route->id)); ?>" warning-message="Are you sure you want to delete this route?" warning-title="Delete Route?" href="<?php echo site_url(route_to('admin.transport.delete', $route->id)); ?>">Delete</a>
                                <?php endif;?>
                                <div class="modal fade edit_route<?php echo $route->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post" data-parsley-validate=""
                                                      action="<?php echo site_url(route_to('admin.transport.save')); ?>">
                                                    <input type="hidden" name="id" value="<?php echo $route->id; ?>" />
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Update Route</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="sess">Driver Name</label>
                                                            <input type="text" class="form-control" name="driver_name"
                                                                   value="<?php echo old('driver_name', $route->driver_name) ?>" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sess">Driver Phone</label>
                                                            <input type="text" class="form-control" name="driver_phone"
                                                                   value="<?php echo old('driver_phone', $route->driver_phone) ?>" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sess">Vehicle Registration Number</label>
                                                            <input type="text" class="form-control" name="licence_plate"
                                                                   value="<?php echo old('licence_plate', $route->licence_plate) ?>" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sess">Route</label>
                                                            <input type="text" class="form-control" name="route"
                                                                   value="<?php echo old('route', $route->route) ?>" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sess">Price</label>
                                                            <input type="number" min="0" class="form-control" name="price"
                                                                   value="<?php echo old('price', $route->price) ?>" required/>
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

                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-routes">
                    No transport routes have been added
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#routes-table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
            ],
        });
    })
</script>