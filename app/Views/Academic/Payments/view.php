<?php


?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php echo date("F", strtotime('01-' . $payment->payment_month . '-2001')). ' '. $site_title; ?></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target=".new_payment">New Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <h4>Fee of <?php echo fee_currency($payment->amount); ?>, Deadline <?php echo $payment->deadline; ?></h4>
        </div>
        <div class="card-body">
            <?php
            //d($payment);
            $students = $class->students;
            if($students && is_array($students) && count($students) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="payments_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Adm. No</th>
                                <th>Amount</th>
<!--                                <th>Penalty</th>-->
                                <th>Total</th>
                                <th>Deposit Slip</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($students as $student) {
                            $is_paid = (new \App\Models\PaymentSubmission())
                                ->where('student', $student->id)
                                ->where('class', $class->id)
                                ->where('payment', $payment->id)
                                ->where('status', 1)
                                ->get()->getLastRow('\App\Entities\PaymentSubmission');

                            $req = (new \App\Models\PaymentSubmission())
                                ->where('student', $student->id)
                                ->where('class', $class->id)
                                ->where('payment', $payment->id)
                                ->get()->getLastRow('\App\Entities\PaymentSubmission');
                            $n++;
//                            $penalty = 0;
//                            $daysDiff = $payment->daysPastDeadline($student->id);
//                            if ($daysDiff > 0) {
//                                $penalty = $daysDiff*get_option('payments_penalty', 10);
//                            }
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $student->profile->name; ?></td>
                                <td><?php echo $student->admission_number; ?></td>
                                <td><?php echo number_format($payment->amount, 2); ?></td>
<!--                                <td>--><?php //echo @number_format($penalty, 2); ?><!--</td>-->
                                <td><?php echo @number_format($payment->amount, 2); ?></td>
                                <td><?php
                                    if($req) {
                                        echo $req->deposit_slip && file_exists($req->slipPath) ? '<a href="'.site_url(route_to('admin.payments.download_slip', $req->id)).'" class="btn btn-sm btn-primary">Download Slip</a>' : '<span class="badge badge-danger">Unavailable</span>';
                                    } else {
                                        echo '<span class="badge badge-danger">Unavailable</span>';
                                    }
                                    ?></td>
                                <td><?php
                                    if($is_paid) {
                                        echo $is_paid->status == 1 ? '<span class="badge badge-success">Paid</span>' : '<span class="badge badge-danger">Unpaid</span>';
                                    } else {
                                        echo '<span class="badge badge-danger">Unpaid</span>';
                                    }
                                    ?></td>
                                <td>
                                    <?php if (!$is_paid):?>
                                    <a class="btn btn-sm btn-success" href="<?php echo site_url(route_to('admin.payments.clear_payment', $payment->id, $student->id)); ?>">Clear Payment</a>
                            <?php endif;?>
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
                    No students found
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

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
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5 ]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5 ]
                    }
                },
            ],
        });
    })
</script>