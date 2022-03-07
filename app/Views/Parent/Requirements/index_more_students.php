<?php
$students = $parent->studentsCurrent;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Requirements </h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php
                    do_action('parent_quick_action_buttons', $parent); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card" style="margin-bottom: 5%">
    <div class="card-header">
        <h4 class="card-title">Requirements</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <ul class="nav nav-pills nav-pill-bordered">
                <?php
                $active = $students[0];
                foreach ($students as $student):?>
                <li class="nav-item">
                  <a class="walla nav-link <?php if ($active->id == $student->id):?>active<?php endif;?>" id="base-pill<?php echo $student->id;?>" data-toggle="pill" href="#pill<?php echo $student->id;?>" aria-expanded="true"><?php echo $student->profile->name;
                  echo '<br>';
                  echo $student->class->name;
                  echo '<br>';
                  echo $student->admission_number;
                  ?></a>
                </li>
                <?php endforeach;?>

            </ul>
            <div class="tab-content px-1 pt-1">
                <?php
                foreach ($students as $student):?>
                <div role="tabpanel" class="tab-pane <?php if ($active->id == $student->id):?>active<?php endif;?>" id="pill<?php echo $student->id;?>" aria-expanded="true" aria-labelledby="base-pill<?php echo $student->id;?>">
                       <div class="card">
                        <div class="card-header">
                            General Requirement: <b><?php echo $student->profile->name; ?></b>
                        </div>
                        <?php
                        if($student->requirements && count($student->requirements) > 0) {
                            ?>
                            <div class="table-responsive" id="requirement">
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
                                        <th>Actions</th>
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
                                            <td>
                                                <?php if (empty($req) || !isset($req->parent_check) || $req->parent_check==0):?>
                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target=".mark_checked_<?php echo $payment->id.'_'.$student->id;?>">Mark Checked</button>
                                                <?php endif;?>
                                                <div class="modal fade mark_checked_<?php echo $payment->id.'_'.$student->id; ?>" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <form class="ajaxFor" loader="true" method="post" data-parsley-validate="" enctype="multipart/form-data" action="<?php echo site_url(route_to('parent.requirements.mark_checked', $payment->id)) ?>" novalidate="">
                                                                <input type="hidden" name="student" value="<?php echo $student->id; ?>">
                                                                <input type="hidden" name="requirement" value="<?php echo $payment->id; ?>">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title" id="modal-title-default">Parent Comment</h6>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>Comment</label>
                                                                        <textarea rows="3" class="form-control" name="parent_comment"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">Mark Checked</button>
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
                                <div class="alert alert-warning">
                                    This student has no general requirements
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(function (){
        moveWindow();
    })
    $('.walla').on('click',function (){
        moveWindow();
    })

    function moveWindow(){
        const r = document.getElementById('requirement');
        r.scrollIntoView();
    }
</script>