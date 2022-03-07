<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Classes</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <?php
        $classes = $classes->findAll();
        if($classes && count($classes) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Class</th>
                            <th>Sections</th>
                            <th># of Students</th>
                            <th>Subjects</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($classes as $class) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td> <a href="<?php echo site_url(route_to('admin.classes.view', $class->id)); ?>"><?php echo $class->name; ?></a> </td>
                            <td><?php echo count($class->sections()); ?></td>
                            <td><?php echo count($class->students()); ?> <a href="<?php echo site_url(route_to('admin.classes.students', $class->id)); ?>"><i class="fa fa-external-link-square-alt"></i></a></td>
                            <td><?php echo count($class->subjects()); ?></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" style="">
                                        <a class="dropdown-item" href="<?php echo site_url(route_to('admin.classes.view', $class->id)); ?>"><i class="fa fa-search"></i> View</a>
                                        <a class="dropdown-item" href="<?php echo site_url(route_to('admin.classes.students', $class->id)); ?>"><i class="fa fa-users"></i> View Students</a>
                                        <?php do_action('classes_table_action_links', $class); ?>
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
                    No classes have been set up
                </div>
            </div>
            <?php
        }
        ?>

    </div>
</div>