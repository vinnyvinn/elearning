<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Fee Payment Log</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('student_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <?php
        if($student->feePayment && count($student->feePayment) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $n = 0;
                    foreach ($student->feePayment as $payment) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $payment->date; ?></td>
                            <td><?php echo fee_currency($payment->amount); ?></td>
                            <td><?php echo $payment->description; ?></td>
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
                    There are no fee payment logs for this student
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>