<?php
$subj = (new \App\Models\Subjects())->find($subject->subject);
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0"><?php
                      echo $class->name.'-'.$semester->name.'-'.$subj->name;?></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
 <div class="card">
 <div class="card-body">
<div class="table-responsive">
    <table class="table datatable" id="datatable">
        <thead>
        <tr>
            <th>#</th>
            <th>Student</th>
            <th>Total</th>
            <th>Converted Total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $n=0;
        foreach ($students as $student) {
            $n++;
            $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
            $result = $resultsModel->getSemesterManualAssessment($semester->id,$subject->id);
            ?>
            <tr>
                <td><?php echo $n; ?></td>
                <td><?php echo $student->profile->name.'-'.$student->id; ?></td>
                <td><?php echo $result->total_score?></td>
                <td>
                 <?php echo $result->converted;?>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
 </div>
 </div>
<script>
    $(document).ready(function () {
        $('#datatable').dataTable({
            dom: 'Bfrtip',
            colReorder: true,
            buttons: [
                {
                    extend: 'copy'
                },
                {
                    extend: 'excel',
                },
                {
                    extend: 'pdf',
                },
                {
                    extend: 'print',
                },
            ],
            "aoColumnDefs": [
                { "sType": "numeric", "aTargets": [ 0, -1 ] }
            ]
        });
    })
</script>