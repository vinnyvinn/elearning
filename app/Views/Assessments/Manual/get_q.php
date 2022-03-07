<?php
$session = active_session();
$key = 'manual_cats_'.$session.'-'.$quarter->id.'-'.$class->id.'-'.$subject->id;
$keys = json_decode(get_option($key, json_encode([])), true);

$keys2 = $keys;

$p = 0;
$sess = (new \App\Models\ManualAssessments())->where('session', $session)->where('semester', $semester->id)->where('class', $class->id)->where('subject', $subject->id)->first();
if (!empty($sess) && !empty($results = json_decode($sess->results))){
    $to_arr_res = (array)$results;
    $res = array();
    $k1=0;
    foreach ($results as $k => $v)  {
        $res[$k] = $v;
        $k1 = $k;
    }

    $keys = $res;
    if (count($keys2) > count($to_arr_res)){
        $new_elements = array_slice($keys2,count($to_arr_res));
        foreach ($new_elements as $el){
            $k1++;
            $keys[$k1] = 0;
        }
    }
}

$keys3 = array();
foreach ($keys2 as $k2 => $v2){
    if (!isset($keys3[$k2])){
        $keys3[$k2] = $v2;
    }
}

$m_ass = (new \App\Models\ManualAssessments())->where('session',$session)->where('quarter',$quarter->id)->where('class',$class->id)->where('subject',$subject->id)->findAll();

$model = (new \App\Models\Students())->where('students.session', active_session())->where('students.class',$class->id)
    ->select("students.*,students.id as id")
    ->join('users','users.id=students.user_id')
    ->join("sections","sections.id=students.section");
if (getDepartedIds() && count(getDepartedIds()) > 0)
    $model->whereNotIn("students.id",getDepartedIds());
$model->where('students.class !=',null)
    ->orderBy('sections.name,users.surname, users.first_name,users.last_name');
$students = $model->findAll();
?>
<div>
    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target=".new_cont_assessment">Create Cont. Assessment</button>
    <?php if (!empty($m_ass)):?>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".convert_assessment_total">Convert</button>
    <?php endif;?>
    <div class="modal fade new_cont_assessment" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.academic.assessments.manual.save_cas_q')) ?>">
                    <input type="hidden" name="session" value="<?php echo $session; ?>">
                    <input type="hidden" name="quarter" value="<?php echo $quarter->id; ?>"/>
                    <input type="hidden" name="class" value="<?php echo $class->id; ?>">
                    <input type="hidden" name="subject" value="<?php echo $subject->id; ?>">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Continuous Assessment</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <p>Create Continuous Assessment Entries for <?php echo $class->name; ?> on Subject <?php echo $subject->name; ?></p>
                        </div>
                        <h6>Assessment Names</h6>
                        <div id="appendContainer">
                            <?php if(!$keys2 || !is_array($keys2)):?>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <input class="form-control" name="item[]" value="" required="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success" onclick="domAppend()">+</button>
                                </div>
                            </div>
                            <?php endif;?>

                            <?php
                            $n = 0;
                                if($keys2 && is_array($keys2)) {?>
                                    <div class="col-md-12 add_assessment">
                                        <button type="button" class="btn btn-success" onclick="domAppend()">+</button>
                                    </div>
                                <?php foreach ($keys as $k3 => $key) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="item[<?php echo $k3;?>]" value="<?php echo $keys3[$n]; ?>" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger" id="appRemove">-</button>
                                        </div>
                                    </div>
                                    <?php
                                        $n++;
                                        $p=$k3;
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade convert_assessment_total" role="dialog" aria-labelledby="modal-default" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.academic.assessments.manual.save_cas_total_q')) ?>">
                    <input type="hidden" name="session" value="<?php echo $session; ?>">
                    <input type="hidden" name="quarter" value="<?php echo $quarter->id; ?>"/>
                    <input type="hidden" name="class" value="<?php echo $class->id; ?>">
                    <input type="hidden" name="subject" value="<?php echo $subject->id; ?>">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Convert Total</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="given_total">Given Total</label>
                            <input type="number" class="form-control" name="given_total" step="0.0001" required>
                        </div>
                        <div class="form-group">
                            <label for="desired_total">Des$keys2ired Total</label>
                            <input type="number" class="form-control" name="desired_total" step="0.0001" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br/><br/>
    <form class="ajaxForm" method="POST" loader="true" action="<?php echo site_url(route_to('admin.academics.assessments.manual.save_results_q')) ?>">
        <input type="hidden" name="session" value="<?php echo $session; ?>" />
        <input type="hidden" name="class" value="<?php echo $class->id; ?>" />
        <input type="hidden" name="subject" value="<?php echo $subject->id; ?>" />
        <input type="hidden" name="quarter" value="<?php echo $quarter->id; ?>"/>
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Section</th>
                    <?php
                    if($keys2 && is_array($keys2)) {
                        foreach ($keys2 as $key) {
                            ?>
                            <th><?php echo $key; ?></th>
                            <?php
                        }
                    }
                    ?>
                    <th>Total</th>
                    <th>Converted Total</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($students) {
                    $n = 0;
                    foreach ($students as $student) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $student->profile->name; ?></td>
                            <td><?php echo $student->section->name; ?></td>
                            <?php
                            $total = 0;

                            if($keys2 && is_array($keys2)) {
                               // exit();
                                //Looks for results
                                $res = (new \App\Models\ManualAssessments())->where('session', $session)->where('class', $class->id)
                                    ->where('quarter', $quarter->id)
                                    ->where('student', $student->id)
                                    ->where('subject', $subject->id)->get()->getLastRow();
                                 $marks = @json_decode($res->results, true);

                                foreach ($keys as $key=>$value) {
                                    $total += (is_array($marks) && isset($marks[$key])) ? $marks[$key] : 0;
                                    ?>
                                    <td>
                                 <input type="number" min="0" step="0.001" class="form-control form-control-sm" name="student[<?php echo $student->id; ?>][<?php echo $key; ?>]" value="<?php echo (is_array($marks) && isset($marks[$key])) ? $marks[$key] : ''; ?>" required />
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                            <td>
                                <?php echo $total; ?>
                            </td>
                            <td>
                                <?php
                                $conv =(new \App\Models\ManualAssessments())->where('session', $session)->where('class', $class->id)
                                    ->where('quarter', $quarter->id)
                                    ->where('student', $student->id)
                                   // ->where('section', $section->id)
                                    ->where('subject', $subject->id)->get()->getLastRow();
                               echo isset($conv->converted_total) ? $conv->converted_total : 0;
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
<div style="display: none" id="appendHolder">
    <div class="row">
        <div class="col-md-10">
            <div class="form-group">
                <input class="form-control" name="item[]" value="" required="">
            </div>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger" id="appRemove">-</button>
        </div>
    </div>
</div>
<script>
    var o = "<?php echo $p;?>"
    function domAppend() {
        o++;
        var d = '<div class="row">';
        d+='<div class="col-md-10">';
        d+='<div class="form-group">';
        d+='<input class="form-control" name="item['+o+']" value="" required="">';
        d+='</div></div><div class="col-md-2"> <button type="button" class="btn btn-danger" id="appRemove">-</button></div></div>';
        var html = $('#appendHolder').html();
        $('#appendContainer').append(d);
    }

    $(document).on('click', '#appRemove', function() {
        o--;
        $(this).parent().parent('.row').remove();
    })
</script>
