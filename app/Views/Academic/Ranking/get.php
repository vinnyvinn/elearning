<div class="card">
    <div class="card-header">
        <a href="<?php echo site_url(route_to("admin.academic.semester_ranking_pdf",$class,$section,$semester))?>" target="_blank" class="btn btn-danger btn-sm ml-1"><i class="fa fa-download">Pdf</i></a>
        <a href="<?php echo site_url(route_to("admin.academic.semester_ranking_print",$class,$section,$semester))?>" target="_blank" class="btn btn-danger btn-sm ml-1"><i class="fa fa-print">Print</i></a>
    </div>
</div>
<?php
//$class = (new \App\Models\Classes())->find($class);

$section = (new \App\Models\Sections())->find($section);
$semester = (new \App\Models\Semesters())->find($semester);
$subjects = $section->class->subjects;
$students = $section->students;

?>
<div class="table-responsive">
    <table class="table datatable" id="datatable">
        <thead>
        <tr>
            <th># </th>
            <th>Student</th>
            <?php
            if(count($subjects) > 0) {
                foreach ($subjects as $subject) { ?>
                    <th><?php echo $subject->name; ?></th>
                    <?php
                }
            }
            ?>
            <th>Average</th>
            <th>Total</th>
            <th>Rank</th>
            <th style="display: none"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $n = 0;
        $i = 0;
        foreach ($section->students as $student) {
            $n++;
            ?>
            <tr>
                <td><?php echo $n; ?></td>
                <td><?php echo $student->profile->name; ?></td>
                <?php
                if(count($subjects) > 0) {
                    foreach ($subjects as $subject) {
                        $result = getSemesterSubjectTotal($student->id,$subject->id,$semester->id);
                        ?>
                        <td>
                          <?php

                            if($result && !empty($result) &&  $subject->optional == 0) {
                                echo $result;
                            }elseif($subject->optional ==0) {
                                echo '-';
                            }

                            if ($subject->optional == 1) {
                             echo getSemesterSubjectGrade($student->id,$subject->id,$semester->id);
                            }
                            ?>
                        </td>
                         <?php
                    }
                }
                ?>
                <td>
                 <?php echo getSemesterAverage($student->id,$semester->id); ?>
                </td>
                <td><?php echo getSemesterTotal($student->id,$semester->id); ?></td>
                <td><?php echo getSemesterRank($student->id,$semester->id); ?></td>
                <td style="display:none;"></td>
            </tr>
            <?php
        }
        ?>
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
                // {
                //     extend: 'pdf',
                // },
                // {
                //     extend: 'print',
                // },
            ],
            "aoColumnDefs": [
                { "sType": "numeric", "aTargets": [ 0, -1 ] }
            ]
        });
    })
</script>