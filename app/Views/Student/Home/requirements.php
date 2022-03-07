<?php
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Requirements</h6>
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
            <div id="requirements" class="card">
                <div class="card-header">
                    General Requirement: <b><?php echo $student->profile->name; ?></b>
                </div>
                <?php
                if($student->requirements && count($student->requirements) > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Requirement</th>
                                <th>Item</th>
                                <th>Deadline</th>
                                <th>Parent Check</th>
                                <th>Parent Comment</th>
                                <th>Teacher Check</th>
                                <th>Teacher Comment</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($student->requirements as $payment) {
                                $n++;
                                $db = \Config\Database::connect();
                                $builder = $db->table('requirements_submissions');
                                $builder->where('session',active_session());
                                $builder->where('student',$student->id);
                                $builder->where('requirement',$payment->id);
                                $req = $builder->get()->getRow();
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $payment->description; ?></td>
                                    <td><?php echo $payment->item; ?></td>
                                    <td><?php echo $payment->deadline; ?></td>
                                    <td>
                                        <?php if ($req && $req->parent_check ==1):?>
                                            <i class="fa fa-check fa-2x text-red"></i>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <?php echo isset($req->parent_comment) ? limit_str_by30($req->parent_comment) : '' ; ?>
                                    </td>
                                    <td>
                                        <?php if ($req && $req->teacher_check ==1):?>
                                            <i class="fa fa-check fa-2x text-red"></i>
                                        <?php endif;?>
                                    </td>
                                    <td> <?php echo isset($req->teacher_comment) ? limit_str_by30($req->teacher_comment) : '' ; ?></td>
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
                            This student has no general requirements
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

</div>
