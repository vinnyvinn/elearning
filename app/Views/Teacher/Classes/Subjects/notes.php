<div class="card">
    <?php
    $notes = $subject->notes;
    //d($subject);
    if($notes && count($notes) > 0) {
        ?>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>File</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($notes as $note) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $note->name; ?></td>
                        <td><?php echo $note->description; ?></td>
                        <td><a href="<?php echo $note->file; ?>">Download File</a></td>
                        <td><?php echo $note->created_at->format('d/m/Y H:i:s'); ?></td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="#!" data-toggle="modal" data-target=".edit_note<?php echo $note->id; ?>">Edit</a>
                    <?php if (isSuperAdmin()):?>
                                    <a class="dropdown-item text-danger send-to-server-click" url="<?php echo site_url(route_to('admin.subject.notes.delete', $note->id)); ?>" data="action:delete|id:<?php echo $note->id ?>" warning-title="Delete Note" warning-message="Do you really want to delete this entry?" loader="true" href="<?php echo site_url(route_to('admin.subject.notes.delete', $note->id)); ?>">Delete</a>
                          <?php endif;?>
                                </div>
                            </div>
                            <div class="modal fade edit_note<?php echo $note->id; ?>" tabindex="-1"
                                 role="dialog" aria-labelledby="modal-default"
                                 style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form class="ajaxForm" loader="true" method="post" data-parsley-validate
                                              action="<?php echo site_url(route_to('admin.subjects.notes', $subject->id, $section->id)); ?>">
                                            <input type="hidden" name="id" value="<?php echo $note->id; ?>" />
                                            <input type="hidden" name="class" value="<?php echo $section->class->id; ?>"/>
                                            <input type="hidden" name="section" value="<?php echo $section->id; ?>"/>
                                            <input type="hidden" name="subject" value="<?php echo $subject->id; ?>"/>
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="modal-title-default">Upload Notes</h6>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="sess">Title</label>
                                                    <input type="text" class="form-control" name="name"
                                                           value="<?php echo old('name', $note->name); ?>"
                                                           required/>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea class="form-control" name="description" rows="4"><?php echo old('description', $note->description); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sess">File</label>
                                                    <input type="file" class="form-control"
                                                           name="file" />
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
        <div class="card-body">
            <div class="alert alert-danger">
                No notes have been added yet
            </div>
        </div>
        <?php
    }
    ?>
    <div class="table-responsive">

    </div>
</div>