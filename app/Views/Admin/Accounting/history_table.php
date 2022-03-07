<?php

use App\Models\Sessions;

if($students && count($students) > 0) {
    ?>

        <div class="table-responsive">
            <table class="table datatable" id="datatable-basic">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Admission #</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Total Fee</th>
                    <th>Paid Fee</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($students as $student) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $student->admission_number; ?></td>
                        <td><?php echo $student->profile->name; ?></td>
                        <td><?php echo $student->class->name; ?></td>
                        <td><?php echo $student->section->name; ?></td>
                        <td><?php echo fee_currency($student->getTotalFees()); ?></td>
                        <td><span class="text-warning"><?php echo fee_currency($student->getPaidFees()); ?></span></td>
                        <td>
                            <?php
                            $total = $student->getTotalFees();
                            $paid = $student->getPaidFees();
                            $balance = $total-$paid;
                            if($balance > 0) {
                                $color = 'danger';
                            } else {
                                $color = 'success';
                            }
                            echo '<span class="text-'.$color.'">'.fee_currency($balance).'</span>';
                            ?>
                        </td>
                        <td><a href="#!" class="btn btn-sm btn-primary">View</a></td>
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
            No students were found for this class section
        </div>
    </div>
    <?php
}
