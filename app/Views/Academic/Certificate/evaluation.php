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
//echo '<pre>';
//foreach ($saved_evaluations as $k => $ev){
//    var_dump($k);
//}

//var_dump($saved_evaluations);
//var_dump($saved_evaluations[1]);

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
                                    <table class="table table-bordered table-striped" style="width: 100%;">
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

//                                                var_dump($trait);
//                                                exit();
                                             ?>
                                        <tr>
                                            <td class="evaluate"><?php echo $trait['description'];?></td>
                                            <?php foreach ($evaluation as $k => $ev) {
                                                ?>
                                            <td>
<!--                                                <select name="remark[--><?php //echo $evaluation['id'];?><!--][--><?php //echo $sem->id;?><!--][--><?php //echo $k;?><!--]" class="form-control" required>-->
                                                <select name="remark[<?php echo $key?>][<?php echo $k;?>][<?php echo array_keys((array)$ev)[0];?>]" class="form-control" required>
<!--                                                <select name="remark[--><?php //echo $key?><!--][--><?php //echo $k;?><!--][--><?php //echo array_keys((array)$ev)[0];?><!--]" class="form-control" required>-->
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
                <a href="<?php echo site_url(route_to('admin.academic.yearly_certificate.view', $student->id)); ?>" class="previous">&laquo; Previous</a>
                <a href="<?php echo site_url(route_to('admin.academic.yearly_certificate.evaluation_summary', $student->id)); ?>" class="next">Next &raquo;</a>
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
</style>