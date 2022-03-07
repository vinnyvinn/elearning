<?php
$options = (new \App\Models\AnswerOption())->findAll();
//d($exam);
//$classid = $exam->class->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Create Exam</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div>
                <div>
                    <?php
                    //Check existing
                    //$existing = (new \App\Models\CatExamItems())->where('cat_exam', $exam->id)->where('class', $class->id)->where('subject', $subject->id)->get()->getFirstRow('\App\Entities\CatExamItem');
                    $existing = $exam;
                    ?>
                    <form class="customSubmitiii" method="post" data-parsley-validate
                          action="<?php echo site_url(route_to('admin.academic.assessments.exams.save_edit', $exam->id)); ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Semester</label>
                                    <select class="form-control form-control-sm" name="semester" required>
                                        <option value="">Select semester</option>
                                        <?php
                                        $semesters = @getSession()->semesters;
                                        if($semesters && count($semesters) > 0) {
                                            foreach ($semesters as $semester) {
                                                ?>
                                                <option <?php echo $exam->semester->id == $semester->id ? 'selected' : ''; ?> value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Class</label>
                                    <select name="class" id="cls_class_id" class="form-control form-control-sm"
                                            onchange="getSubjects($(this).val())" required>
                                        <option value="">Select a class</option>
                                        <?php
                                        $classes = getSession()->classes()->findAll();

                                        foreach ($classes as $class) {
                                            ?>
                                            <option <?php echo $exam->class->id == $class->id ? 'selected' : ''; ?> value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Subject</label>
                                    <select id="subject_id" name="subject" class="form-control form-control-sm" required>
                                        <?php
                                        $subjects = $exam->class->subjects;
                                        foreach ($subjects as $subject) {
                                            ?>
                                            <option <?php echo $exam->subject->id == $subject->id ? 'selected' : ''; ?> value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sess">Starting Date</label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="given"
                                           value="<?php echo old('given', $exam->given->format('m/d/Y')) ?>" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sess">Ending Date</label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="deadline"
                                           value="<?php echo old('deadline', $exam->deadline->format('m/d/Y')) ?>" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sess">Exam Name</label>
                            <input type="text" class="form-control form-control-sm" name="name"
                                   value="<?php echo old('name', $exam->name) ?>" required/>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Out of</label>
                                    <input type="number" class="form-control form-control-sm" min="0" step="1" name="out_of"
                                           value="<?php echo old('out_of', $exam->out_of); ?>" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Duration in Minutes</label>
                                    <input type="number" class="form-control form-control-sm" min="0" step="1" name="duration"
                                           value="<?php echo old('duration', $exam->duration); ?>" required/>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div id="theQuestions">
                            <?php
                            if($existing && $existing->items && count($existing->items) > 0) {
                                foreach ($existing->items as $key => $item) {
                                    ?>
                                    <div class="item" style="background-color: #edeef2; padding:5px">
                                        <button type="button" id="removeRow" class="btn btn-sm pull-right float-right btn-danger">Remove</button>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Question #</label>
                                                    <input type="number" name="question_number[<?php echo $key;?>]" min="1" value="<?php echo @$item->question_number; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Instructions</label>
                                                    <textarea name="instructions[<?php echo $key;?>]" id="instructions" cols="20" class="form-control" rows="1"><?php echo @$item->instructions; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Precautions</label>
                                                    <textarea name="precautions[<?php echo $key;?>]" id="precaution" cols="20" class="form-control" rows="1"><?php echo @$item->precautions; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Question</label>
                                            <textarea name="question[<?php echo $key;?>]" class="form-control" rows="2" placeholder="Type a question..."><?php echo @$item->question; ?></textarea>
                                        </div>
<!--                                        <div class="row">-->
<!--                                            <div class="col-md-6">-->
<!--                                                <div class="form-group">-->
<!--                                                    <label>Image</label>-->
<!--                                                    <input type="file" name="image" src="--><?php //echo @$item->image; ?><!--" onchange="readTheImage(this)" accept="image/*" class="form-control" />-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="col-md-6">-->
<!---->
<!--                                                <img id="xPrev" src="--><?php //echo @$item->image; ?><!--" style="max-height: 200px; width: auto" />-->
<!---->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div>
                                            <label>Answers</label>
                                            <table class="table table-sm table-borderless">
                                                <thead>
                                                <tr>
                                                    <th width="3%">#</th>
                                                    <th width="10%">Correct</th>
                                                    <th>Answer Option</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $answers = array_flatten(json_decode($item->answers));
                                                foreach ($options as $option):
                                                    $opt_name = $option['name'];
                                                    foreach ($answers as $answer):
                                                        if (isset($answer->$opt_name)):
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $opt_name;?></td>
                                                                <td>
                                                                    <label><input
                                                                                type="checkbox" <?php echo isset(json_decode($item->corrects)[0]) && json_decode($item->corrects)[0] == $opt_name ? 'checked' : ''; ?>
                                                                                title="Check if this is the correct answer"
                                                                                name="correct[][][<?php echo $opt_name;?>]"/>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control form-control-sm" name="answers[][][<?php echo $opt_name;?>]"
                                                                               value="<?php echo isset($answer->$opt_name) && $answer->$opt_name ? $answer->$opt_name: ''; ?>"/>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif;endforeach;endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group explanation_temp">
                                            <label>Answer Explanation</label>
                                            <textarea name="explanation[<?php echo $key;?>]" class="form-control" rows="3"><?php echo isset($item->explanation) ? $item->explanation : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="item" style="background-color: #edeef2; padding:5px">
                                    <div class="form-group">
                                        <label>Question</label>
                                        <textarea name="question" class="form-control" rows="2"
                                                  placeholder="Type a question..."></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Image</label>
                                                <input type="file" name="image" src="" onchange="readTheImage(this)" accept="image/*" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <img id="xPrev" src="" style="max-height: 200px; width: auto" />

                                        </div>
                                    </div>
                                    <div>
                                        <label>Answers</label>
                                        <table class="table table-sm table-borderless">
                                            <thead>
                                            <tr>
                                                <th width="3%">#</th>
                                                <th width="10%">Correct</th>
                                                <th>Answer Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($options as $option):?>
                                            <tr>
                                                <td><?php echo $option['name'];?></td>
                                                <td>
                                                    <label><input type="checkbox" title="Check if this is the correct answer"
                                                                  name="correct[<?php echo $option['name']?>]"</label>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control form-control-sm" name="answers[<?php echo $option['name'];?>]" rows="2"/>
                                                    </div>
                                                </td>
                                            </tr>
                                           <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <label>Answer Explanation</label>
                                        <textarea name="explanation" class="form-control" rows="3"><?php echo isset($item->explanation) ? $item->explanation : ''; ?></textarea>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="mt-1"></div>
                        <button class="btn btn-success btn-sm" type="button" id="addRow"><i class="fa fa-plus"></i> Add Question
                        </button>
                        <button type="submit" class="btn btn-primary btn-block mt-4">Save exam</button>
                    </form>
                </div>
                <div class="hidden" style="display: none" id="questionTemplates">
                    <div class="item" style="background-color: #edeef2; padding:5px">
                        <button type="button" id="removeRow" class="btn btn-sm pull-right float-right btn-danger">Remove</button>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Question #</label>
                                    <input type="number" name="question_number[]" min="1" value="1" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Instructions</label>
                                    <textarea name="instructions[]" id="instructions" cols="20" class="form-control" rows="1"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Precautions</label>
                                    <textarea name="precautions[]" id="precaution" cols="20" class="form-control" rows="1"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group question_temp">
                            <label>Question</label>
                            <textarea name="question[]" class="form-control" rows="2"
                                      placeholder="Type a question..."></textarea>
                        </div>

<!--                        <div class="row">-->
<!--                            <div class="col-md-6">-->
<!--                                <div class="form-group">-->
<!--                                    <label>Image</label>-->
<!--                                    <input type="file" name="image" src="" onchange="readTheImage(this)" accept="image/*" class="form-control" />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-md-6">-->
<!---->
<!--                                <img id="xPrev" src="" style="max-height: 200px; width: auto" />-->
<!---->
<!--                            </div>-->
<!--                        </div>-->
                        <div>
                            <label>Answers</label>
                            <table class="table table-sm table-borderless">
                                <thead>
                                <tr>
                                    <th width="3%">#</th>
                                    <th width="10%">Correct</th>
                                    <th>Answer Option</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($options as $option):?>
                                    <tr>
                                        <td><?php echo $option['name']?></td>
                                        <td>
                                            <label><input type="checkbox" title="Check if this is the correct answer"
                                                          name="correct[][][<?php echo $option['name']?>]"/>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" name="answers[][][<?php echo $option['name'];?>]">
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group explanation_temp">
                            <label>Answer Explanation</label>
                            <textarea name="explanation[]" class="form-control" rows="3"><?php echo isset($item->explanation) ? $item->explanation : ''; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>

<?php
if($existing && $existing->items && count($existing->items) > 0) {

    foreach ($existing->items as $key => $item) {
        ?>
        <script>
            var k = '<?php echo $key?>';
            CKEDITOR.replace("question[" + k + "]");
            CKEDITOR.replace("explanation[" + k + "]");
        </script>

    <?php }}?>
<script>
    var getSubjects = function (classId) {
        //var classId = $('#class').val();
        if (classId == '') {
            toast('Error', 'Please select a class', 'error');
        } else {
            //getSections();
            var data = {
                url: "<?php echo site_url('ajax/class/') ?>" + classId + "/subjects",
                data: "class=" + classId,
                loader: true
            };
            ajaxRequest(data, function (data) {
                $('#subject_id').html(data);
            });
        }
    };

    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('.item').remove();
    });

    $(document).on('click', '#addRow', function (e) {
        e.preventDefault();
        var the_html = ""; //unset. Memory is the b word
        the_html = $('#questionTemplates').html();
        $("#theQuestions").append(the_html);
        getQuestions();
    });

    //TODO: Ensure only one checbox is checked
    // $(document).on('change', '#theQuestions .item input[type="checkbox"]', function (e) {
    //     $(this).parents('.item checkbox').removeAttr('checked');
    //     $(this).attr('checked', 'checked');
    // });

    $('.customSubmit').submit(function (e) {
        e.preventDefault();
        var index = 1;
        var exam = [];
        $('#theQuestions .item').each(function () {
            var row = {};

            $(this).find('input,select,textarea').each(function () {
                if ($(this).is(':checkbox')) {
                    row[$(this).attr('name')] = $(this).is(':checked');
                } else {
                    if($(this).attr('type') == 'file') {
                        row['image'] = $(this).attr('src');
                    } else {
                        row[$(this).attr('name')] = $(this).val();
                    }
                }
            });
            row['index'] = index;
            index++;
            exam.push(row);
        });
        // var fd = new FormData();
        // fd.append('exam', JSON.stringify(exam));
        // fd.append('out_of', $('input[name="out_of"]').val());
        var data = {
            exam: exam,
            out_of: $('input[name="out_of"]').val(),
            duration: $('input[name="duration"]').val(),
            classid: $('select[name="class"]').val(),
            class: $('select[name="class"]').val(),
            semester: $('select[name="semester"]').val(),
            subject: $('select[name="subject"]').val(),
            given: $('input[name="given"]').val(),
            deadline: $('input[name="deadline"]').val(),
            name: $('input[name="name"]').val(),
        };
        var e = {
            data: JSON.stringify(data),
            loader: true,
            url: "<?php echo site_url(route_to('admin.academic.assessments.exams.save_edit', $exam->id)); ?>"
        }

        //Use fd as you would any form data.
        // Check /home/ben/HTML/Perfex/perfex_crm/modules/hrm/views/new.php:527 for an example
        server(e);
    })

    function readTheImage(node) {
        if (node.files && node.files[0]) {

            var FR= new FileReader();

            FR.addEventListener("load", function(e) {
                $(node).attr('src', e.target.result);
                $(node).parent().parent().parent().find('#xPrev').attr('src', e.target.result);
                //document.getElementById("b64").innerHTML = e.target.result;
            });

            FR.readAsDataURL( node.files[0] );
        }
    }
</script>

<script>
    function getQuestions() {
        var items = document.getElementsByClassName("question_temp");
        var items2 = document.getElementsByClassName("explanation_temp");
        for (let i = 1; i < items.length; i++) {
            if(i == items.length-2) {
                var el = '<label>Question</label><textarea name="question[' + i + ']" class="form-control question" rows="2" placeholder="Type a question..."></textarea>';
                items[i].innerHTML = el;
                CKEDITOR.replace("question[" + i + "]");
            }
        }

        for (let i = 1; i < items2.length; i++) {
            if(i == items2.length-2) {
                var el = '<label>Answer Explanation</label><textarea name="explanation[' + i + ']" class="form-control" rows="3"></textarea>';
                items2[i].innerHTML = el;
                CKEDITOR.replace("explanation[" + i + "]");
            }
        }
    }
</script>
