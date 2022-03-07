<?php
$options = (new \App\Models\AnswerOption())->findAll();
use App\Models\CatExamSubmissions;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Exam : <?php echo $exam->name; ?> : <?php echo $exam->subject->name; ?></h6>
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
                Deadline: <?php echo $exam->deadline->format('d/m/Y'); ?>
                <span class="pull-right float-right">Time Remaining: <span id="timer"></span></span>
            </h4>
        </div>
    </div>
    <?php
    $showAnswers = FALSE;
    $showTimer = TRUE;
    $submit = TRUE;
    $model = new CatExamSubmissions();
    $existing_submission = FALSE;
    if($existing_submission = $model->where('cat_exam', $exam->id)
        //->where('cat_exam_item', $item->id)
        ->where('subject', $item->subject->id)
        ->where('student_id', $student->id)
        ->get()->getFirstRow('object')) {
        ?>
        <div class="alert alert-success">
            You submitted this exam on <?php echo date('d/m/Y \a\t H:i A', $existing_submission->submitted_on); ?>
            and scored <b><?php echo $existing_submission->score.' marks'; ?></b>
        </div>
        <?php
        $submit = FALSE;
        $showTimer = FALSE;
    } else {
        if(time() > $exam->deadline->addHours(23)->addMinutes(59)->getTimestamp()) {
            $showTimer = FALSE;
            $showAnswers = TRUE;
            $submit = FALSE;
            ?>
            <div class="alert alert-danger">
                Deadline passed. Please contact your teacher
            </div>
            <?php
        }
    }
    if($exam->deadline->addHours(23)->addMinutes(59)->getTimestamp() < time()) {
        $showTimer = FALSE;
        $showAnswers = TRUE;
        $submit = FALSE;
    }
    if($showTimer) {
        ?>
        <script>
            $(document).ready(function () {
                var d = new Date();
                d.setMinutes(d.getMinutes() + <?php echo $item->duration; ?>);
                //d.setSeconds(d.getSeconds() + 5);
                $('#timer').tinyTimer({ to: d,
                    onEnd: function () {
                        submitquiz(false);
                        notify("Time is up!", "", 'warning', "Ok");
                    }
                });
            })
        </script>
        <?php
    }
    if($item->items) {
        foreach ($item->items as $key=>$question) {
            $x = $key+1;
            ?>
            <div class="card">
                <div class="card-header mb-0">
                    <div class="item" style="background-color: #edeef2; padding:5px">
                        <?php
                        if ($question->instructions):?>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><b>Instructions</b>: <span style="text-decoration: underline"><?php echo $question->instructions;?></span></p>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($question->precautions):?>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><b>Precautions</b>: <span style="text-decoration: underline"><?php echo $question->precautions;?></span></p>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                    <span>
                        <b>Q<?php echo $question->question_number; ?>.</b> <?php echo $question->question; ?>
                    </span>
                </div>
                <div class="card-body answers">
                    <?php
                    $question->answers = (array) $question->answers;
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <ul style="list-style: none">
                                <?php
                                $answers = array_flatten(json_decode($question->answers[0]));
                                $corrects = json_decode($question->corrects);
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
                                                    ?> <?php echo !$submit ? 'disabled readonly' : ''; ?> name="<?php echo $x; ?>" data-value="<?php echo $opt_name; ?>" data-question="<?php echo $x-1; ?>" value="<?php echo $key; ?>" />
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
                        <div class="col-md-6">
                            <?php
                            if(isset($question->image)) {
                                ?>
                                <img src="<?php echo $question->image; ?>" style="max-height: 200px;" />
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    if($showAnswers) {
                        ?>
                        <div class="alert border-info">
                            <?php
                            echo @$question->explanation;
                            ?>
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
                <button class="btn btn-success" onclick="submitquiz()">Submit</button>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<script>
    function submitquiz(loader = true) {
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
        //check = true; //Umm, we dont care no more.
        if(check || !loader) {
            //console.log(responses);
            var form = {
                answers: responses,
                exam: "<?php echo $exam->id; ?>",
                item: "<?php echo $item->id; ?>"
            };
            var e = {
                url: "<?php echo site_url(route_to('student.assessments.exam.submit', $exam->id, $item->id)); ?>",
                data: JSON.stringify(form),
                loader: loader
            };
            ajaxRequest(e, function (iss) {
                serverResponse(iss);
            });
        } else {
            notify("Warning", "You have not answered all questions", "warning");
        }
    }
</script>