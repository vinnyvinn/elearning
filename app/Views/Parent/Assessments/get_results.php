<?php



$student = (new \App\Models\Students())->find($student);
$resultmodel = new \App\Libraries\YearlyResults($student->id,$student->session->id);
?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive" id="results">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Total</th>
                        <th>Converted Total</th>
                    </tr>
                    </thead>
                    <tbody>
                           <?php
                           $n=0;
                           foreach ($student->class->subjects as $sub):
                               $n++;
                               $result = $resultmodel->getSemesterManualAssessment($semester,$sub->id);

                               ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo $sub->name;?></td>
                                <td><?php echo $result->total_score;?></td>
                                <td><?php echo $result->converted;?></td>
                            </tr>
                     <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
    $(function (){
        moveWindow();
    })
    function moveWindow(){
        const e = document.getElementById('results');
        e.scrollIntoView();
    }
</script>