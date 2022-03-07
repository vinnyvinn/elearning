<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Edit Assignment</h6>
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
                    //$existing = (new \App\Models\QuizItems())->where('quiz', $quiz->id)->where('class', $quiz->class->id)->where('subject', $subject->id)->get()->getFirstRow('\App\Entities\QuizItem');
                    $existing = $quiz;
                    ?>
                    <form class="customSubmiti" method="post" data-parsley-validate
                          action="<?php echo site_url(route_to('admin.academic.assignments.save_edit', $quiz->id)); ?>">
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
                                                <option <?php echo $quiz->semester->id == $semester->id ? 'selected' : ''; ?> value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
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
                                            <option <?php echo $quiz->class->id == $class->id ? 'selected' : ''; ?> value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
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
                                        $subjects = $quiz->class->subjects;
                                        foreach ($subjects as $subject) {
                                            ?>
                                            <option <?php echo $quiz->subject->id == $subject->id ? 'selected' : ''; ?> value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
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
                                    <label for="sess">Given Date</label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="given"
                                           value="<?php echo old('given', $quiz->given->format('m/d/Y')) ?>" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sess">Deadline</label>
                                    <input type="text" class="form-control form-control-sm datepicker" name="deadline"
                                           value="<?php echo old('deadline', $quiz->deadline->format('m/d/Y')) ?>" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sess">Assignment Name</label>
                            <input type="text" class="form-control form-control-sm" name="name"
                                   value="<?php echo old('name', @$quiz->name);?>" required/>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Out of</label>
                                    <input type="number" class="form-control form-control-sm" min="0" step="1" name="out_of"
                                           value="<?php echo old('out_of', @$existing->out_of); ?>" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Duration in Minutes</label>
                                    <input type="number" class="form-control form-control-sm" min="0" step="1" name="duration"
                                           value="<?php echo old('duration', @$existing->duration); ?>" required/>
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
                                        <div class="form-group question_temp">
                                            <label>Question</label>
                                            <textarea name="question[<?php echo $key;?>]" class="form-control" rows="2" placeholder="Type a question..."><?php echo @$item->question; ?></textarea>
                                        </div>
<!--                                        <div class="row">-->
<!--                                            <div class="col-md-6">-->
<!--                                                <div class="form-group">-->
<!--                                                    <label>Image</label>-->
<!--                                                    <input type="file" name="image" src="" onchange="readTheImage(this)" accept="image/*" class="form-control" />-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="col-md-6">-->
<!---->
<!--                                                <img id="xPrev" src="--><?php //echo base_url("uploads/assignments/".@$item->image); ?><!--" style="max-height: 200px; width: auto" />-->
<!---->
<!--                                            </div>-->
<!--                                        </div>-->
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
                                        <textarea name="question[]" class="form-control" rows="2"
                                                  placeholder="Type a question..."></textarea>
                                    </div>
<!--                                    <div class="row">-->
<!--                                        <div class="col-md-6">-->
<!--                                            <div class="form-group">-->
<!--                                                <label>Image</label>-->
<!--                                                <input type="file" name="image" src="" onchange="readTheImage(this)" accept="image/*" class="form-control" />-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="colimg-md-6">-->
<!---->
<!--                                            <img id="xPrev" src="" style="max-height: 200px; width: auto" />-->
<!---->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="form-group">
                                        <label>Answer Explanation</label>
                                        <textarea name="explanation" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="mt-1"></div>
                        <button class="btn btn-success btn-sm" type="button" id="addRow"><i class="fa fa-plus"></i> Add Question
                        </button>
                        <button type="submit" class="btn btn-primary btn-block mt-4">Save assignment</button>
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
                            <textarea name="question[]" class="form-control" rows="2" placeholder="Type a question..."></textarea>
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
                        <div class="form-group explanation_temp">
                            <label>Answer Explanation</label>
                            <textarea name="explanation[]" class="form-control" rows="3"></textarea>
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
        var quiz = [];
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
            quiz.push(row);
        });
        // var fd = new FormData();
        // fd.append('quiz', JSON.stringify(quiz));
        // fd.append('out_of', $('input[name="out_of"]').val());
        var data = {
            quiz: quiz,
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
            url: "<?php echo site_url(route_to('admin.academic.assignments.save_edit', $quiz->id)); ?>"
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
