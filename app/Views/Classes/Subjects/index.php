<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Subjects</h6>
                    <a href="<?php echo site_url(route_to("admin.registration.subjects.excel"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-file-excel"></i> Excel</a>
                    <a href="<?php echo site_url(route_to('admin.registration.subjects.pdf')); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-cloud-download-alt"></i> PDF</a>
                    <a href="<?php echo site_url(route_to("admin.registration.subjects.print"));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                                class="fa fa-print"></i> Print</a>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_subject">
                        New Subject
                    </button>
                </div>
            </div>
            <div class="modal fade new_subject" tabindex="-1"
                 role="dialog" aria-labelledby="modal-default"
                 style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                    <div class="modal-content">
                        <form class="ajaxForm" loader="true" method="post"
                              action="<?php echo site_url(route_to('admin.subjects.create')); ?>">
                            <div class="modal-header">
                                <h6 class="modal-title" id="modal-title-default">New
                                    Subject</h6>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Subject Name</label><br/>
                                    <input class="form-control" name="name" value="<?php old('name'); ?>" required/>
                                </div>
<!--                                <div class="form-group" style="display: none">-->
<!--                                    <label for="sess">Pass Mark (%)</label>-->
<!--                                    <input type="number" min="0" max="100" class="form-control"-->
<!--                                           name="pass_mark"-->
<!--                                           value="--><?php //echo old('pass_mark'); ?><!--"-->
<!--                                           required/>-->
<!--                                </div>-->
<!--                                <div class="form-group" style="display: none">-->
<!--                                    <label>Subject Optional?</label><br/>-->
<!--                                    <label class="custom-toggle">-->
<!--                                        <input type="checkbox" name="optional"-->
<!--                                               value="1" --><?php //echo old('optional') == 1 ? 'checked' : ''; ?><!-- />-->
<!--                                        <span class="custom-toggle-slider rounded-circle"-->
<!--                                              data-label-off="No" data-label-on="Yes"></span>-->
<!--                                    </label>-->
<!--                                </div>-->
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
        <div class="card-header"></div>
        <div class="card-body text-warning">
            After adding the subject here, click on 'View Class' in <a href="<?php echo site_url(route_to('admin.registration.classes.index')); ?>">this page</a> to add the subject to that class, then assign the teacher in the sections.
        </div>
        <?php

        use App\Models\Subjects;

        $subjects = (new Subjects())->findAll();
        if ($subjects && count($subjects) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table" id="subjects-datatable">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th style="display: none"></th>
                        <th>Action</th>
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
                            <td><?php echo $subject->name; ?></td>
                            <td style="display: none"></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target=".edit_subject<?php echo $subject->id; ?>">Edit
                                </button>
                        <?php if (isSuperAdmin()):?>
                                <a class="btn btn-sm btn-danger send-to-server-click"
                                   href="<?php echo site_url(route_to('admin.subjects.delete', $subject->id)); ?>"
                                   url="<?php echo site_url(route_to('admin.subjects.delete', $subject->id)); ?>"
                                   data="action:delete|id:<?php echo $subject->id; ?>" loader="true"
                                   warning-title="Delete Subject"
                                   warning-message="Are you sure you want to delete this subject and all of its contents?">Delete</a>
                        <?php endif;?>
                                <div class="modal fade edit_subject<?php echo $subject->id; ?>" tabindex="-1"
                                     role="dialog" aria-labelledby="modal-default"
                                     style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                        <div class="modal-content">
                                            <form class="ajaxForm" loader="true" method="post"
                                                  action="<?php echo site_url(route_to('admin.subjects.edit', $subject->id)); ?>">
                                                <input type="hidden" name="id" value="<?php echo $subject->id; ?>"/>
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="modal-title-default">Update
                                                        Subject</h6>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Subject Name</label><br/>
                                                        <input class="form-control" name="name"
                                                               value="<?php echo old('name', $subject->name); ?>" required/>
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
                    No subjects found
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#subjects-datatable').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                    }
                },
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