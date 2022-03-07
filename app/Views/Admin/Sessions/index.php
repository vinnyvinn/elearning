<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">School Sessions</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_session"><i
                                class="fa fa-user-plus"></i> New Session
                    </button>
                    <?php do_action('sessions_quick_action_buttons'); ?>
                </div>
                <div class="modal fade new_session" role="dialog" aria-labelledby="modal-default"
                     style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content">
                            <form class="ajaxFormii" loader="true" method="post"
                                  action="<?php echo site_url(route_to('admin.sessions.create')); ?>">
                                <div class="modal-header">
                                    <h6 class="modal-title" id="modal-title-default">New Session</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                   <div class="row">
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label for="sess">Session Name</label>
                                               <input type="text" class="form-control" name="name"
                                                      value="<?php echo old('name') ?>" required/>
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label>Year</label>
                                               <input type="number" class="form-control" name="year"
                                                      value="<?php echo old('year') ?>" required/>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="form-group">
                                        <input type="radio" name="session_type" value="0" checked>
                                        <label>By Semester</label>
                                        <input type="radio" name="session_type" value="1">
                                        <label>By Quarter</label>
                                        <input type="radio" name="session_type"value="2" >
                                        <label>By Quarter Plus</label>
                                    </div>
                                    <a href="#!" onclick="$('#advanced').toggle('slow')"><i class="fa fa-cogs"></i> Advanced Options</a>
                                    <div id="advanced" style="display: none">
                                        <?php
                                        $existing_sessions = (new \App\Models\Sessions())->findAll();
                                        if($existing_sessions && count($existing_sessions) > 0 ) {
                                            ?>
                                            <br/>
                                            <h2>Import Data</h2>
                                            <div class="form-group">
                                                <label>Import From Another Session</label>
                                                <select class="form-control select2" name="import_session" >
                                                    <option value=""> -- Please select a session -- </option>
                                                    <?php
                                                    foreach ($existing_sessions as $existing_session) {
                                                        ?>
                                                        <option value="<?php echo $existing_session->id; ?>"><?php echo $existing_session->name; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Select Data to import from selected session</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label><input type="checkbox" name="classes" value="1" /> Class Information</label>
                                                        <label><input type="checkbox" name="sections" value="1" /> Class Sections</label>
                                                        <label><input type="checkbox" name="students" value="1" /> Students</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label><input type="checkbox" name="subjects" value="1" /> Class Subjects</label>
                                                        <label><input type="checkbox" name="subject_teachers" value="1" /> Class Subject Teacher</label>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success save">Save</button>
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
        if ($sessions && count($sessions) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($sessions as $session) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $session->name; ?></td>
                            <td>
                                <label class="custom-toggle custom-toggle-success disabled">
                                    <input type="checkbox"
                                           disabled <?php echo $session->active == 1 ? 'checked' : ''; ?>>
                                    <span class="custom-toggle-slider rounded-circle disabled" data-label-off="No"
                                          data-label-on="Yes"></span>
                                </label>
                            </td>
                            <td>
                                <?php
                                if ($session->active != 1) {
                                    ?>
                                    <a class="btn btn-success btn-sm send-to-server-click"
                                       data="method:activate|id:<?php echo $session->id; ?>|active:1"
                                       url="<?php echo site_url(route_to('admin.sessions.activate', $session->id)); ?>" loader="true"
                                       warning-title="Confirm"
                                       warning-message="Are you sure you want to activate this session? All future operations will reflect in this session"
                                       href="#!"> <i class="fa fa-check"></i> Activate</a>
                                    <?php if (isSuperAdmin()):?>
                                    <a class="btn btn-danger btn-sm send-to-server-click"
                                       data="method:delete|id:<?php echo $session->id; ?>"
                                       url="<?php echo site_url(route_to('admin.sessions.delete', $session->id)); ?>" loader="true"
                                       warning-title="Irreversible Action"
                                       warning-message="Are you sure you want to delete this session and all of its data?"
                                       href="#!"> <i class="fa fa-trash"></i> Delete</a>
                                    <?php endif;
                                }
                                ?>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target=".edit_<?php echo $session->id; ?>"><i class="fa fa-edit"></i> Edit
                                </button>
                                <div class="modal fade edit_<?php echo $session->id; ?>" tabindex="-1" role="dialog"
                                     aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post"
                                                  action="<?php echo site_url(route_to('admin.sessions.update')); ?>">
                                                <input type="hidden" name="id" value="<?php echo $session->id; ?>" />
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modal-title-default">Update Session</h6>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group" style="display: grid">
                                                        <label for="sess">Session Name</label>
                                                        <input type="text" class="form-control" name="name"
                                                               value="<?php echo old('name', $session->name); ?>"
                                                               required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Year</label>
                                                        <input type="number" class="form-control" name="year"
                                                               value="<?php echo old('year',$session->year) ?>" required/>
                                                    </div>
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
                    No sessions have been set up
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script>
    $('.save').on('click',function (){
       setTimeout(()=>{
           $(this).attr('disabled',true)
           $(this).html('Please wait...')
       },1000)
    })
</script>