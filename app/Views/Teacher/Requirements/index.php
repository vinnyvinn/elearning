<?php
$sections = (new \App\Models\Subjectteachers())->groupBy('section_id')->orderBy('id')->where('teacher_id', $teacher->id)->findAll();
$advisor_sections = (new \App\Models\Sections())->orderBy('id')->where('advisor', $teacher->id)->where('session',active_session())->findAll();

$sections_array = array();
$section_ids = array();

foreach ($sections as $section){
    if (!isset($sections_array[$section->section_id])){
     $sections_array[$section->section_id] = $section->section;
      array_push($section_ids,$section->section_id);
    }
}

foreach ($advisor_sections as $section){
    if (!isset($sections_array[$section->id])){
        $sections_array[$section->id] = $section;
        array_push($section_ids,$section->id);
    }
}
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
              <div class="col-lg-6 col-7">
               <h6 class="h2 text-white d-inline-block mb-0">Requirements</h6>
               </div>
               <div class="col-lg-6 col-5 text-right">
              <button type="button" class="btn btn-sm btn-neutral" data-toggle="modal" data-target=".new_requirement">New Requirement</button>
              </div>
            </div>
            <div class="modal fade new_requirement"
                 role="dialog" aria-labelledby="modal-default"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                              action="<?php echo site_url(route_to('teacher.requirements.save')); ?>">
                            <input type="hidden" name="session" value="<?php echo active_session(); ?>" />
                            <div class="modal-header">
                                <h6 class="modal-title" id="modal-title-default">New Requirement</h6>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Class</label>
                                    <select name="section" id="ass_class_id" class="form-control form-control-sm select2"
                                            data-toggle="select2">
                                        <?php foreach ($sections_array as $section_):?>
                                         <option value="<?php echo $section_->id?>"><?php echo $section_->class->name.','.$section_->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input class="form-control" name="description" value="<?php echo old('description'); ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Item</label>
                                    <input class="form-control" name="item" value="<?php echo old('item'); ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Deadline</label>
                                    <input type="text" name="deadline" class="form-control datepicker" id="datepicker" data-toggle="datepicker" required value="<?php echo old('deadline'); ?>" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <button type="button" class="btn btn-link  ml-auto"
                                        data-dismiss="modal">Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <?php
            $selected_section = \Config\Services::request()->getGet('section');
            $selected_month = \Config\Services::request()->getGet('month');
            ?>
            <form>
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <select name="section" class="form-control form-control-sm">
                            <option value="all"> View All </option>
                            <?php foreach ($sections_array as $section):?>
                                <option value="<?php echo $section->id?>" <?php if (isset($selected_section) && $selected_section == $section->id):?>selected<?php endif;?>><?php echo $section->class->name.','.$section->name;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="month" class="form-control form-control-sm">
                            <option value="all"> View All</option>
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                ?>
                                <option <?php echo isset($selected_month) && $selected_month == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm btn-block">Filter</button>
                    </div>
                </div>
            </form>
            <hr/>
            <?php

            $model = new \App\Models\Requirements();
            $requirements = array();
            $model->whereIn('section',$section_ids);
            $model->where('session', active_session())->findAll();
            if($selected_section != '' && is_numeric($selected_section)) {
                $model->where('section', $selected_section);
            }
            if($selected_month != '' && is_numeric($selected_month)) {
                $selected_month = str_pad($selected_month, 2, '0', STR_PAD_LEFT);
                $model->like('deadline', $selected_month, 'left');
                //$model->where('payment_month', $selected_month);
            }
            if (isset($section_ids) && is_array($section_ids)){
            $model->orderBy('id', 'DESC');
            $model->whereIn('section',$section_ids);
            $requirements = $model->findAll();
            }
            if($requirements && count($requirements) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="requirements_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Description</th>
                            <th>Item</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($requirements as $requirement) {
                            $n++;
                            ?>
                             <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $requirement->class ? $requirement->class->name : 'All Classes'; ?></td>
                                <td><?php echo $requirement->section ? $requirement->section->name : 'All Sections'; ?></td>
                                <td><?php echo $requirement->description; ?></td>
                                <td><?php echo $requirement->item; ?></td>
                                <td><?php echo $requirement->deadline; ?></td>
                                <td>
                                    <?php if (isset( $requirement->class->id)):?>
                                    <a class="btn btn-sm btn-primary" href="<?php echo site_url(route_to('teacher.requirements.view_requirement', $requirement->id,  $requirement->class->id,$requirement->section->id)); ?>">View</a>
                                    <?php endif;?>
                                   <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".edit<?php echo $requirement->id; ?>">Edit</button>
                                    <div class="modal fade edit<?php echo $requirement->id; ?>"
                                         role="dialog" aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                                      action="<?php echo site_url(route_to('teacher.requirements.save')); ?>">
                                                    <input type="hidden" name="id" value="<?php echo $requirement->id; ?>" />
                                                    <input type="hidden" name="session" value="<?php echo old('session', $requirement->session); ?>" />
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Edit Requirement</h6>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Class</label>
                                                            <select name="section" id="ass_class_id" class="form-control form-control-sm select2"
                                                                    data-toggle="select2">
                                                                <?php foreach ($sections_array as $sec):?>
                                                                    <option value="<?php echo $sec->id?>" <?php if ($sec->id == $requirement->section):?>selected<?php endif;?>><?php echo $sec->class->name.','.$sec->name;?></option>
                                                                <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <input class="form-control" name="description" value="<?php echo old('description', $requirement->description); ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Item</label>
                                                            <input class="form-control" name="item" value="<?php echo old('item', $requirement->item); ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Deadline</label>
                                                            <input type="text" name="deadline" class="form-control datepicker" id="datepicker" data-toggle="datepicker" required value="<?php echo old('deadline', $requirement->deadline); ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Save</button>
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
                    No requirements have been added
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<script>
    function getSections(classId) {
        if(classId == '') {

        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#section_id').html(data);
            });

        }
    }
    function getAnySections(classId) {
        if(classId == '') {

        } else {
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/sections",
                data: "session=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#ass_section_id').html(data);
            });

        }
    }
</script>
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
