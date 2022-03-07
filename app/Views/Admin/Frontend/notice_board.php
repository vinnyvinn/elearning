<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Public Notice Board</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.frontend.notice_board_new')); ?>" class="btn btn-sm btn-success">New Notice</a>
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
            $events = (new \App\Models\Notices())->where('public', '1')->orderBy('id', 'DESC')->findAll();
            if(isset($events) && count($events) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Info</th>
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
                                <td><?php echo $event->date_created; ?></td>
                                <td><?php echo $event->title; ?></td>
                                <td><?php echo word_limiter($event->info, 10); ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="<?php echo site_url(route_to('admin.frontend.notice_board_edit', $event->id)); ?>"><i class="fa fa-edit"></i> Edit</a>
                            <?php if (isSuperAdmin()):?>
                                    <a class="btn btn-danger btn-sm send-to-server-click" href="<?php echo site_url(route_to('admin.frontend.notice.delete', $event->id)); ?>"
                                        loader="true"
                                       warning-title="Delete this notice?"
                                       warning-message="Are you sure you want to delete this notice?"
                                       warning-button="Yes, Delete"
                                       url="<?php echo site_url(route_to('admin.frontend.notice.delete', $event->id)); ?>"
                                       data="action:delete|id:<?php echo $event->id; ?>"
                                    >Delete</a>
                            <?php endif;?>
                                    <?php if ($event->active ==1):?>
                                    <a title="Hide" class="btn btn-info btn-sm send-to-server-click" href="<?php echo site_url(route_to('admin.frontend.notice.hide', $event->id)); ?>"
                                       loader="true"
                                       warning-title="Hide this notice?"
                                       warning-message="Are you sure you want to hide this notice?"
                                       warning-button="Yes, Hide"
                                       url="<?php echo site_url(route_to('admin.frontend.notice.hide', $event->id)); ?>"
                                       data="action:hide|id:<?php echo $event->id; ?>"
                                    ><i class="fa fa-lock"></i></a>
                            <?php else:?>
                                <a title="Show" class="btn btn-success btn-sm send-to-server-click" href="<?php echo site_url(route_to('admin.frontend.notice.show', $event->id)); ?>"
                                   loader="true"
                                   warning-title="Show this notice?"
                                   warning-message="Are you sure you want to show this notice?"
                                   warning-button="Yes, Show"
                                   url="<?php echo site_url(route_to('admin.frontend.notice.show', $event->id)); ?>"
                                   data="action:show|id:<?php echo $event->id; ?>"
                                ><i class="fa fa-eye"></i></a>
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
                    No public notices have been posted
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<script>


</script>