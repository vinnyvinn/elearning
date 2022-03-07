<?php
$selected_class = \Config\Services::request()->getGet('class');
$selected_month = \Config\Services::request()->getGet('month');
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Payments</h6>
                    <a href="<?php echo base_url("admin/academic/payments-excel?class=$selected_class&month=$selected_month");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo base_url("admin/academic/payments-pdf?class=$selected_class&month=$selected_month"); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo base_url("admin/academic/payments-print?class=$selected_class&month=$selected_month");?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target=".new_payment">New Payment</button>
                </div>
            </div>
            <div class="modal fade new_payment"
                 role="dialog" aria-labelledby="modal-default"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                              action="<?php echo site_url(route_to('admin.academic.save_payment')); ?>">
                            <input type="hidden" name="session" value="<?php echo active_session(); ?>" />
                            <div class="modal-header">
                                <h6 class="modal-title" id="modal-title-default">New Payment</h6>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Class</label>
                                    <select name="class" id="ass_class_id" class="form-control form-control-sm select2"
                                            data-toggle="select2"
                                            onchange="getSections($(this).val())" required>
                                        <option value="">Select class</option>
                                        <option value="">All Classes</option>
                                        <?php
                                        $classes = getSession()->classes()->findAll();

                                        foreach ($classes as $class) {
                                            echo '<option value="' . $class->id . '">' . $class->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group hidden" style="display: none">
                                    <label>Section</label>
                                    <select name="section" id="section_id" class="form-control form-control-sm select2"
                                            data-toggle="select2" >
                                        <option value="">Select Section</option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input class="form-control" name="description" value="<?php echo old('description'); ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" min="0" class="form-control" name="amount" value="<?php echo old('amount'); ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Payment Month</label>
                                    <select name="payment_month" class="form-control" required >
                                        <option value="">-- Please select --</option>
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) {
                                            ?>
                                            <option <?php echo old('payment_month') == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Deadline</label>
                                    <input type="text" name="deadline" class="form-control datepicker" id="datepicker" data-toggle="datepicker" required value="<?php echo old('deadline'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="sms_notification" value="1" /> Send SMS Notification of this payment to parents?
                                    </label>
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
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <form>
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <select name="class" class="form-control form-control-sm">
                            <option value="all"> View All</option>
                            <?php
                            $school_session = getSession();
                            if($school_session) {
                                $classes = $school_session->classes()->findAll();

                                if($classes && is_array($classes) && count($classes) > 0) {
                                    foreach ($classes as $class) {
                                        ?>
                                        <option <?php echo isset($selected_class) && $selected_class == $class->id ? 'selected' : ''; ?> value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                    <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="month" class="form-control form-control-sm">
                            <option value="all"> View All</option>
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                ?>
                                <option <?php echo isset($selected_month) && $selected_month == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm btn-block">Filter</button>
                    </div>
                </div>
            </form>
            <hr/>
            <?php
            $model = (new \App\Models\Payments())->where('session', active_session());
                if($selected_class != '' && is_numeric($selected_class)) {
                    $model->where('class', $selected_class);
                }
                if($selected_month != '' && is_numeric($selected_month)) {
                    //$selected_month = str_pad($selected_month, 2, '0', STR_PAD_LEFT);
                    //$model->like('deadline', $selected_month, 'left');
                    $model->where('payment_month', $selected_month);
                }
            $model->orderBy('id', 'DESC');
            $payments = $model->findAll();
            if($payments && count($payments) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="payments_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Class</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Payment Month</th>
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
                                <td><?php echo $payment->description; ?></td>
                                <td><?php echo fee_currency($payment->amount); ?></td>
                                <td><?php echo isset($payment->payment_month) ? date("F", strtotime('01-' . $payment->payment_month . '-2001')) : '-'; ?></td>
                                <td><?php echo $payment->deadline; ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('admin.academic.view_payment', $payment->id, $payment->class->id)); ?>">View</a>
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".edit<?php echo $payment->id; ?>">Edit</button>
                            <?php if (isSuperAdmin()):?>
                                    <a href="<?php echo site_url(route_to('admin.academic.delete_payment', $payment->id)); ?>" class="btn btn-sm btn-danger send-to-server-click" url="<?php echo site_url(route_to('admin.academic.delete_payment', $payment->id)); ?>" data="action:delete|id:<?php echo $payment->id; ?>" loader="true" warning-title="Delete Payment" warning-message="Are you sure you want to delete this payment?">Delete</a>
                                 <?php endif;?>
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
</div>
<script>
    function getSections(classId) {
        if(classId == '') {

        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#section_id').html(data);
            });

        }
    }
    function getAnySections(classId) {
        if(classId == '') {

        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ass_section_id').html(data);
            });

        }
    }
</script>
<script>
    $(document).ready(function () {
        $('#payments_table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5]
                    }
                },
                // {
                //     extend: 'excel',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3,4,5 ]
                //     }
                // },
                // {
                //     extend: 'pdf',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3,4,5 ]
                //     }
                // },
                // {
                //     extend: 'print',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3,4,5 ]
                //     }
                // },
            ],
        });
    })
</script>