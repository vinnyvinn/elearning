<?php
//$classid = $classwork->class->id;
//$sectionid = $classwork->section->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Create Class Work</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <div>
                <form class="customSubmit" loader="true" method="post" data-parsley-validate=""
                action="<?php echo site_url(route_to('teacher.academic.assessments.class_work.create')); ?>">
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
                                            <option value="<?php echo $semester->id; ?>"><?php echo $semester->name; ?></option>
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
                                        echo '<option value="' . $class->id . '">' . $class->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Subject</label>
                                <select id="subject_id" name="subject" class="form-control form-control-sm" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sess">Given Date</label>
                                <input type="text" class="form-control form-control-sm datepicker" name="given"
                                       value="<?php echo old('given', date('m/d/Y')) ?>" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sess">Deadline</label>
                                <input type="text" class="form-control form-control-sm datepicker" name="deadline"
                                       value="<?php echo old('deadline') ?>" required/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sess">Classwork Name</label>
                        <input type="text" class="form-control form-control-sm" name="name"
                               value="<?php echo old('name') ?>" required/>
                    </div>
                    <div>
                        <div>
                            <?php
                            //Check existing
                            //$existing = (new ClassWorkItems())->where('class_work', $classwork->id)->where('class', $classwork->class->id)->where('subject', $subject->id)->get()->getFirstRow('\App\Entities\ClassWorkItems');
                            $existing = false;
                            ?>
                            <div>
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
                                    if ($existing && $existing->items && count($existing->items) > 0) {
                                        foreach ($existing->items as $item) {
                                            ?>
                                            <div class="item" style="background-color: #edeef2; padding:5px">
                                                <button type="button" id="removeRow" class="btn btn-sm pull-right float-right btn-danger">
                                                    Remove
                                                </button>
                                                <div class="form-group">
                                                    <label>Question</label>
                                                    <textarea name="question" class="form-control" rows="2"
                                                              placeholder="Type a question..."><?php echo @$item->question; ?></textarea>
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
                                                                <label><input
                                                                            type="checkbox" <?php echo @$item->corrects->A ? 'checked' : ''; ?>
                                                                            title="Check if this is the correct answer"
                                                                            name="correct[A]"/>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control form-control-sm" name="answers[A]"
                                                                           value="<?php echo $item->answers->A; ?>" rows="2"/>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>B</td>
                                                            <td>
                                                                <label><input type="checkbox"<?php echo @$item->corrects->B ? 'checked' : ''; ?>
                                                                              title="Check if this is the correct answer"
                                                                              name="correct[B]"/>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control form-control-sm" name="answers[B]"
                                                                           value="<?php echo $item->answers->B; ?>" rows="2"/>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>C</td>
                                                            <td>
                                                                <label><input type="checkbox" <?php echo @$item->corrects->C ? 'checked' : ''; ?>
                                                                              title="Check if this is the correct answer"
                                                                              name="correct[C]"/>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control form-control-sm" name="answers[C]"
                                                                           value="<?php echo $item->answers->C; ?>" rows="2"/>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>D</td>
                                                            <td>
                                                                <label><input type="checkbox" <?php echo @$item->corrects->D ? 'checked' : ''; ?>
                                                                              title="Check if this is the correct answer"
                                                                              name="correct[D]"/>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control form-control-sm" name="answers[D]"
                                                                           value="<?php echo $item->answers->D; ?>" rows="2"/>
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
                                                    <img src="" id="xPrev" class="img" style="max-height: 200px; width: auto" />
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
                                                                          name="correct[A]"/>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control form-control-sm" name="answers[A]"
                                                                       rows="2"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>B</td>
                                                        <td>
                                                            <label><input type="checkbox"
                                                                          title="Check if this is the correct answer"
                                                                          name="correct[B]"/>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control form-control-sm" name="answers[B]"
                                                                       rows="2"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>C</td>
                                                        <td>
                                                            <label><input type="checkbox"
                                                                          title="Check if this is the correct answer"
                                                                          name="correct[C]"/>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control form-control-sm" name="answers[C]"
                                                                       rows="2"/>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>D</td>
                                                        <td>
                                                            <label><input type="checkbox"
                                                                          title="Check if this is the correct answer"
                                                                          name="correct[D]"/>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control form-control-sm" name="answers[D]"
                                                                       rows="2"/>
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
                            </div>
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
                                        <img src="" id="xPrev" class="img" style="max-height: 200px; width: auto" />
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
                                                              name="correct[A]"/>
                                                </label>
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
                                                              name="correct[B]"/>
                                                </label>
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
                                                              name="correct[C]"/>
                                                </label>
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
                                                              name="correct[D]"/>
                                                </label>
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
                    </div>
                    <br/>
                    <hr/>
                    <button type="submit" class="btn btn-primary">Save Classwork</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
    });

    //TODO: Ensure only one checbox is checked
    // $(document).on('change', '#theQuestions .item input[type="checkbox"]', function (e) {
    //     $(this).parents('.item checkbox').removeAttr('checked');
    //     $(this).attr('checked', 'checked');
    // });

    var xRes = null;

    $('.customSubmit').submit(function (e) {
        e.preventDefault();
        var index = 1;
        var classwork = [];
        $('#theQuestions .item').each(function () {
            var row = {};
            $(this).find('input,select,textarea').each(function () {
                if ($(this).is(':checkbox')) {
                    row[$(this).attr('name')] = $(this).is(':checked');
                } else {
                    if($(this).attr('type') == 'file') {

                        // var file = this.files[0];
                        // if(file) {
                        //     var reader = new FileReader();
                        //     reader.onloadend = function
                        //     reader.readAsDataURL(file);
                        //     //xRes = reader.result;
                        //     console.log(xRes);
                        // }
                        row['image'] = $(this).attr('src');
                    } else {
                        row[$(this).attr('name')] = $(this).val();
                    }
                }
            });
            row['index'] = index;
            index++;
            classwork.push(row);
        });
        // var fd = new FormData();
        // fd.append('classwork', JSON.stringify(classwork));
        // fd.append('out_of', $('input[name="out_of"]').val());
        var data = {
            classwork: classwork,
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
            url: "<?php echo site_url(route_to('teacher.academic.assessments.class_work.new_classwork_save')); ?>"
        }

        //Use fd as you would any form data.
        // Check /home/ben/HTML/Perfex/perfex_crm/modules/hrm/views/new.php:527 for an example

        //console.log(classwork);
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