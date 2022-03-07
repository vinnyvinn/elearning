<?php
$section = (new \App\Models\Sections())->find($section);
$students = $section->students;
?>
<style>
    td,th{
      border: 1px solid black !important;
    }
</style>
<div class="table-responsive">
    <?php
    $semesters = (new \App\Models\Semesters())->where('session',active_session())->findAll();
    $data = studentResult($students,$semesters[0]->id,$semesters[1]->id);
    $keys = array_keys($data);
    $stud = (new \App\Models\Students())->find(array_slice($keys,0,1));
   $subjects = $stud[0]->class->subjects;
    ?>

    <table class="table datatable" id="">
        <thead>
       <tr>
           <th>Name</th>
           <th>Sex</th>
           <th>Semester</th>
           <?php foreach ($subjects as $subject):?>
               <th><?php echo $subject->name;?></th>
           <?php endforeach;?>
           <th>Total</th>
           <th>Average</th>
           <th>Rank</th>
       </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $key => $items):
            $student = (new \App\Models\Students())->find($key);
            ?>
            <tr>
                <td rowspan="4" style="vertical-align: middle"><?php echo $student->profile->name;?></td>
            <td rowspan="4" style="vertical-align: middle"><?php echo strtolower($student->profile->gender)=='female' ? "F" : "M";?></td>
             <?php foreach($items as $k2 => $item):
                ?>
            <tr>

              <td><?php echo $k2;?></td>
                <?php
                foreach ($item['scores'] as $k3 => $val):?>
                 <td><?php echo $val;?></td>
                <?php endforeach;?>
                <td><?php echo $item['total']?></td>
                <td><?php echo $item['average']?></td>
                <td><?php echo $item['rank']?></td>
            </tr>
            <?php endforeach;?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
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