<?php


use App\Models\ExamResults;
use App\Models\Students;

$student = (new Students())->find($student);
if($student && $student->parent->id == $parent->id) {
    $examResultsModel = new ExamResults();
    $exam_results = $examResultsModel->getResultsAndRank($student->id, $exam);

    if($exam_results ) {

        //return $this->response->setContentType('application/json')->setBody(json_encode($exam_results));
        ?>
        <div class="table-responsive" id="result">
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Score</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                $total = 0;
                $divisor = 0;
                foreach ($exam_results['marks'] as $key=>$result) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $exam_results['labels'][$key]; ?></td>
                        <td><?php echo $result; ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td></td>
                    <th>Total</th>
                    <th><?php echo $exam_results['total_marks']; ?></th>
                </tr>
                <tr>
                    <td></td>
                    <th>Average</th>
                    <th><?php echo number_format($exam_results['average'],2); ?></th>
                </tr>
                <tr>
                    <td></td>
                    <th>Rank</th>
                    <th><?php echo $exam_results['rank']; ?></th>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger">
            <?php echo "Results for ".$student->profile->name." have not been added"; ?>
        </div>
        <?php
    }
} else {
    ?>
    <div class="alert alert-danger">
        <?php echo "Student does not exist or you are not allowed to view the results"; ?>
    </div>
    <?php
}?>

<script>
 $(function (){
     const r = document.getElementById('result');
     r.scrollIntoView();
 })

</script>
