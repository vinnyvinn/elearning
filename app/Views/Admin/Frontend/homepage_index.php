<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">SECTION 3</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.frontend.homepage')); ?>" class="btn btn-sm btn-success">New Video</a>
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
            $events =get_option('home_videos') ? json_decode(get_option('home_videos')) :'';
            if(isset($events) && !empty($events)) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
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
                                <td><?php echo $event->title; ?></td>
                                <td><?php echo word_limiter($event->description, 10); ?></td>
                                <td>

<!--                                    <a class="btn-sm send-to-server-click" href="--><?php //echo site_url(route_to('admin.frontend.video.delete', $n)); ?><!--"-->
<!--                                        loader="true"-->
<!--                                       warning-title="Delete this video?"-->
<!--                                       warning-message="Are you sure you want to delete this video?"-->
<!--                                       warning-button="Yes, Delete"-->
<!--                                       url="--><?php //echo site_url(route_to('btn btn-danger admin.frontend.video.delete', $n)); ?><!--"-->
<!--                                       data="action:delete|id:--><?php //echo $n; ?><!--"-->
<!--                                    >Delete</a>-->
                                    <a href="<?php echo base_url('admin/frontend/edit-homepage?video='.$event->video)?>" class="btn btn-primary btn-sm">Edit</a>
                            <?php if (isSuperAdmin()):?>
                                    <a href="<?php echo site_url(route_to('admin.frontend.video.delete', $n)); ?>" class="btn btn-danger btn-sm">Delete</a>
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
    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFile', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="file" name="image[]" class="form-control" accept="image/*"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRow" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBody").append(html);
    });

        $(document).on('click', '#removeRowE', function (e) {
            e.preventDefault();
            $(this).parents('tr').remove();
        });

        $(document).on('click', '#addFileE', function (e) {
            //e.preventDefault();
            var html = '<tr>\n' +
                '        <td>\n' +
                '            <input type="file" name="image[]" class="form-control" accept="image/*"/>\n' +
                '        </td>\n' +
                '        <td>\n' +
                '            <button type="button" id="removeRowE" class="btn btn-sm btn-danger">x</button>\n' +
                '        </td>\n' +
                '    </tr>';
            $("#tableBodyE").append(html);
        //$('#filesTable').append(html);
    });
</script>