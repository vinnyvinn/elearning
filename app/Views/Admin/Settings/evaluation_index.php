<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
               <div class="col-lg-6 col-7">
               <h6 class="h2 text-white d-inline-block mb-0">Student Evaluations</h6>
               </div>
               <div class="col-lg-6 col-5 text-right">
               <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_slide">New Evaluation</button>
            </div>
            </div>
            <div class="modal fade new_slide" role="dialog" style="display: none">
                <div class="modal modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="card">
                            <div class="card-body">
                                <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.settings.save_evaluation')); ?>">
                                    <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="textarea" class="form-control" name="description" rows="4" required><?php echo old('description'); ?></textarea>
                                    </div>
                                        <button type="submit" class="btn btn-success">Save</button>
                                </form>
                            </div>
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
        <div class="card-body">
            <?php
            $evaluations = (new \App\Models\Evaluations())->orderBy('id', 'DESC')->findAll();
            if(isset($evaluations) && count($evaluations) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($evaluations as $evaluation) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo word_limiter($evaluation['description'], 10); ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target=".edit_<?php echo $evaluation['id']; ?>"><i class="fa fa-edit"></i> Edit
                                    </button>
                                    <div class="modal fade edit_<?php echo $evaluation['id']; ?>" role="document"
                                         aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post"
                                                      action="<?php echo site_url(route_to('admin.settings.evaluation.update', $evaluation['id'])); ?>">
                                                    <input type="hidden" name="id" value="<?php echo $evaluation['id']; ?>" />
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Update Evaluation</h6>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="sess">Description</label>
                                                            <textarea name="description" class="form-control" rows="4" required><?php echo old('description', $evaluation['description']) ?></textarea>

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
                            <?php if (isSuperAdmin()):?>
                                    <a class="btn btn-danger btn-sm send-to-server-click" href="<?php echo site_url(route_to('admin.settings.evaluation.delete', $evaluation['id'])); ?>"
                                       loader="true"
                                       warning-title="Delete this Evaluation?"
                                       warning-message="Are you sure you want to delete this Evaluation?"
                                       warning-button="Yes, Delete"
                                       url="<?php echo site_url(route_to('admin.settings.evaluation.delete', $evaluation['id'])); ?>"
                                       data="action:delete|id:<?php echo $evaluation['id']; ?>"
                                    >Delete</a>
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
                <div class="alert alert-danger">
                    No Evaluations have been posted
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>