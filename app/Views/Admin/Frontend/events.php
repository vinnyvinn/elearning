<?php




?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Homepage Events</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_slide">Add Event</button>
                </div>
            </div>
            <div class="modal fade new_slide" role="dialog" style="display: none">
                <div class="modal modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">New Event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.frontend.save_event')); ?>">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="text" class="form-control datepicker" name="date" value="<?php echo old('date'); ?>" required/>
                                    </div>
                                    <div class="form-group">
                                       <label>Title</label>
                                      <input type="text" class="form-control" name="title" value="<?php echo old('title'); ?>" required/>
                                    </div>

                                    <div class="form-group">
                                       <label>Description</label>
                                       <textarea id="textarea" class="form-control" name="description" rows="4" required><?php echo old('description'); ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Save Slide</button>
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
            $events = (new \App\Models\Events())->where('public', '1')->orderBy('id', 'DESC')->where("session",active_session())->findAll();
            if(isset($events) && count($events) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($events as $event) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $event->name; ?></td>
                                <td><?php echo word_limiter($event->description, 10); ?></td>
                                <td><?php echo $event->starting_date; ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target=".edit_slide_<?php echo $event->id?>">Edit</button>
                                    <div class="modal fade edit_slide_<?php echo $event->id?>" role="dialog" style="display: none">
                                        <div class="modal modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Event</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.frontend.update_event',$event->id)); ?>">
                                                            <div class="form-group">
                                                                <label>Date</label>
                                                                <input type="text" class="form-control datepicker" name="date" value="<?php echo $event->starting_date; ?>" required/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                <input type="text" class="form-control" name="title" value="<?php echo $event->name; ?>" required/>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <textarea id="textarea" class="form-control" name="description" rows="4" required><?php echo $event->description;?></textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-success">Save Slide</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php if (isSuperAdmin()):?>
                                    <a class="btn btn-danger btn-sm send-to-server-click" href="<?php echo site_url(route_to('admin.frontend.event.delete', $event->id)); ?>"
                                       loader="true"
                                       warning-title="Delete this notice?"
                                       warning-message="Are you sure you want to delete this event?"
                                       warning-button="Yes, Delete"
                                       url="<?php echo site_url(route_to('admin.frontend.event.delete', $event->id)); ?>"
                                       data="action:delete|id:<?php echo $event->id; ?>"
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
                    No public events have been posted
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>