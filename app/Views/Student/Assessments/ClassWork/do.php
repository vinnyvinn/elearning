<?php
$options = (new \App\Models\AnswerOption())->findAll();
use App\Models\ClassWorkSubmissions; ?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Class Work : <?php echo $classwork->name; ?> : <?php echo $item->subject->name; ?></h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title m-0">
                Deadline: <?php echo $classwork->deadline->format('d/m/Y'); ?>
            </h4>
        </div>
    </div>
<!--    <form action="--><?php //echo site_url(route_to('student.assessments.classwork.submit',$classwork->id))?><!--" method="post">-->
    <?php
    $submit = TRUE;
    $showAnswers = FALSE;
    $model = new ClassWorkSubmissions();
    $existing_submission = FALSE;
    if($existing_submission = $model->where('class_work', $classwork->id)
        //->where('classwork_item', $item->id)
        ->where('subject', $item->subject->id)
        ->where('student_id', $student->id)
        ->get()->getFirstRow('object')) {
        ?>
        <div class="alert alert-success">
            You submitted this classwork on <?php echo date('d/m/Y \a\t H:i A', $existing_submission->submitted_on); ?>
        </div>
        <?php
        $submit = FALSE;
    }else {
        if($classwork->deadline->addDays(5)->addHours(23)->addMinutes(59)->getTimestamp() < time()) {
            $submit = FALSE;
            $showAnswers = TRUE;
            ?>
            <div class="alert alert-danger">
                Unfortunately deadline has passed. Please contact your teacher.
            </div>
            <?php
        }elseif($classwork->deadline->addHours(23)->addMinutes(59)->getTimestamp() < time()) {
            ?>
            <div class="alert alert-danger">
                Unfortunately deadline has passed. Your score will be half of what you attain up until <?php echo $classwork->deadline->addDays(5)->addHours(23)->addMinutes(59)->format('d/m/Y H:i'); ?>
            </div>
            <?php
        }
    }
    if($classwork->deadline->addDays(5)->addHours(23)->addMinutes(59)->getTimestamp() < time()) {
        $showAnswers = TRUE;
    }
    if($item->items) {
        foreach ($item->items as $key=>$quiz) {
            $x = $key+1;

            ?>
            <div class="card">
                <div class="card-header mb-0">
                    <div class="item" style="background-color: #edeef2; padding:5px">
                       <?php
                        //var_dump($quiz->instructions);
                        //exit();
                        if ($quiz->instructions):?>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><b>Instructions</b>: <span style="text-decoration: underline"><?php echo $quiz->instructions;?></span></p>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($quiz->precautions):?>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><b>Precautions</b>: <span style="text-decoration: underline"><?php echo $quiz->precautions;?></span></p>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                    <span>
                        <b>Q<?php echo $quiz->question_number; ?>.</b> <?php echo $quiz->question; ?>
                    </span>
                </div>
                <div class="card-body answers">
                    <?php
                    $quiz->answers = (array) $quiz->answers;
                    //d($quiz);
                    ?>
                    <div class="row">
                        <div class="col-md-8">
                            <ul style="list-style: none">
                                <?php
                                $answers = array_flatten(json_decode($quiz->answers[0]));
                                $corrects = json_decode($quiz->corrects);
                                $correct = '';
                                foreach ($corrects as $corr){
                                    $correct = $corr;
                                    break;
                                }

                                foreach ($options as $option){
                                    $opt_name = $option['name'];
                                foreach ($answers as $answer) {
                                    if(isset($answer->$opt_name)) {
                                        ?>
                                        <li>
                                            <label>
                                                <input type="radio" <?php
                                                if($showAnswers) {
                                                    if($opt_name == $correct) {
                                                        echo 'checked="checked" class="custom-checkbox custom-checkbox-success"';
                                                    }
                                                }
                                                ?> name="<?php echo $x; ?>" data-value="<?php echo $opt_name; ?>" data-question="<?php echo $x-1; ?>" value="<?php echo $key; ?>" />
                                                <?php echo $opt_name ?>. <?php echo $answer->$opt_name; ?>
                                            </label>
                                        </li>
                                        <?php
                                    }
                                }
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <?php
                            if(isset($quiz->image)) {
                                ?>
                                <img src="<?php echo $quiz->image ?>" style="max-height: 200px; width: auto" />
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    if($showAnswers) {
                        ?>
                        <div class="alert border-info">
                            <?php echo $quiz->explanation; ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <?php
    if($submit) {
        ?>
        <div class="card">
            <div class="card-body">
                <button class="btn btn-success" onclick="submitClasswork()">Submit</button>
            </div>
        </div>
        <?php
    }
    ?>
<!--    </form>-->
</div>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>

<script>
    function submitClasswork() {
        var responses = {};
        var check = true;
        var index = 0;
        $(".answers").each(function () {
            index++;
            var row = {};
            $(this).find('input:radio').each(function () {
                var name = $(this).attr("name");
                if($("input:radio[name="+name+"]:checked").length == 0){
                    check = false;
                }
                if($(this).is(':checked')) {
                    row[$(this).attr('data-question')] = $(this).attr('data-value');
                    responses[$(this).attr('data-question')] = $(this).attr('data-value');
                }
            });

            //responses.push(row);
        });
        if(check) {
            //console.log(responses);
            var form = {
                answers: responses,
                classwork: "<?php echo $classwork->id; ?>",
                item: "<?php echo $item->id; ?>"
            };
            var e = {
                url: "<?php echo site_url(route_to('student.assessments.classwork.submit', $classwork->id, $item->id)); ?>",
                data: JSON.stringify(form),
                loader: true
            };
            ajaxRequest(e, function (iss) {
                serverResponse(iss);
            });
        } else {
            notify("Warning", "You have not answered all questions", "warning");
        }
    }
</script>