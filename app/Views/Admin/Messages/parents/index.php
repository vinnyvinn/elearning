<?php
//Show all parents
$parents = (new \App\Models\Parents())->getParents();
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Parents Messages</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php do_action('parents_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header"></div>
        <?php
        if($parents && count($parents) > 0) {
            ?>
            <div class="table-responsive pt-2">
                <table class="table" id="parents-table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($parents as $parent) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td class="table-user">
                                <a href="<?php echo site_url(route_to('admin.parents.view', $parent->id)); ?>">
                                    <?php echo $parent->name; ?>
                                </a>
                            </td>
                            <td><?php echo $parent->phone; ?></td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-sms"> Message</i></a>
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
                    No parents were found in the system
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#parents-table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
            ],
        });
    })
</script>