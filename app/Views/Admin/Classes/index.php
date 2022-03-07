<div class="header bg-primary pb-6">
    <style>
        .show{
            display: inline;
        }
        .hide{
            display: none;
        }
    </style>
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Classes</h6>
                    <a href="<?php echo site_url(route_to("admin.registration.classes.excel"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo site_url(route_to('admin.registration.classes.pdf')); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo site_url(route_to("admin.registration.classes.print"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-lg-6 col-5 text-right">

                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_class"><i
                                class="fa fa-plus"></i> New Class
                    </button>
                    <?php do_action('classes_quick_action_buttons'); ?>
                </div>
                <div class="modal fade new_class" tabindex="-1" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div    class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxForm" loader="true" method="post" data-parsley-validate=""
                                  action="<?php echo site_url(route_to('admin.school.classes.create')); ?>">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Class</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="sess">Class Name</label>
                                        <input type="text" class="form-control" name="name"
                                               value="<?php echo old('name') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Class Pass Mark</label>
                                        <input type="number" class="form-control" name="pass_mark"
                                               value="<?php echo old('pass_mark') ?>" required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sess">Session</label><br/>
                                        <select class="form-control select2" data-toggle="select2" name="session" required>
                                            <option>Please select</option>
                                            <?php
                                            $sessions = (new \App\Models\Sessions())->orderBy('id', 'DESC')->findAll();
                                            if($sessions && is_array($sessions)) {
                                                foreach ($sessions as $session) {
                                                    ?>
                                                    <option <?php echo active_session() == $session->id ? 'selected' : ''; ?> value="<?php echo $session->id; ?>"><?php echo $session->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Class Number</label>
                                        <input type="number" name="weight" value="<?php echo old('pass_mark') ?>" required class="form-control">
                                    </div>
                                <div class="form-group">
                                    <label>Kindergarten ?</label><br/>
                                    <label class="custom-toggle">
                                        <input type="checkbox" name="type"
                                               value="1" <?php echo old('type') == 'kg' ? 'checked' : ''; ?> id="kind"/>
                                        <span class="custom-toggle-slider rounded-circle"
                                              data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>
                                    <div class="form-group grading" style="display: none;">
                                        <h3>Grading System</h3>
                                        <div class="table-responsive">
                                            <table class="table" id="filesTable">
                                                <thead>
                                                <tr>
                                                    <th>Grade</th>
                                                    <th>Scale</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody id="tableBody">
                                                <tr>
                                                    <td>
                                                        <input type="text" name="grade[]" class="form-control"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="scale[]" class="form-control"/>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-default btn-sm" id="addFile"><i class="fa fa-plus"></i> Add Row</button>
                                        <br/><br/>
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
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <?php
        if($classes && count($classes) > 0 ) {
            ?>
            <div class="table-responsive pt-2">
                <table class="table" id="classes-datatable">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Session</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach($classes as $class) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $class->name; ?></td>
                            <td><?php echo @(new \App\Models\Sessions())->find($class->session)->name; ?></td>
                            <td>
                                <a class="btn btn-success btn-sm" href="<?php echo site_url(route_to('admin.classes.view', $class->id)); ?>">View Class</a>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target=".edit_<?php echo $class->id; ?>"><i class="fa fa-edit"></i> Edit
                                </button>
                                <div class="modal fade edit_<?php echo $class->id; ?>" role="document"
                                     aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post"
                                                  action="<?php echo site_url(route_to('admin.school.classes.update', $class->id)); ?>">
                                                <input type="hidden" name="id" value="<?php echo $class->id; ?>" />
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modal-title-default">Update Class</h6>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group" style="display: grid">
                                                        <label for="sess">Class Name</label>
                                                        <input type="text" class="form-control" name="name"
                                                               value="<?php echo old('name', $class->name) ?>" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sess">Class Pass Mark</label>
                                                        <input type="number" class="form-control" name="pass_mark"
                                                               value="<?php echo old('pass_mark',$class->pass_mark) ?>" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sess">Session</label><br/>
                                                        <select class="form-control select2" data-toggle="select2" name="session" required>
                                                            <option>Please select</option>
                                                            <?php
                                                            $sessions = (new \App\Models\Sessions())->orderBy('id', 'DESC')->findAll();
                                                            if($sessions && is_array($sessions)) {
                                                                foreach ($sessions as $session) {
                                                                    ?>
                                                                    <option <?php echo old('session', $session->id) == $class->session ? 'selected' : ''; ?> value="<?php echo $session->id; ?>"><?php echo $session->name; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="weight">Class Number</label>
                                                        <input type="number" class="form-control" name="weight"
                                                               value="<?php echo old('weight',$class->weight) ?>" readonly/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kindergarten ?</label><br/>
                                                        <label class="custom-toggle">
                                                            <input type="checkbox" name="type"
                                                                   value="1" <?php echo $class->type == 'kg' ? 'checked' : ''; ?> id="kind" class="kind"/>
                                                            <span class="custom-toggle-slider rounded-circle"
                                                                  data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    </div>
                                                    <?php
                                                    $grading = $class->grading ? json_decode($class->grading) : '';
                                                    ?>
                                                    <?php if(!$grading || !is_array($grading)):?>
                                                    <div class="form-group grading <?php if ($class->type=='kg'):?>show <?php else:?> hide<?php endif;?>">
                                                        <h3>Grading System</h3>
                                                        <div class="table-responsive">
                                                            <table class="table" id="filesTableE">
                                                                <thead>
                                                                <tr>
                                                                    <th>Grade</th>
                                                                    <th>Scale</th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="tableBodyE" class="tableBodyE">
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" name="grade[]" class="form-control"/>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="scale[]" class="form-control"/>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <button type="button" class="btn btn-default btn-sm" id="addFileE"><i class="fa fa-plus"></i> Add Row</button>
                                                        <br/><br/>
                                                    </div>
                                                 <?php endif;?>

                                                    <?php if($grading && is_array($grading)):?>
                                                        <div class="table-responsive">
                                                            <table class="table" id="filesTableE">
                                                                <thead>
                                                                <tr>
                                                                    <th>Grade</th>
                                                                    <th>Scale</th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="tableBodyE" class="tableBodyE">
                                                                <?php foreach ($grading as $g):?>
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text" name="grade[]" class="form-control" value="<?php echo $g->grade;?>"/>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="scale[]" class="form-control" value="<?php echo $g->scale;?>"/>
                                                                        </td>
                                                                        <td><button type="button" id="removeRowE" class="btn btn-sm btn-danger">x</button></td>
                                                                    </tr>
                                                                <?php endforeach;?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <button type="button" class="btn btn-default btn-sm" id="addFileE"><i class="fa fa-plus"></i> Add Row</button>
                                                        <br/><br/>
                                                    <?php endif;?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    <button type="button" class="btn btn-link  ml-auto"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                          <?php if (isSuperAdmin()):?>
                                <a class="btn btn-danger btn-sm send-to-server-click"
                                   data="method:delete|id:<?php echo $class->id; ?>"
                                   url="<?php echo site_url(route_to('admin.school.classes.delete', $class->id)); ?>" loader="true"
                                   warning-title="Irreversible Action"
                                   warning-message="Are you sure you want to delete this class and all of its data?"
                                   href="#!"> <i class="fa fa-trash"></i> Delete</a>
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
            <div class="card-body">
                <div class="alert alert-danger">
                    No classes have been added
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script>
  $('.kind').on('click',function (){
      if ($(this).is(':checked')){
      $('.grading').show();
      }else{
      $('.grading').hide();
      }
  })

    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFile', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="grade[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="text" name="scale[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRow" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBody").append(html);
        //$('#filesTable').append(html);
    });

    $('#kind').on('click',function (){
        if ($(this).is(":checked")){
         $('.grading').show();
        }else {
         $('.grading').hide();
        }
    })

    $(document).on('click', '#removeRowE', function (e) {
        e.preventDefault();
        $('#removeRowE').parents('tr').remove();
    });

    $(document).on('click', '#addFileE', function (e) {
       // alert('cool....')
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="grade[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="text" name="scale[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowE" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $(".tableBodyE").append(html);
        //$('#filesTable').append(html);
    });

    $('#kind').on('click',function (){
        if ($(this).is(":checked")){
            $('.grading').show();
        }else {
            $('.grading').hide();
        }
    })

  $(document).ready(function () {
      $('#classes-datatable').dataTable({
          dom: 'Bfrtip',
          colReorder: true,
          buttons: [
              // {
              //     extend: 'copy',
              //     exportOptions: {
              //         columns: [ 0, 1, 2, 3 ]
              //     }
              // },
              // {
              //     extend: 'excel',
              //     exportOptions: {
              //         columns: [ 0, 1, 2, 3 ]
              //     }
              // },
              // {
              //     extend: 'pdf',
              //     exportOptions: {
              //         columns: [ 0, 1, 2, 3 ]
              //     }
              // },
              // {
              //     extend: 'print',
              //     exportOptions: {
              //         columns: [ 0, 1, 2, 3 ]
              //     }
              // },
          ],
      });
  })
</script>