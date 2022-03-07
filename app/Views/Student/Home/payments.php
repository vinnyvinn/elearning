<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Payments</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('parent_quick_action_buttons', $student); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
            <div class="card">
                <div class="card-header">
                    Payments Required: <b><?php echo $student->profile->name; ?></b>
                </div>
                <?php
                if($student->payments && count($student->payments) > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Requirement</th>
                                    <th>Amount</th>
                                    <th>Deadline</th>
                                    <th>Deposit Slip</th>
                                    <th>Reference No.</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            $amount = 0;
                            foreach ($student->payments as $payment) {
                                $is_paid = (new \App\Models\PaymentSubmission())
                                    ->where('student', $student->id)
                                    ->where('class', $student->class->id)
                                    ->where('payment', $payment->id)
                                    ->get()->getLastRow('\App\Entities\PaymentSubmission');
                                $n++;
                                $amount +=$payment->amount;
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $payment->description; ?></td>
                                    <td><?php echo fee_currency($amount); ?></td>
                                    <td><?php echo $payment->deadline; ?></td>
                                    <td><?php
                                        //d($is_paid->slipPath);
                                        if(@$is_paid) {
                                            echo $is_paid->deposit_slip && file_exists($is_paid->slipPath) ? '<a href="'.site_url(route_to('parent.payments.download_slip', $is_paid->id)).'" class="btn btn-sm btn-info">Download Slip</a>' : '<span class="badge badge-danger">Unavailable</span>';
                                        } else {
                                            ?>
                                            <button type="button" class="btn btn-primary btn-sm d-none" data-toggle="modal" data-target=".upload_slip_<?php echo $payment->id; ?>">Upload Deposit Slip</button>
                                            <div class="modal fade upload_slip_<?php echo $payment->id; ?>" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form class="ajaxForm" loader="true" method="post" data-parsley-validate="" enctype="multipart/form-data" action="<?php echo site_url(route_to('parent.payments.upload_slip', $payment->id)) ?>" novalidate="">
                                                            <input type="hidden" name="student" value="<?php echo $student->id; ?>">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title" id="modal-title-default">Upload Deposit Slip</h6>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Upload Slip <span class="text-danger"></span></label>
                                                                    <input type="file" name="slip" class="form-control-file" id="datepicker"   accept="img/*,.pdf,.doc,.docx">
                                                                    <small class="text-center">Accepted File: Images, PDF, 5MB max file size</small>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Reference Number</label>
                                                                    <input type="text" class="form-control" name="reference" />
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Upload</button>
                                                                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?></td>
                                    <td><?php echo @$is_paid ? $is_paid->reference : ''; ?></td>
                                    <td><?php
                                        if(@$is_paid) {
                                            echo $is_paid->status == 1 ? '<span class="badge badge-success">Paid</span>' : '<span class="badge badge-danger">Unpaid</span>';
                                        } else {
                                            echo '<span class="badge badge-danger">Unpaid</span>';
                                        }
                                        ?></td>
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
                        <div class="alert alert-warning">
                            This student has no required payments
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
</div>
