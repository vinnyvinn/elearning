<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Semesters</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_session"><i
                                class="fa fa-user-plus"></i> New Semester
                    </button>
                    <?php use App\Models\Sessions;

                    do_action('sessions_quick_action_buttons'); ?>
                </div>
                <div class="modal fade new_session" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post"
                                  action="<?php echo site_url(route_to('admin.school.semesters.create')); ?>">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Semester</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Semester Name</label>
                                        <input type="text" class="form-control" name="name"
                                               value="<?php echo old('name') ?>" required/>
                                    </div>
                                    <input type="hidden" name="session" value="<?php echo active_session();?>">
                                    <div class="form-group">
                                        <label>Opening Date</label>
                                        <input type="text" class="form-control datepicker" name="opening_date"
                                               value="<?php echo old('opening_date'); ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Closing Date</label>
                                        <input type="text" class="form-control datepicker" name="closing_date"
                                               value="<?php echo old('closing_date'); ?>"/>
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
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <?php
        $ss = (new Sessions())->find(active_session());
        if ($ss) {
            ?>
            <div class="card-body">
                <div class="alert alert-success">
                    <h3>Active Session: <b><?php echo $ss->name; ?></b></h3>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if ($semesters && count($semesters) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table datatable" data-toggle="datatable">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th>Opening Date</th>
                        <th>Closing Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($semesters as $semester) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $semester->name; ?></td>
                            <td><?php echo $semester->session->name; ?></td>
                            <td><?php echo $semester->opening_date; ?></td>
                            <td><?php echo $semester->closing_date; ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target=".edit_<?php echo $semester->id; ?>"><i class="fa fa-edit"></i> Edit
                                </button>
                        <?php if (isSuperAdmin()):?>
                                <a class="btn btn-sm btn-danger send-to-server-click"
                                   href="<?php echo site_url(route_to('admin.school.semesters.delete', $semester->id)); ?>"
                                   url="<?php echo site_url(route_to('admin.school.semesters.delete', $semester->id)); ?>"
                                   data="action:delete|id:<?php echo $semester->id; ?>" warning-title="Delete Semester"
                                   warning-message="Are you sure you want to delete this entry?" loader="true"><i
                                            class="fa fa-trash"></i> Delete</a>
                        <?php endif;?>
                                <div class="modal fade edit_<?php echo $semester->id; ?>" tabindex="-1" role="dialog"
                                     aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post"
                                                  action="<?php echo site_url(route_to('admin.school.semesters.create')); ?>">
                                                <input type="hidden" name="id" value="<?php echo $semester->id; ?>"/>
                                                <div class="modal-body">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Update
                                                            Semester</h6>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="sess">Semester Name</label>
                                                            <input type="text" class="form-control" name="name"
                                                                   value="<?php echo old('name', $semester->name); ?>"
                                                                   required/>
                                                        </div>
                                                        <input type="hidden" name="session" value="<?php echo active_session();?>">
                                                        <div class="form-group">
                                                            <label>Opening Date</label>
                                                            <input type="text" class="form-control datepicker"
                                                                   name="opening_date"
                                                                   value="<?php echo old('opening_date', $semester->opening_date); ?>"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Closing Date</label>
                                                            <input type="text" class="form-control datepicker"
                                                                   name="closing_date"
                                                                   value="<?php echo old('closing_date', $semester->closing_date); ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Update</button>
                                                        <button type="button" class="btn btn-link  ml-auto"
                                                                data-dismiss="modal">Close
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
            <div class="card-body">
                <div class="alert alert-danger">
                    No sessions have been set up
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>