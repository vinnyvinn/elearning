<div class="evaluation">
<?php
$section = (new \App\Models\Sections())->find($student->section->id);
$class = (new \App\Models\Classes())->find($student->class->id);
$std_id = $student->id;
if(isset($session) && is_numeric($session)) {
    $session = (new \App\Models\Sessions())->find($session);
} else {
    $session = getSession();
}

$semesters = (new \App\Models\Semesters())->where('session',$session->id)->findAll();
$student_evaluation = (new \App\Models\StudentEvaluation())->where('student',$student->id)->where('session',$session->id)->where('class',$class->id)->where('section',$section->id)->get()->getLastRow();
$saved_evaluations = isset($student_evaluation->remark) ? json_decode($student_evaluation->remark) : [];
//echo '<pre>';
//foreach ($saved_evaluations as $k => $ev){
//    var_dump($k);
//}

//var_dump($saved_evaluations);
//var_dump($saved_evaluations[1]);

?>                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" style="width: 100%">
                                        <thead>
                                        <tr>
                                          <th colspan="3" style="background: lightgrey;text-align: center"><h3>Student's Behavior and Basic Skills Progress Report</h3></th>
                                        </tr>
                                            <tr>
                                                <th><h3>Traits / Evaluation areas</h3></th>
                                                <?php foreach ($semesters as $semester):?>
                                                <th><h3><?php echo $semester->name;?></h3></th>
                                                <?php endforeach;?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (!empty($saved_evaluations)){?>
                                        <?php
                                            foreach ($saved_evaluations as $key => $evaluation){
                                                $trait = (new \App\Models\Evaluations())->find($key);
                                             ?>
                                        <tr>
                                            <td class="evaluate"><?php echo $trait['description'];?></td>
                                            <?php foreach ($evaluation as $k => $ev) {
                                                ?>
                                            <td>
                                                <select name="remark[<?php echo $key?>][<?php echo $k;?>][<?php echo array_keys((array)$ev)[0];?>]" class="form-control" required>
                                                    <option value="E" <?php if (array_values((array)$ev)[0] == 'E'){ echo 'selected';}?>>E</option>
                                                    <option value="V" <?php if (array_values((array)$ev)[0] == 'V'){ echo 'selected';}?>>V.G</option>
                                                    <option value="G" <?php if (array_values((array)$ev)[0] == 'G'){ echo 'selected';}?>>G</option>
                                                    <option value="S" <?php if (array_values((array)$ev)[0] == 'S'){ echo 'selected';}?>>S</option>
                                                    <option value="NI" <?php if (array_values((array)$ev)[0] == 'NI'){ echo 'selected';}?>>NI</option>
                                                </select>
                                            </td>
                                             <?php }?>
                                        </tr>

                                        <?php }?>
                                            <tr>
                                                <td>No. of tardy days</td>
                                                <td><input type="number" class="form-control" name="first_sem_tardy" value="<?php echo isset($student_evaluation->first_sem_tardy)?$student_evaluation->first_sem_tardy:0;?>" required></td>
                                                <td><input type="number" class="form-control" name="second_sem_tardy" value="<?php echo isset($student_evaluation->second_sem_tardy)?$student_evaluation->second_sem_tardy:0;?>" required></td>
                                            </tr>
                                            <tr>
                                                <td>No. of absent days</td>
                                                <td><input type="number" class="form-control" name="first_sem_absent" value="<?php echo isset($student_evaluation->first_sem_absent)?$student_evaluation->first_sem_absent:0;?>" required></td>
                                                <td><input type="number" class="form-control" name="second_sem_absent" value="<?php echo isset($student_evaluation->second_sem_absent)?$student_evaluation->second_sem_absent:0;?>" required></td>
                                            </tr>
                                        <?php }?>
                                        <?php if (empty($saved_evaluations)){?>
                                            <?php
                                            $k=0;
                                            $evaluations = (new \App\Models\Evaluations())->orderBy('id', 'DESC')->findAll();
                                            foreach ($evaluations as $evaluation){
                                                ?>
                                                <tr>
                                                    <td class="evaluate"><?php echo $evaluation['description'];?></td>
                                                    <?php foreach ($semesters as $sem){
                                                        $k++;
                                                        ?>
                                                        <td>
                                                            <select name="remark[<?php echo $evaluation['id'];?>][<?php echo $sem->id;?>][<?php echo $k;?>]" class="form-control" required>

                                                                <option value="E">E</option>
                                                                <option value="V">V.G</option>
                                                                <option value="G">G</option>
                                                                <option value="S">S</option>
                                                                <option value="NI">NI</option>
                                                            </select>
                                                        </td>
                                                    <?php }?>
                                                </tr>
                                            <?php }?>
                                        <tr>
                                            <td>No. of tardy days</td>
                                            <td><input type="number" class="form-control" name="first_sem_tardy" value="0" required></td>
                                            <td><input type="number" class="form-control" name="second_sem_tardy" value="0" required></td>
                                        </tr>
                                        <tr>
                                            <td>No. of absent days</td>
                                            <td><input type="number" class="form-control" name="first_sem_absent" value="0" required></td>
                                            <td><input type="number" class="form-control" name="second_sem_absent" value="0" required></td>
                                        </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                </div>


            <div style="text-align: right;">
                <a href="javascript:void(0)" class="previous_ev">&laquo; Previous</a>
                <a href="javascript:void(0)" class="next_ev">Next &raquo;</a>
            </div>

</div>
<style>

    hr {
        color: #0000004f;
        margin-top: 5px;
        margin-bottom: 5px
    }

    .add td {
        color: #c5c4c4;
        text-transform: uppercase;
        font-size: 12px
    }

    .content {
        font-size: 14px
    }
    .table td, .table th{
        white-space: revert !important;
    }
    .evaluate{
        width: 50% !important;
    }
    .next_ev {
        background-color: #04AA6D;
        color: white;
    }
    a {
        text-decoration: none;
        display: inline-block;
        padding: 8px 16px;
    }
    a:hover {
        background-color: #ddd;
        color: black;
    }
    .previous_ev {
        background-color: #f1f1f1;
        color: black;
    }


</style>


<script>
    var sess = "<?php echo $session->id;?>";
    var student = "<?php echo $student->id;?>";
    $('.next_ev').on('click',function (){
        next(sess,student);
    })
    $('.previous_ev').on('click',function (){
        back(sess,student);
    })
    function next(session,student) {
        var d = {
            url: "<?php echo site_url(route_to('parent.student.certificate.evaluation_summary')); ?>",
            loader: true,
            data: "year="+session+"&student="+student
        };
        ajaxRequest(d, function (data) {
            $('.evaluation').html(data);
        })
    }
    function back(session,student) {
        var d = {
            url: "<?php echo site_url(route_to('parent.student.certificate.view')); ?>",
            loader: true,
            data: "year="+session+"&student="+student
        };
        ajaxRequest(d, function (data) {
            $('.evaluation').html(data);
        })
    }
</script>