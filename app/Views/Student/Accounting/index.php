<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Student Fee</h6>
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
        if($student->fees && count($student->fees) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($student->fees as $fee) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $fee->description; ?></td>
                            <td><?php echo fee_currency($fee->amount, 2); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr style="border-top: 2px solid gray">
                        <th colspan="2" class="pull-right">Total Fees</th>
                        <td><?php echo fee_currency($student->getTotalFees(), 2); ?></td>
                    </tr>
                    <tr class="table-success">
                        <th colspan="2">Total Paid</th>
                        <td><?php echo fee_currency($student->getPaidFees(), 2); ?></td>
                    </tr>
                    <tr class="table-warning">
                        <th colspan="2">Balance</th>
                        <td><?php echo fee_currency($student->getTotalFees()-$student->getPaidFees(), 2); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            ?>
            <div class="card-body">
                <div class="alert alert-warning">
                    No fees added for this student
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
