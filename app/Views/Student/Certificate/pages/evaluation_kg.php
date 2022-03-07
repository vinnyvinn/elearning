<div class="evaluation_kg">
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
?>

      <div class="table-responsive">
                                        <?php $categories = (new \App\Models\KGCategory())->findAll();?>
                                        <?php foreach ($categories as $category):?>
                                        <div class="text-center mt-1">

                                            Development Area: <b><?php echo $category['name'];?></b>
                                        </div>
                                           <?php if ($category['sub_category_id']):
                                                $sub_categories = json_decode($category['sub_category_id']);
                                            foreach ($sub_categories as $sub):
                                            $evaluations_ = (new \App\Models\KGEvaluation())->where('sub_category_id',$sub)->findAll();
                                            $sub_category = (new \App\Models\KGSubCategory())->find($sub);
                                            ?>
                                                <br>
                                        <table class="table table-bordered table-striped" style="width: 100%">
                                             <tr>
                                               <td class="text-center" width="50%">Attribute</td>
                                               <td colspan="2" class="text-center">Level</td>
                                             </tr>
                                            <tr>
                                            <th><?php echo $sub_category['name'];?></th>
                                             <td class="text-center">Sem-I</td>
                                             <td class="text-center">Sem-II</td>
                                            </tr>

                                             <?php
                                             $k=0;
                                             foreach ($evaluations_ as $key => $evaluation):
                                               $k++;
                                             ?>
                                            <tr>
                                              <td><?php echo $evaluation['description'];?></td>
                                              <td>


                                                  <select name="remark[<?php echo $evaluation['id'];?>][<?php echo $k;?>][1]" class="form-control" required>

                                                      <option value="E" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k,'E')):?> selected="selected" <?php endif;?>>E</option>
                                                      <option value="V" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k,'V')):?> selected="selected" <?php endif;?>>V.G</option>
                                                      <option value="G" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k,'G')):?> selected="selected" <?php endif;?>>G</option>
                                                      <option value="S" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k,'S')):?> selected="selected" <?php endif;?>>S</option>
                                                      <option value="NI" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k,'NI')):?> selected="selected" <?php endif;?>>NI</option>
                                                  </select>
                                              </td>
                                              <td>

                                                  <select name="remark[<?php echo $evaluation['id'];?>][<?php echo $k;?>][2]" class="form-control" required>
                                                      <option value="E" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k,'E')):?> selected="selected" <?php endif;?>>E</option>
                                                      <option value="V" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k,'V')):?> selected="selected" <?php endif;?>>V.G</option>
                                                      <option value="G" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k,'G')):?> selected="selected" <?php endif;?>>G</option>
                                                      <option value="S" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k,'S')):?> selected="selected" <?php endif;?>>S</option>
                                                      <option value="NI" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k,'NI')):?> selected="selected" <?php endif;?>>NI</option>
                                                  </select>
                                              </td>
                                            </tr>
                                            <?php endforeach;?>
                                        </table>
                                            <?php endforeach;?>
                                        <?php else:
                                        $evaluations = (new \App\Models\KGEvaluation())->where('category_id',$category['id'])->findAll();
                                        ?>
                                                <br>
                                            <table class="table table-bordered table-striped" style="width: 100%">
                                                <tr>
                                                    <th>Attribute</th>
                                                    <th class="text-center">Sem-I</th>
                                                    <th class="text-center">Sem-II</th>
                                                </tr>
                                                <?php
                                                $k2=0;
                                                foreach ($evaluations as $key2 => $evaluation):
                                                    $k2++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $evaluation['description'];?></td>
                                                        <td>
                                                            <select name="remark[<?php echo $evaluation['id'];?>][<?php echo $k2;?>][1]" class="form-control" required>

                                                                <option value="E" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k2,'E')):?> selected="selected" <?php endif;?>>E</option>
                                                                <option value="V" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k2,'V')):?> selected="selected" <?php endif;?>>V.G</option>
                                                                <option value="G" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k2,'G')):?> selected="selected" <?php endif;?>>G</option>
                                                                <option value="S" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k2,'S')):?> selected="selected" <?php endif;?>>S</option>
                                                                <option value="NI" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],1,$k2,'NI')):?> selected="selected" <?php endif;?>>NI</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="remark[<?php echo $evaluation['id'];?>][<?php echo $k2;?>][2]" class="form-control" required>
                                                                <option value="E" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k2,'E')):?> selected="selected" <?php endif;?>>E</option>
                                                                <option value="V" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k2,'V')):?> selected="selected" <?php endif;?>>V.G</option>
                                                                <option value="G" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k2,'G')):?> selected="selected" <?php endif;?>>G</option>
                                                                <option value="S" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k2,'S')):?> selected="selected" <?php endif;?>>S</option>
                                                                <option value="NI" <?php if (getEvaluation($saved_evaluations,$evaluation['id'],2,$k2,'NI')):?> selected="selected" <?php endif;?>>NI</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php endforeach;?>
                                            </table>
                                        <?php endif;endforeach;?>
                                    </div>
       <div style="text-align: right;">
    <a href="javascript:void(0)" class="previous_kg_ev">&laquo; Previous</a>
    <a href="javascript:void(0)" class="next_kg_ev">Next &raquo;</a>
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
    .next_kg_ev {
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
    .previous_kg_ev{
        background-color: #f1f1f1;
        color: black;
    }

</style>

<script>
    var sess = "<?php echo $session->id;?>";
    var student = "<?php echo $student->id;?>";
    $('.next_kg_ev').on('click',function (){
     nextKg(sess,student);
    })
    $('.previous_kg_ev').on('click',function (){
     backKg(sess,student);
    })
    function nextKg(session,student) {
        var d = {
            url: "<?php echo site_url(route_to('student.certificate.evaluation_summary')); ?>",
            loader: true,
            data: "year="+session+"&student="+student
        };
        ajaxRequest(d, function (data) {
            $('.evaluation_kg').html(data);
        })
    }
    function backKg(session,student) {
        var d = {
            url: "<?php echo site_url(route_to('student.certificate.view')); ?>",
            loader: true,
            data: "year="+session+"&student="+student
        };
        ajaxRequest(d, function (data) {
            $('.evaluation_kg').html(data);
        })
    }
</script>