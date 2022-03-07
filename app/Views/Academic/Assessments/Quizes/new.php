<?php
//d($quiz);
?>
<div>
    <p>
        Creating quiz for <?php echo $quiz->class->name; ?> for
        subject <?php echo $subject->name; ?>
    </p>
    <?php
    //Check existing
    $existing = (new \App\Models\QuizItems())->where('quiz', $quiz->id)->where('class', $quiz->class->id)->where('subject', $subject->id)->get()->getFirstRow('\App\Entities\QuizItem');
    ?>
    <form class="customSubmit" method="post" data-parsley-validate
          action="<?php echo site_url(route_to('admin.academic.assessments.class_work.new_quiz_save', $quiz->id)); ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Out of</label>
                    <input type="number" class="form-control" min="0" step="1" name="out_of"
                           value="<?php echo old('out_of', @$existing->out_of); ?>" required/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Duration in Minutes</label>
                    <input type="number" class="form-control" min="0" step="1" name="duration"
                           value="<?php echo old('duration', @$existing->duration); ?>" required/>
                </div>
            </div>
        </div>
        <hr/>
        <div id="theQuestions">
            <?php
            if($existing && $existing->items && count($existing->items) > 0) {
                foreach ($existing->items as $item) {
                    ?>
                    <div class="item" style="background-color: #edeef2; padding:5px">
                        <button type="button" id="removeRow" class="btn btn-sm pull-right float-right btn-danger">Remove</button>
                        <div class="form-group">
                            <label>Question</label>
                            <textarea name="question" class="form-control" rows="2" placeholder="Type a question..."><?php echo @$item->question; ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" name="image" src="<?php echo @$item->image; ?>" onchange="readTheImage(this)" accept="image/*" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">

                                    <img id="xPrev" src="<?php echo @$item->image; ?>" style="max-height: 200px; width: auto" />

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
                                <tr>
                                    <td>A</td>
                                    <td>
                                        <label><input type="checkbox" <?php echo @$item->corrects->A ? 'checked' : ''; ?> title="Check if this is the correct answer"
                                                      name="correct[A]"</label>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" name="answers[A]" value="<?php echo $item->answers->A; ?>" rows="2"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>B</td>
                                    <td>
                                        <label><input type="checkbox"<?php echo @$item->corrects->B ? 'checked' : ''; ?> title="Check if this is the correct answer"
                                                      name="correct[B]"</label>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" name="answers[B]" value="<?php echo $item->answers->B; ?>" rows="2"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>C</td>
                                    <td>
                                        <label><input type="checkbox" <?php echo @$item->corrects->C ? 'checked' : ''; ?> title="Check if this is the correct answer"
                                                      name="correct[C]"</label>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" name="answers[C]" value="<?php echo $item->answers->C; ?>" rows="2"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>D</td>
                                    <td>
                                        <label><input type="checkbox" <?php echo @$item->corrects->D ? 'checked' : ''; ?> title="Check if this is the correct answer"
                                                      name="correct[D]"</label>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" name="answers[D]" value="<?php echo $item->answers->D; ?>" rows="2"/>
                                        </div>
                                    </td>
                                </tr>
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
                            <tr>
                                <td>A</td>
                                <td>
                                    <label><input type="checkbox" title="Check if this is the correct answer"
                                                  name="correct[A]"</label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" name="answers[A]" rows="2"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>B</td>
                                <td>
                                    <label><input type="checkbox" title="Check if this is the correct answer"
                                                  name="correct[B]"</label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" name="answers[B]" rows="2"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>C</td>
                                <td>
                                    <label><input type="checkbox" title="Check if this is the correct answer"
                                                  name="correct[C]"</label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" name="answers[C]" rows="2"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>D</td>
                                <td>
                                    <label><input type="checkbox" title="Check if this is the correct answer"
                                                  name="correct[D]"</label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" name="answers[D]" rows="2"/>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
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
        <button type="submit" class="btn btn-primary btn-block mt-4">Save quiz</button>
    </form>
</div>
<div class="hidden" style="display: none" id="questionTemplates">
    <div class="item" style="background-color: #edeef2; padding:5px">
        <button type="button" id="removeRow" class="btn btn-sm pull-right float-right btn-danger">Remove</button>
        <div class="form-group">
            <label>Question</label>
            <textarea name="question" class="form-control" rows="2" placeholder="Type a question..."></textarea>
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
                <tr>
                    <td>A</td>
                    <td>
                        <label><input type="checkbox" title="Check if this is the correct answer"
                                      name="correct[A]"</label>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="answers[A]" rows="2"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>B</td>
                    <td>
                        <label><input type="checkbox" title="Check if this is the correct answer"
                                      name="correct[B]"</label>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="answers[B]" rows="2"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>C</td>
                    <td>
                        <label><input type="checkbox" title="Check if this is the correct answer"
                                      name="correct[C]"</label>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="answers[C]" rows="2"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>D</td>
                    <td>
                        <label><input type="checkbox" title="Check if this is the correct answer"
                                      name="correct[D]"</label>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="answers[D]" rows="2"/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <label>Answer Explanation</label>
            <textarea name="explanation" class="form-control" rows="3"></textarea>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('.item').remove();
    });

    $(document).on('click', '#addRow', function (e) {
        e.preventDefault();
        var the_html = ""; //unset. Memory is the b word
        the_html = $('#questionTemplates').html();
        $("#theQuestions").append(the_html);
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
            section: $('#section_id').val(),
            subject: "<?php echo $subject->id; ?>",
            class_work: "<?php echo $quiz->id; ?>"
        };
        var e = {
            data: JSON.stringify(data),
            loader: true,
            url: "<?php echo site_url(route_to('admin.academic.assessments.quizes.new_quiz_save', $quiz->id)); ?>"
        }

        //Use fd as you would any form data.
        // Check /home/ben/HTML/Perfex/perfex_crm/modules/hrm/views/new.php:527 for an example

        console.log(quiz);
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