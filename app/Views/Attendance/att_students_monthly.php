<div class=ml-5>
    <a href="<?php echo site_url(route_to("admin.attendance.students_monthly_excel",$class,$section->id,$month,$year));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                class="fa fa-file-excel"></i> Excel</a>
    <a href="<?php echo site_url(route_to('admin.attendance.students_monthly_pdf',$class,$section->id,$month,$year)); ?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                class="fa fa-cloud-download-alt"></i> PDF</a>
    <a href="<?php echo site_url(route_to("admin.attendance.students_monthly_print",$class,$section->id,$month,$year));?>" target="_blank" class="btn btn-sm btn-danger ml-1"><i
                class="fa fa-print"></i> Print</a>
</div>
<?php
if ($attendance && count($attendance) > 0) {
     ?>
    <div class="table-responsive">
        <table class="table table-sm table-bordered" id="attend-table">
            <thead class="thead-light">
            <tr>
                <th>Date </th>
                <th>No. of Present Students</th>
                <th>No. of Absent Students</th>
                <th>No. of Permission Students</th>
                <th>No. of Sick Students</th>
                <th>No. of Late Students</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($attendance as $item) {
                ?>
                <tr>
                    <td><?php echo $item['date']; ?></td>
                    <td><?php echo $item['students']-$item['absent']-$item['permission']-$item['sick']-$item['late'] .'/'.$item['students'];?></td>
                    <td><?php echo $item['absent'] .'/'.$item['students']; ?></td>
                    <td><?php echo $item['permission'] .'/'.$item['students']; ?></td>
                    <td><?php echo $item['sick'] .'/'.$item['students']; ?></td>
                    <td><?php echo $item['late'] .'/'.$item['students']; ?></td>
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
            No attendance found for this class
        </div>
    </div>
    <?php
}?>

<script>
    $(document).ready(function () {
        $('#attend-table').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    }
                },
                // {
                //     extend: 'excel',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3, 4, 5 ]
                //     }
                // },
                // {
                //     extend: 'pdf',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3, 4, 5 ]
                //     }
                // },
                // {
                //     extend: 'print',
                //     exportOptions: {
                //         columns: [ 0, 1, 2, 3, 4, 5 ]
                //     }
                // },
            ],
        });
    })
</script>
