<div class="card">
    <div class="card-header">
        <h3 class="h3 card-title mb-0">Payments Required</h3>
    </div>
    <div class="card-body">
        <?php
        $payments = $class->payments;
        if($payments && count($payments) > 0) {
            ?>
            <div class="table-responsive mt-2">
                <table class="table" id="datatable-basic">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($payments as $payment) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $payment->class ? $payment->class->name : 'All Classes'; ?></td>
                            <td><?php echo $payment->section ? $payment->section->name : 'All Sections'; ?></td>
                            <td><?php echo $payment->description; ?></td>
                            <td><?php echo fee_currency($payment->amount); ?></td>
                            <td><?php echo $payment->deadline; ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".edit<?php echo $payment->id; ?>">Edit</button>
                        <?php if (isSuperAdmin()):?>
                                <a href="<?php echo site_url(route_to('admin.academic.delete_payment', $payment->id)); ?>" class="btn btn-sm btn-danger send-to-server-click" url="<?php echo site_url(route_to('admin.academic.delete_payment', $payment->id)); ?>" data="action:delete|id:<?php echo $payment->id; ?>" loader="true" warning-title="Delete Payment" warning-message="Are you sure you want to delete this payment?">Delete</a>
                        <?php endif?>
                                <div class="modal fade edit<?php echo $payment->id; ?>"
                                     role="dialog" aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                                  action="<?php echo site_url(route_to('admin.academic.save_payment')); ?>">
                                                <input type="hidden" name="id" value="<?php echo $payment->id; ?>" />
                                                <input type="hidden" name="session" value="<?php echo old('session', $payment->session); ?>" />
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modal-title-default">Edit Payment</h6>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Class</label><br/>
                                                        <select name="class" id="ass_class_id" class="form-control form-control-sm select2"
                                                                data-toggle="select2"
                                                                onchange="getAnySections($(this).val())">
                                                            <option value="">Select class</option>
                                                            <option value="">All Classes</option>
                                                            <?php
                                                            $classes = getSession()->classes()->findAll();

                                                            foreach ($classes as $class) {
                                                                ?>
                                                                <option <?php echo ($payment->class && $payment->class->id == $class->id) ? 'selected' : ''; ?> value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Section</label><br/>
                                                        <select name="section" id="ass_section_id" class="form-control form-control-sm select2"
                                                                data-toggle="select2" >
                                                            <option value="">Select Section</option>

                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <input class="form-control" name="description" value="<?php echo old('description', $payment->description); ?>" required />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="number" min="0" class="form-control" name="amount" value="<?php echo old('amount', $payment->amount); ?>" required />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Deadline</label>
                                                        <input type="text" name="deadline" class="form-control datepicker" id="datepicker" data-toggle="datepicker" required value="<?php echo old('deadline', $payment->deadline); ?>" />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Save</button>
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
            <div class="alert alert-warning">
                No payments have been added
            </div>
            <?php
        }
        ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="h3 card-title mb-0">Class Requirements</h3>
    </div>
    <div class="card-body">
        <?php
        $requirements = $class->requirements;
        if($requirements && count($requirements) > 0) {
            ?>
            <div class="table-responsive mt-2">
                <table class="table" id="datatable-basic">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($requirements as $requirement) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $requirement->class ? $requirement->class->name : 'All Classes'; ?></td>
                            <td><?php echo $requirement->section ? $requirement->section->name : 'All Sections'; ?></td>
                            <td><?php echo $requirement->description; ?></td>
                            <td><?php echo $requirement->item; ?></td>
                            <td><?php echo $requirement->deadline; ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".edit<?php echo $requirement->id; ?>">Edit</button>
                                <a href="<?php echo site_url(route_to('admin.academic.delete_requirement', $requirement->id)); ?>" class="btn btn-sm btn-danger send-to-server-click" url="<?php echo site_url(route_to('admin.academic.delete_requirement', $requirement->id)); ?>" data="action:delete|id:<?php echo $requirement->id; ?>" loader="true" warning-title="Delete Requirement" warning-message="Are you sure you want to delete this requirement?">Delete</a>
                                <div class="modal fade edit<?php echo $requirement->id; ?>"
                                     role="dialog" aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                                  action="<?php echo site_url(route_to('admin.academic.save_requirement')); ?>">
                                                <input type="hidden" name="id" value="<?php echo $requirement->id; ?>" />
                                                <input type="hidden" name="session" value="<?php echo old('session', $requirement->session); ?>" />
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modal-title-default">Edit Requirement</h6>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Class</label><br/>
                                                        <select name="class" id="ass_class_id" class="form-control form-control-sm select2"
                                                                data-toggle="select2"
                                                                onchange="getAnySections($(this).val())">
                                                            <option value="">Select class</option>
                                                            <option value="">All Classes</option>
                                                            <?php
                                                            $classes = getSession()->classes()->findAll();

                                                            foreach ($classes as $class) {
                                                                ?>
                                                                <option <?php echo ($requirement->class && $requirement->class->id == $class->id) ? 'selected' : ''; ?> value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Section</label><br/>
                                                        <select name="section" id="ass_section_id" class="form-control form-control-sm select2"
                                                                data-toggle="select2" >
                                                            <option value="">Select Section</option>

                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <input class="form-control" name="description" value="<?php echo old('description', $requirement->description); ?>" required />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Item</label>
                                                        <input class="form-control" name="item" value="<?php echo old('item', $requirement->item); ?>" required />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Deadline</label>
                                                        <input type="text" name="deadline" class="form-control datepicker" id="datepicker" data-toggle="datepicker" required value="<?php echo old('deadline', $requirement->deadline); ?>" />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Save</button>
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
            <div class="alert alert-warning">
                No requirements have been added
            </div>
            <?php
        }
        ?>
    </div>
</div>