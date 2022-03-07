<?php
 $assignment = (new \App\Models\AssignmentItems())->find($assignment);

if($students && count($students) > 0) {
    ?>
        <div class="table-responsive">
            <table class="table datatable" id="datatable-basic">
                <thead class="thead-light">
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>ADM. NO</th>
                    <th>Section</th>
                    <th>Marks</th>
                    <th>OUT OF</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n=0;
                foreach ($students as $student) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n;?></td>
                        <td><?php echo $student->profile->name; ?></td>
                        <td><?php echo $student->admission_number; ?></td>
                        <td><?php echo $student->section->name; ?></td>
                        <td><?php echo $student->marks_scored($assignment->id); ?></td>
                        <td><?php echo $assignment->out_of; ?></td>
                        <td>
                            <?php if($student->submission($assignment->id)):?>
                            <span class="badge badge-success">Submitted</span>
                        <?php else:?>
                            <span class="badge badge-danger">Unavailable</span>
                        <?php endif;?>
                           </td>
                        <td>
                            <?php if($student->submission($assignment->id)):?>
                            <a href="<?php echo site_url(route_to('admin.academic.assignments.view_submitted',$student->id,$assignment->id))?>" class="btn btn-primary btn-sm">View</a>
                        <?php endif;?>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    <script>
        $(document).ready(function () {
            $('#datatable-basic').dataTable({
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
    <?php
} else {
    ?>
    <div class="card-body">
        <div class="alert alert-danger">
            No students were found for this class section
        </div>
    </div>
    <?php
}
