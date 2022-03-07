<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Accounting</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_fee"><i
                                class="fa fa-plus"></i> New Fee
                    </button>
                    <?php
                    do_action('admin_accounting_quick_action_buttons'); ?>
                </div>
                <div class="modal fade new_fee" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post"
                                  action="<?php echo site_url(route_to('admin.accounting.fee.create')); ?>">
                                <div class="modal-header pb-0">
                                    <h6 class="modal-title" id="modal-title-default">New Fee</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Session <span class="text-danger">*</span></label><br/>
                                        <select class="form-control select2" name="session" required id="session"
                                                onchange="getClass()">
                                            <option value=""> -- Select session --</option>
                                            <?php

                                            use App\Models\Accounting;
                                            use App\Models\Sessions;

                                            $ss = (new Sessions())->orderBy('id', 'DESC')->findAll();
                                            if ($ss && count($ss) > 0) {
                                                foreach ($ss as $s) {
                                                    ?>
                                                    <option value="<?php echo $s->id; ?>"><?php echo $s->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Semester/Term</label>
                                        <select class="form-control select2" name="semester" id="semester"">
                                        <option value=""> -- Select semester --</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Class</label><br/>
                                        <select class="form-control select2" name="class" id="class"
                                                onchange="getSections()">
                                            <option value=""> -- Select class --</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Section</label><br/>
                                        <select class="form-control select2" name="section" id="section">
                                            <option value=""> -- Select section --</option>
                                        </select>
                                    </div>
                                    <div class="form-group"><br/>
                                        <label>Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="description" required/>
                                    </div>
                                    <div class="form-group"><br/>
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input type="number" min="0" class="form-control" name="amount" required/>
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
        <div class="card-header mb--1 pb-0">
            <h2 class="h3">School Fees for Session <?php echo getSession()->name; ?></h2>
        </div>
        <?php
        $fees = (new Accounting())->where('session', active_session())->orderBy('id', 'DESC')->findAll();
        if ($fees && count($fees) > 0) {
            ?>
            <div class="table-responsive pt-2">
                <table class="table datatable" id="datatable-buttons">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Semester/Term</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($fees as $fee) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $fee->description; ?></td>
                            <td><?php echo $fee->semester ? $fee->semester->name : '-'; ?></td>
                            <td><?php echo $fee->class ? $fee->class->name : '-'; ?></td>
                            <td><?php echo $fee->section ? $fee->section->name : '-'; ?></td>
                            <td><?php echo fee_currency($fee->amount); ?></td>
                            <td>
<!--                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".edit_fee--><?php //echo $fee->id; ?><!--"><i class="fa fa-edit"></i> Edit</button>-->
                              <?php if (isSuperAdmin()):?>
                                <a class="btn btn-sm btn-danger send-to-server-click"
                                   data="action:delete|id:<?php echo $fee->id; ?>"
                                   url="<?php echo site_url(route_to('admin.accounting.fee.delete', $fee->id)); ?>"
                                   warning-title="Delete Fee"
                                   warning-message="Are you sure you want to delete this entry?"
                                   loader="true"
                                   href="<?php echo site_url(route_to('admin.accounting.fee.delete', $fee->id)); ?>"> <i
                                            class="fa fa-trash-alt"></i> Delete</a>
                                  <?php endif;?>
<!--                                <div class="modal fade edit_fee--><?php //echo $fee->id; ?><!--" tabindex="-1" role="dialog" aria-labelledby="modal-default"-->
<!--                                     style="display: none;" aria-hidden="true">-->
<!--                                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">-->
<!--                                        <div class="modal-content">-->
<!--                                            <form class="ajaxForm" loader="true" method="post"-->
<!--                                                  action="--><?php //echo site_url(route_to('admin.accounting.fee.create')); ?><!--">-->
<!--                                                <div class="modal-header">-->
<!--                                                    <h6 class="modal-title" id="modal-title-default">New Fee</h6>-->
<!--                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                                                        <span aria-hidden="true">×</span>-->
<!--                                                    </button>-->
<!--                                                </div>-->
<!--                                                <div class="modal-body">-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label>Session <span class="text-danger">*</span></label><br/>-->
<!--                                                        <select class="form-control select2" name="session" required id="session"-->
<!--                                                                onchange="getClass()">-->
<!--                                                            <option value=""> -- Select session --</option>-->
<!--                                                            --><?php
//                                                            $ss = (new Sessions())->orderBy('id', 'DESC')->findAll();
//                                                            if ($ss && count($ss) > 0) {
//                                                                foreach ($ss as $s) {
//                                                                    ?>
<!--                                                                    <option value="--><?php //echo $s->id; ?><!--">--><?php //echo $s->name; ?><!--</option>-->
<!--                                                                    --><?php
//                                                                }
//                                                            }
//                                                            ?>
<!--                                                        </select>-->
<!--                                                    </div>-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label>Semester/Term</label><br/>-->
<!--                                                        <select class="form-control select2" name="semester" id="semester"">-->
<!--                                                        <option value=""> -- Select semester --</option>-->
<!--                                                        </select>-->
<!--                                                    </div>-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label>Class</label><br/>-->
<!--                                                        <select class="form-control select2" name="class" id="class"-->
<!--                                                                onchange="getSections()">-->
<!--                                                            <option value=""> -- Select class --</option>-->
<!--                                                        </select>-->
<!--                                                    </div>-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label>Section</label><br/>-->
<!--                                                        <select class="form-control select2" name="section" id="section">-->
<!--                                                            <option value=""> -- Select section --</option>-->
<!--                                                        </select>-->
<!--                                                    </div>-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label>Description <span class="text-danger">*</span></label><br/>-->
<!--                                                        <input type="text" class="form-control" name="description" required/>-->
<!--                                                    </div>-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label>Amount <span class="text-danger">*</span></label><br/>-->
<!--                                                        <input type="number" min="0" class="form-control" name="amount" required/>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                                <div class="modal-footer">-->
<!--                                                    <button type="submit" class="btn btn-success">Save</button>-->
<!--                                                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close-->
<!--                                                    </button>-->
<!--                                                </div>-->
<!--                                            </form>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
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
                    No fees entries found
                </div>
            </div>
            <?php
        }
        ?>

    </div>
</div>
<script>
    var getClass = function () {
        var session = $('#session').val();
        if (session == '') {
            toast('Error', 'Please select a Session', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/session/') ?>" + session + "/classes",
                data: "session=" + session,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#class').html(data);
                // $('#class').each(function (e) {
                //     e.html(data);
                // })
            });
            data.url = "<?php echo site_url('ajax/session/') ?>" + session + "/semesters";
            ajaxRequest(data, function (data) {
                $('#semester').html(data);
                // $('#semester').each(function (e) {
                //     e.html(data);
                // })
            });
        }
    };

    var getSections = function () {
        var classId = $('#class').val();
        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#section').html(data);
            });
        }
    };

    var getStudents = function () {
        var session = $('#session').val();
        var classId = $('#class').val();
        var sectionId = $('#section').val();
        if (classId == '' || session == '' || sectionId == '') {
            toast('Error', 'Please make sure all filter fields are selected', 'error');
        } else {
            var data = {
                url: "<?php echo site_url('ajax/students/') ?>" + session + "/class/" + classId + "/section/" + sectionId,
                data: "session=" + session + "&class=" + classId + "&section=" + sectionId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ajaxData').html(data);
            });
        }
    }
</script>