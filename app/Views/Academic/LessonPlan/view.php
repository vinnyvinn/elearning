<div id="printable-data">
    <?php
    $lessonPlanModel = new \App\Models\LessonPlan();
    $plan = $lessonPlanModel->where(['session' => active_session(), 'class' => $section->class->id, 'section' => $section->id, 'subject' => $subject->id, 'month' => $month, 'week'=> $week ])->get()->getLastRow();
    ?>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card bg-secondary text-center">
                <h3>LESSON PLAN</h3>
                <h4>Class: <?php echo $section->class->name; ?></h4>
                <h4>Section: <?php echo $section->name; ?></h4>
                <h4>Subject: <?php echo $subject->name; ?></h4>
                <h4>Teacher: <?php echo $subject->getTeacher($section->id) ? $subject->getTeacher($section->id)->profile->name : '-'; ?></h4>
            </div>
        </div>
    </div>
    <hr/>
    <?php
    if(!$plan) {
        ?>
        <div class="alert alert-warning">
            Lesson plan has not been created for this subject<br/>
            <a class="btn btn-primary text-white" href="<?php echo site_url(route_to('admin.subjects.lesson_plan.create', $section->id, $subject->id)); ?>">Create Lesson Plan</a>
        </div>
        <?php
    } else {
        ?>
        <div>
<!--            <span class="btn btn-primary btn-sm pull-right float-right" onclick="printNode('#printable-data', 'test-pdf');"><i class="fa fa-download"></i> Download</span>-->
                    <a class="btn btn-primary btn-sm pull-right float-right" target="_blank" href="<?php echo site_url(route_to('admin.subjects.lesson_plan.download', $section->id, $subject->id, $plan->week, $plan->month)); ?>"><i class="fa fa-download"></i> Download</a>
            <a class="btn btn-warning btn-sm pull-right float-right" href="<?php echo site_url(route_to('admin.subjects.lesson_plan.update', $section->id, $subject->id, $plan->week, $plan->month)); ?>">Edit Lesson Plan</a>
            <br/><br/>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Month</label>
                                <select name="month" id="month_id" disabled class="disabled form-control form-control-sm select2"
                                        data-toggle="select2" required>
                                    <option value="">Select Month</option>
                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        ?>
                                        <option <?php echo old('month', $plan->month) == $i ? 'selected' : ''; ?>
                                                value="<?php echo $i; ?>"><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Week</label>
                                <select name="week" id="week_id" disabled class="form-control disabled form-control-sm select2" data-toggle="select2"
                                        required>
                                    <option value="">Select Week</option>
                                    <?php
                                    for ($i = 1; $i <= 4; $i++) {
                                        ?>
                                        <option <?php echo old('week', $plan->week) == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 1em">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unit</label>
                                <input disabled="disabled" type="text" name="unit"
                                       value="<?php echo old('unit', $plan->unit); ?>" placeholder="Unit"
                                       class="form-control form-control-muted disabled" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Duration of Period</label>
                                <input disabled="disabled" type="text" name="duration"
                                       value="<?php echo old('duration', $plan->duration); ?>"
                                       placeholder="Duration of Period" class="form-control form-control-muted disabled"
                                       required/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Select Day</label><br/>
                        <?php
                        //$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        $days = json_decode(get_option('school_days', json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])), true);
                        ?>
                        <div class="row">
                            <?php
                            $eDays = json_decode($plan->day, TRUE);
                            foreach ($days as $day) {
                                ?>
                                <div class="col-md-4">
                                    <label><input disabled="disabled"
                                                  type="checkbox" <?php echo (is_array($eDays) && in_array($day, $eDays)) ? 'checked' : ''; ?>
                                                  name="day[]" value="<?php echo $day; ?>"/> <?php echo trim($day); ?>
                                    </label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>At the end of this lesson, the students will be able to:-</label>
                        <textarea disabled="disabled" name="objective" rows="4"
                                  class="form-control form-control-muted disabled"
                                  required><?php echo old('objective', @$plan->objectives); ?></textarea disabled="disabled">
                    </div>
                </div>
            </div>
            <hr/>
            <h3 class="title">Introduction</h3>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="title">Teacher's Activity</h5>
                    <div class="form-group">
                        <?php
                        $intro = json_decode($plan->intro);
                        ?>
                        <label><input disabled="disabled" type="checkbox"
                                      name="intro[revise]" <?php echo old('intro.revise', @$intro->revise) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Revise'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="intro[introduce]" <?php echo old('intro.introduce', @$intro->introduce) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Introduce'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="intro[motivation]" <?php echo old('intro.motivation', @$intro->motivation) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Motivation'); ?></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Students Activity'); ?></h5>
                    <div class="form-group">
                        <label><input disabled="disabled" type="checkbox"
                                      name="intro[recalling]" <?php echo old('intro.recalling', @$intro->recalling) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Recalling'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="intro[awareness]" <?php echo old('intro.awareness', @$intro->awareness) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Awareness'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="intro[listening]" <?php echo old('intro.listening', @$intro->listening) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Listening'); ?></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Methodologies'); ?></label>
                        <textarea disabled="disabled" name="intro[methodologies]"
                                  class="form-control form-control-muted disabled"><?php echo old('intro.methodologies', @$intro->methodologies); ?></textarea disabled="disabled">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Teaching Aids'); ?></label>
                        <textarea disabled="disabled" name="intro[teaching_aids]"
                                  class="form-control form-control-muted disabled"><?php echo old('intro.teaching_aids', @$intro->teaching_aids); ?></textarea disabled="disabled">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Remarks'); ?></label>
                        <textarea disabled="disabled" name="intro[remarks]"
                                  class="form-control form-control-muted disabled"><?php echo old('intro.remarks', $intro->remarks); ?></textarea disabled="disabled">
                    </div>
                </div>
            </div>
            <hr/>
            <h3 class="title"><?php echo trim('Presentation'); ?></h3>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Teachers Activity'); ?></h5>
                    <div class="form-group">
                        <?php
                        $presentation = json_decode($plan->presentation);
                        ?>
                        <label><input disabled="disabled" type="checkbox"
                                      name="presentation[group]" <?php echo old('presentation.group', @$presentation->group) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Group Discussion'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="presentation[inductivity]" <?php echo old('presentation.inductivity', @$presentation->inductivity) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Inductivity'); ?></label>
                        <label><input disabled="disabled" type="checkbox" name="presentation[explanation]"  <?php echo old('presentation.explanation', @$presentation->explanation) == 1 ? 'checked' : ''; ?> value="1" /> Explanation</label>
                        <label><input disabled="disabled" type="checkbox" name="presentation[showing]"  <?php echo old('presentation.showing', @$presentation->showing) == 1 ? 'checked' : ''; ?> value="1" /> Showing</label>
                        <br/>
                        <label><?php echo trim('Others'); ?></label>
                        <input disabled="disabled" type="text" class="form-control form-control-muted disabled"
                               name="presentation[other]"
                               value="<?php echo old('presentation.other', @$presentation->other); ?>"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Students Activity'); ?></h5>
                    <div class="form-group">
                        <label><input disabled="disabled" type="checkbox"
                                      name="presentation[participation]" <?php echo old('presentation.participation', @$presentation->participation) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Participation'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="presentation[observation]" <?php echo old('presentation.observation', @$presentation->observation) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Observation'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="presentation[listening]" <?php echo old('presentation.listening', @$presentation->listening) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Listening'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="presentation[notes]" <?php echo old('presentation.notes', @$presentation->notes) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Taking Short Notes'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="presentation[doing]" <?php echo old('presentation.doing', @$presentation->doing) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Doing'); ?></label>
                        <label><input disabled="disabled" type="checkbox" name="presentation[sexplanation]"  <?php echo old('presentation.sexplanation', @$presentation->sexplanation) == 1 ? 'checked' : ''; ?> value="1" /> Explanation</label>
                        <label><input disabled="disabled" type="checkbox" name="presentation[sshowing]"  <?php echo old('presentation.sshowing', @$presentation->sshowing) == 1 ? 'checked' : ''; ?> value="1" /> Showing</label>
                        <label><?php echo trim('Others'); ?></label>
                        <input disabled="disabled" type="text" class="form-control form-control-muted disabled"
                               name="presentation[stud_other]"
                               value="<?php echo old('presentation.stud_other', @$presentation->stud_other); ?>"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Methodologies'); ?></label>
                        <textarea disabled="disabled" name="presentation[methodologies]"
                                  class="form-control form-control-muted disabled"><?php echo old('presentation.methodologies', @$presentation->methodologies); ?></textarea disabled="disabled">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Teaching Aids'); ?></label>
                        <textarea disabled="disabled" name="presentation[teaching_aids]"
                                  class="form-control form-control-muted disabled"><?php echo old('presentation.teaching_aids', @$presentation->teaching_aids); ?></textarea disabled="disabled">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Remarks'); ?></label>
                        <textarea disabled="disabled" name="presentation[remarks]"
                                  class="form-control form-control-muted disabled"><?php echo old('presentation.remarks', @$presentation->remarks); ?></textarea disabled="disabled">
                    </div>
                </div>
            </div>
            <hr/>
            <h3 class="title"><?php echo trim('Stabilization'); ?></h3>
            <?php
            $stabilization = json_decode($plan->stabilization);
            ?>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Teachers Activity'); ?></h5>
                    <div class="form-group">
                        <label><input disabled="disabled" type="checkbox"
                                      name="stabilization[summary]" <?php echo old('stabilization.summary', @$stabilization->summary) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Summary Short Notes'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="stabilization[questing]" <?php echo old('stabilization.questing', @$stabilization->questing) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Questing-Answering'); ?></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Students Activity'); ?></h5>
                    <div class="form-group">
                        <label><input disabled="disabled" type="checkbox"
                                      name="stabilization[reorganization]" <?php echo old('stabilization.reorganization', @$stabilization->reorganization) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Reorganization'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="stabilization[participation]" <?php echo old('stabilization.participation', @$stabilization->participation) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Participation'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="stabilization[notes]" <?php echo old('stabilization.notes', @$stabilization->notes) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Taking Notes'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="stabilization[questions]" <?php echo old('stabilization.questions', @$stabilization->questions) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Asking Questions'); ?></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Methodologies'); ?></label>
                        <textarea disabled="disabled" name="stabilization[methodologies]"
                                  class="form-control form-control-muted disabled"><?php echo old('stabilization.methodologies', @$stabilization->methodologies); ?></textarea disabled="disabled">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Teaching Aids'); ?></label>
                        <textarea disabled="disabled" name="stabilization[teaching_aids]"
                                  class="form-control form-control-muted disabled"><?php echo old('stabilization.teaching_aids', @$stabilization->teaching_aids); ?></textarea disabled="disabled">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Remarks'); ?></label>
                        <textarea disabled="disabled" name="stabilization[remarks]"
                                  class="form-control form-control-muted disabled"><?php echo old('stabilization.remarks', @$stabilization->remarks); ?></textarea disabled="disabled">
                    </div>
                </div>
            </div>
            <hr/>
            <h3 class="title"><?php echo trim('Evaluation'); ?></h3>
            <?php
            $evaluation = json_decode($plan->evaluation);
            ?>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Teachers Activity'); ?></h5>
                    <div class="form-group">
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[class_work]" <?php echo old('evaluation.class_work', @$evaluation->class_work) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Class Work'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[home_work]" <?php echo old('evaluation.home_work', @$evaluation->home_work) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Home Work'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[assignment]" <?php echo old('evaluation.assignment', @$evaluation->assignment) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Assignment'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[group_work]" <?php echo old('evaluation.group_work', @$evaluation->group_work) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Group Works'); ?></label>
                        <label><?php echo trim('Others'); ?></label>
                        <input disabled="disabled" type="text" class="form-control form-control-muted disabled"
                               name="evaluation[other]"
                               value="<?php echo old('evaluation.other', @$evaluation->other); ?>"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Students Activity'); ?></h5>
                    <div class="form-group">
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[doing]" <?php echo old('evaluation.doing', @$evaluation->doing) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Doing'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[participation]" <?php echo old('evaluation.participation', @$evaluation->participation) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Participation'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[responding]" <?php echo old('evaluation.responding', @$evaluation->responding) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Responding'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[sharing_ideas]" <?php echo old('evaluation.sharing_ideas', @$evaluation->sharing_ideas) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Sharing Ideas'); ?></label>
                        <label><input disabled="disabled" type="checkbox"
                                      name="evaluation[notes]" <?php echo old('evaluation.notes', @$evaluation->notes) == 1 ? 'checked' : ''; ?>
                                      value="1"/> <?php echo trim('Taking Short Notes'); ?></label>
                        <br/>
                        <label><?php echo trim('Others'); ?></label>
                        <input disabled="disabled" type="text" class="form-control form-control-muted disabled"
                               name="evaluation[stud_other]"
                               value="<?php echo old('evaluation.stud_other', @$evaluation->stud_other); ?>"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Methodologies'); ?></label>
                        <textarea disabled="disabled" name="evaluation[methodologies]"
                                  class="form-control form-control-muted disabled"><?php echo old('evaluation.methodologies', @$evaluation->methodologies); ?></textarea disabled="disabled">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Teaching Aids'); ?></label>
                        <textarea disabled="disabled" name="evaluation[teaching_aids]"
                                  class="form-control form-control-muted disabled"><?php echo old('evaluation.teaching_aids', @$evaluation->teaching_aids); ?></textarea disabled="disabled">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Remarks'); ?></label>
                        <textarea disabled="disabled" name="evaluation[remarks]"
                                  class="form-control form-control-muted disabled"><?php echo old('evaluation.remarks', @$evaluation->remarks); ?></textarea disabled="disabled">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>