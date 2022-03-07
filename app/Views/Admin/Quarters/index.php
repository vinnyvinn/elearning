<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Quarters</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_session"><i
                                class="fa fa-user-plus"></i> New Quarter
                    </button>
                    <?php use App\Models\Sessions;
                    do_action('sessions_quick_action_buttons'); ?>
                </div>
                <div class="modal fade new_session" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post"
                                  action="<?php echo site_url(route_to('admin.school.quarters.create')); ?>">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Quarter</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Quarter Name</label>
                                        <input type="text" class="form-control" name="name"
                                               value="<?php echo old('name') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Semester</label>
                                        <select class="form-control select2" data-toggle="select2" name="semester"
                                                required>
                                            <?php
                                            if ($semesters && count($semesters)) {
                                                foreach ($semesters as $semester) {
                                                    ?>
                                                    <option <?php if (old('semester', $semester->id) == 1)?>  value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                        <input type="hidden" name="session" value="<?php echo active_session()?>">

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                        </div>
                    </div>
                </div>
                </button>
            </div>
            </form>
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
                        <th>Quarter</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($quarters as $quarter) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $quarter->name;?></td>
                            <td><?php echo $quarter->semester->name; ?></td>
                            <td><?php echo $quarter->session->name; ?></td>

                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target=".edit_<?php echo $quarter->id; ?>"><i class="fa fa-edit"></i> Edit
                                </button>
                                <?php if (isSuperAdmin()):?>
                                    <a class="btn btn-sm btn-danger send-to-server-click"
                                       href="<?php echo site_url(route_to('admin.school.quarters.delete', $quarter->id)); ?>"
                                       url="<?php echo site_url(route_to('admin.school.quarters.delete', $quarter->id)); ?>"
                                       data="action:delete|id:<?php echo $quarter->id; ?>" warning-title="Delete Semester"
                                       warning-message="Are you sure you want to delete this entry?" loader="true"><i
                                                class="fa fa-trash"></i> Delete</a>
                                <?php endif;?>
                                <div class="modal fade edit_<?php echo $quarter->id; ?>" tabindex="-1" role="dialog"
                                     aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post"
                                                  action="<?php echo site_url(route_to('admin.school.quarters.create')); ?>">
                                                <input type="hidden" name="id" value="<?php echo $quarter->id; ?>"/>
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
                                                            <label for="sess">Quarter Name</label>
                                                            <input type="text" class="form-control" name="name"
                                                                   value="<?php echo old('name', $quarter->name); ?>"
                                                                   required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="sess">Semester</label><br/>
                                                            <select class="form-control select2" data-toggle="select2"
                                                                    name="semester" required>
                                                                <option> -- Please select --</option>
                                                                <?php
                                                                if ($semesters && count($semesters)) {
                                                                    foreach ($semesters as $semester) {
                                                                        ?>
                                                                        <option <?php if ($semester->id == $quarter->semester->id):?> selected="selected" <?php endif;?> value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="session" value="<?php echo active_session()?>">
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