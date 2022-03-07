<?php
$subjects = $dept->subjects;
?>
<div>
    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target=".subjects" >Manage Department Subjects</button>
    <div class="modal fade subjects" tabindex="-1" role="dialog" aria-labelledby="modal-default"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                <form class="ajaxForm" loader="true" method="post" data-parsley-validate=""
                      action="<?php echo site_url(route_to('admin.registration.departments.manage_subjects')); ?>">
                    <input type="hidden" name="dept" value="<?php echo $dept->id; ?>" />
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">New Department</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sess">Select Subjects</label><br/>
                            <?php
                            $ss = (new \App\Models\Subjects())->whereNotIn('id', function (\CodeIgniter\Database\BaseBuilder $builder){
                                return $builder->select('subject')->from('subject_departments');
                            })->findAll();

                            if($ss && count($ss) > 0) {
                                foreach ($ss as $s) {
                                    ?>
                                    <label><input type="checkbox" name="subjects[]" value="<?php echo $s->id ?>" /> <?php echo $s->name; ?></label><br/>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-warning">
                                    No subjects can be added to this department at the moment
                                </div>
                                <?php
                            }
                            ?>
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
    <?php
    if($subjects && count($subjects) > 0) {
        ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($subjects as $subject) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $subject->subject->name; ?></td>
                        <td>
                    <?php if (isSuperAdmin()):?>
                            <a class="btn btn-sm btn-danger send-to-server-click" href="<?php echo site_url(route_to('admin.registration.departments.remove_subject', $subject->id)); ?>" url="<?php echo site_url(route_to('admin.registration.departments.remove_subject', $subject->id)); ?>" data="action:delete|id:<?php echo $subject->id; ?>" loader="true" warning-title="Confirm" warning-message="Are you sure you want to remove this subject from this department?"><i class="fa fa-trash-alt"></i></a>
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
        <hr class="mt-3 mb-3"/>
        <div class="alert alert-warning">
            No subjects added to this department
        </div>
        <?php
    }
    ?>
</div>
