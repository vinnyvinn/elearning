<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Transport Route (<?php echo $route->route;?>)</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo site_url(route_to('admin.transport.view.pdf',$route->id))?>" class="btn btn-danger btn-sm"><i class="fa fa-download">Download</i></a>
                    <?php do_action('admin_transport_routes_quick_action_buttons'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <?php
            $db = \Config\Database::connect();
            $builder = $db->table('usersmeta');
            $builder->where('meta_key','transportation_route');
            $builder->where('meta_value',$route->id);
            $result = $builder->get()->getResult();
            $students = array();
            foreach ($result as $res){
             array_push($students,(new \App\Models\Students())->where('user_id',$res->userid)->get()->getLastRow("\App\Entities\Student"));
            }

            if($students && count($students) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table" id="routes-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Admission Number</th>
                                <th>Class</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($students as $student) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $student->profile->name; ?></td>
                                <td><?php echo $student->admission_number; ?></td>
                                <td><?php echo $student->class->name; ?></td>
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
                <div class="alert alert-routes">
                    No Student record found for this route.
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#routes-table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
            ],
        });
    })
</script>