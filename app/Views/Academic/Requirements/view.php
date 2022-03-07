<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-sm btn-neutral d-none" data-toggle="modal" data-target=".new_payment">New Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <h4>Requirements</h4>
        </div>
        <div class="card-body">
            <?php
            $students = $class->students;
            if($students && is_array($students) && count($students) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="requirements_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Adm. No</th>
                                <th>Parent Check</th>
                                <th>Parent Comment</th>
                                <th>Teacher Check</th>
                                <th>Teacher Comment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($students as $student) {
                            $n++;

                            $db = \Config\Database::connect();
                            $builder = $db->table('requirements_submissions');
                            $builder->where('session',active_session());
                            $builder->where('student',$student->id);
                            $builder->where('requirement',$requirement->id);
                            $req = $builder->get()->getRow();
                            ?>

                             <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $student->profile->name; ?></td>
                                <td><?php echo $student->admission_number; ?></td>
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
                                 <td><?php echo isset($req->teacher_comment) ? limit_str_by30($req->teacher_comment) : '';?></td>
                                 <td>
                                     <?php if (empty($req) || !isset($req->teacher_check) || $req->teacher_check==0):?>
                                     <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target=".edit<?php echo $student->id; ?>">Mark Checked</button>
                                     <?php endif;?>
                                     <div class="modal fade edit<?php echo $student->id; ?>"
                                          role="dialog" aria-labelledby="modal-default"
                                          style="display: none;" aria-hidden="true">
                                         <div class="modal-dialog modal-dialog-centered" role="document">
                                             <div class="modal-content">
                                                 <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                                       action="<?php echo site_url(route_to('admin.academic.update_teacher_comment')); ?>">
                                                     <input type="hidden" name="student" value="<?php echo $student->id; ?>" />
                                                     <input type="hidden" name="requirement" value="<?php echo $requirement->id; ?>" />
                                                     <div class="modal-header">
                                                         <h6 class="modal-title" id="modal-title-default">Teacher Comment</h6>
                                                         <button type="button" class="close" data-dismiss="modal"
                                                                 aria-label="Close">
                                                             <span aria-hidden="true">×</span>
                                                         </button>
                                                     </div>
                                                     <div class="modal-body">
                                                         <div class="form-group">
                                                           <label>Comment</label>
                                                           <textarea name="teacher_comment" id="teacher_comment" cols="10" rows="3" class="form-control"></textarea>
                                                         </div>
                                                     </div>
                                                     <div class="modal-footer">
                                                         <button type="submit" class="btn btn-success">Mark Checked</button>
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
        $('#requirements_table').dataTable({
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