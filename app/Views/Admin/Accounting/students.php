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
                    <th>Current Class</th>
                    <th>Current Section</th>
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
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target=".record<?php echo $student->id; ?>"> Record Fee </button>
                            <div class="modal fade record<?php echo $student->id; ?>" style="z-index: 9998" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                                 style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                    <div class="modal-content">
                                        <form class="ajaxForm" loader="true" method="post"
                                              action="<?php echo site_url(route_to('admin.accounting.fee.add_collection', $student->id)); ?>">
                                            <input type="hidden" name="session" value="<?php echo active_session(); ?>" />
                                            <input type="hidden" name="student" value="<?php echo $student->id; ?>" />
                                            <div class="modal-header pb-0">
                                                <h6 class="modal-title" id="modal-title-default">New Fee Collection</h6>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body bg-gradient-green text-white">
                                                Record fee payment for <b><?php echo $student->profile->name; ?></b> of Admission Number <b><?php echo $student->admission_number; ?></b>
                                            </div>
                                            <div class="modal-body pb-0">
                                                <div class="form-group"><br/>
                                                    <label>Payment Note</label>
                                                    <input type="text" class="form-control" name="description" />
                                                </div>
                                                <div class="form-group"><br/>
                                                    <label>Payment Date <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="date" value="<?php echo date('m/d/Y'); ?>" required />
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
            No students were found for this class section
        </div>
    </div>
    <?php
}
