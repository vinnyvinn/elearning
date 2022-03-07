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
                        <td><?php echo fee_currency($fee->amount); ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr style="border-top: 2px solid gray">
                    <th colspan="2" class="pull-right">Total Fees</th>
                    <td><?php echo fee_currency($student->getTotalFees()); ?></td>
                </tr>
                <tr>
                    <th colspan="2">Total Paid</th>
                    <td><?php echo fee_currency($student->getPaidFees()); ?></td>
                </tr>
                <tr>
                    <th colspan="2">Balance</th>
                    <td><?php echo fee_currency($student->getTotalFees()-$student->getPaidFees()); ?></td>
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
