<?php
$section = (new \App\Models\Sections())->find($student->section->id);
$class = (new \App\Models\Classes())->find($student->class->id);
$std_id = $student->id;
if(isset($session) && is_numeric($session)) {
    $session = (new \App\Models\Sessions())->find($session);
} else {
    $session = getSession();
}

$semesters = (new \App\Models\Semesters())->where('session',active_session())->findAll();
$student_evaluation = (new \App\Models\StudentEvaluation())->where('student',$student->id)->where('session',active_session())->where('class',$class->id)->where('section',$section->id)->get()->getLastRow();
$saved_evaluations = isset($student_evaluation->remark) ? json_decode($student_evaluation->remark) : [];
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Yearly Certificate</h6><br/>
                    <span class="text-white"><?php echo $student->profile->name;?></span><br/>
                    <span class="text-white"><?php echo $student->class->name; ?></span><br/>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('admin.academic.yearly_certificate.print', $student->id)); ?>">Print</a>
                    <a class="btn btn-sm btn-warning" target="_blank" href="<?php echo site_url(route_to('admin.academic.yearly_certificate.download-cert', $student->id)); ?>">Download Pdf</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="card">
            <div class="card-body">
                <div class="container mt-5 mb-3">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <form action="<?php echo site_url(route_to('admin.academic.yearly_certificate.save_student_evaluation'));?>" method="post">
                                    <input type="hidden" name="student" value="<?php echo $student->id;?>">
                                    <input type="hidden" name="class" value="<?php echo $class->id;?>">
                                    <input type="hidden" name="section" value="<?php echo $section->id;?>">
                                    <input type="hidden" name="evaluation_id" value="<?php echo isset($student_evaluation->id) ? $student_evaluation->id :'';?>">

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


                                  <div style="text-align: center;padding-top: 1%;">
                                      <button type="submit" class="btn btn-success">Save</button>
                                  </div>
                                </form>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>

            <div style="text-align: right;">
                <a href="<?php echo site_url(route_to('teacher.academic.yearly_certificate.view', $student->id)); ?>" class="previous">&laquo; Previous</a>
                <a href="<?php echo site_url(route_to('teacher.academic.yearly_certificate.evaluation_summary', $student->id)); ?>" class="next">Next &raquo;</a>
            </div>
        </div>

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

    .next {
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
    .previous {
        background-color: #f1f1f1;
        color: black;
    }
</style>

