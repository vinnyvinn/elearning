<?php
//$lessonPlanModel = new \App\Models\LessonPlan();
//$plan = $lessonPlanModel->where(['session' => active_session(), 'class' => $section->class->id, 'section' => $section->id, 'subject' => $subject->id ])->get()->getLastRow();
$plan = FALSE;
?>
<div class="card">
    <div class="card-body">
        <form class="ajaxForm d-block" loader="true" method="post" action="<?php echo site_url(route_to('admin.subjects.lesson_plan.create', $section->id, $subject->id)); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Month</label>
                                <select name="month" id="month_id" class="form-control form-control-sm select2"
                                        data-toggle="select2" required>
                                    <option value="">Select Month</option>
                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        ?>
                                        <option <?php echo old('month') == $i ? 'selected' : ''; ?>
                                                value="<?php echo $i; ?>"><?php echo date("F", strtotime('01-' . $i . '-2001')); ?></option>';
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Week</label>
                                <select name="week" id="week_id" class="form-control form-control-sm select2" data-toggle="select2"
                                        required>
                                    <option value="">Select Week</option>
                                    <?php
                                    for ($i = 1; $i <= 4; $i++) {
                                        ?>
                                        <option <?php echo old('week') == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
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
                                <input type="text" name="unit" value="<?php echo old('unit'); ?>" placeholder="Unit" class="form-control" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Duration of Period</label>
                                <input type="text" name="duration" value="<?php echo old('duration'); ?>" placeholder="Duration of Period" class="form-control" required />
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
                            $eDays = json_decode(@$plan->day, TRUE);
                            foreach ($days as $day) {
                                ?>
                                <div class="col-md-4">
                                    <label><input type="checkbox" <?php echo (is_array($eDays) && in_array($day, $eDays)) ? 'checked' : ''; ?> name="day[]" value="<?php echo $day; ?>"/> <?php echo trim($day); ?></label>
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
                        <textarea name="objective" rows="4" class="form-control" required><?php echo old('objective'); ?></textarea>
                    </div>
                </div>
            </div>
            <hr/>
            <h3 class="title">Introduction</h3>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="title">Teacher's Activity</h5>
                    <div class="form-group">
                        <label><input type="checkbox" name="intro[revise]" <?php echo old('intro.revise') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Revise'); ?></label>
                        <label><input type="checkbox" name="intro[introduce]" <?php echo old('intro.introduce') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Introduce'); ?></label>
                        <label><input type="checkbox" name="intro[motivation]" <?php echo old('intro.motivation') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Motivation'); ?></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Students Activity'); ?></h5>
                    <div class="form-group">
                        <label><input type="checkbox" name="intro[recalling]" <?php echo old('intro.recalling') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Recalling'); ?></label>
                        <label><input type="checkbox" name="intro[awareness]" <?php echo old('intro.awareness') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Awareness'); ?></label>
                        <label><input type="checkbox" name="intro[listening]" <?php echo old('intro.listening') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Listening'); ?></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Methodologies'); ?></label>
                        <textarea name="intro[methodologies]" class="form-control"><?php echo old('intro.methodologies'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Teaching Aids'); ?></label>
                        <textarea name="intro[teaching_aids]" class="form-control"><?php echo old('intro.teaching_aids'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Remarks'); ?></label>
                        <textarea name="intro[remarks]" class="form-control"><?php echo old('intro.remarks'); ?></textarea>
                    </div>
                </div>
            </div>
            <hr/>
            <h3 class="title"><?php echo trim('Presentation'); ?></h3>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Teachers Activity'); ?></h5>
                    <div class="form-group">
                        <label><input type="checkbox" name="presentation[group]" <?php echo old('presentation.group') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Group Discussion'); ?></label>
                        <label><input type="checkbox" name="presentation[inductivity]" <?php echo old('presentation.inductivity') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Inductivity'); ?></label>
                        <label><input type="checkbox" name="presentation[explanation]"  <?php echo old('presentation.explanation') == 1 ? 'checked' : ''; ?> value="1" /> Explanation</label>
                        <label><input type="checkbox" name="presentation[showing]"  <?php echo old('presentation.showing') == 1 ? 'checked' : ''; ?> value="1" /> Showing</label>
                        <br/>
                        <label><?php echo trim('Others'); ?></label>
                        <input type="text" class="form-control" name="presentation[other]" value="<?php echo old('presentation.other'); ?>" />
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Students Activity'); ?></h5>
                    <div class="form-group">
                        <label><input type="checkbox" name="presentation[participation]" <?php echo old('presentation.participation') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Participation'); ?></label>
                        <label><input type="checkbox" name="presentation[observation]" <?php echo old('presentation.observation') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Observation'); ?></label>
                        <label><input type="checkbox" name="presentation[listening]" <?php echo old('presentation.listening') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Listening'); ?></label>
                        <label><input type="checkbox" name="presentation[notes]" <?php echo old('presentation.notes') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Taking Short Notes'); ?></label>
                        <label><input type="checkbox" name="presentation[doing]" <?php echo old('presentation.doing') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Doing'); ?></label>
                        <label><input type="checkbox" name="presentation[sexplanation]"  <?php echo old('presentation.sexplanation') == 1 ? 'checked' : ''; ?> value="1" /> Explanation</label>
                        <label><input type="checkbox" name="presentation[sshowing]"  <?php echo old('presentation.sshowing') == 1 ? 'checked' : ''; ?> value="1" /> Showing</label>
                        <br/>
                        <label><?php echo trim('Others'); ?></label>
                        <input type="text" class="form-control" name="presentation[stud_other]" value="<?php echo old('presentation.stud_other'); ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Methodologies'); ?></label>
                        <textarea name="presentation[methodologies]" class="form-control"><?php echo old('presentation.methodologies'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Teaching Aids'); ?></label>
                        <textarea name="presentation[teaching_aids]" class="form-control"><?php echo old('presentation.teaching_aids'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Remarks'); ?></label>
                        <textarea name="presentation[remarks]" class="form-control"><?php echo old('presentation.remarks'); ?></textarea>
                    </div>
                </div>
            </div>
            <hr/>
            <h3 class="title"><?php echo trim('Stabilization'); ?></h3>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Teachers Activity'); ?></h5>
                    <div class="form-group">
                        <label><input type="checkbox" name="stabilization[summary]" <?php echo old('stabilization.summary') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Summary Short Notes'); ?></label>
                        <label><input type="checkbox" name="stabilization[questing]" <?php echo old('stabilization.questing') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Questing-Answering'); ?></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Students Activity'); ?></h5>
                    <div class="form-group">
                        <label><input type="checkbox" name="stabilization[reorganization]" <?php echo old('stabilization.reorganization') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Reorganization'); ?></label>
                        <label><input type="checkbox" name="stabilization[participation]" <?php echo old('stabilization.participation') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Participation'); ?></label>
                        <label><input type="checkbox" name="stabilization[notes]" <?php echo old('stabilization.notes') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Taking Notes'); ?></label>
                        <label><input type="checkbox" name="stabilization[questions]" <?php echo old('stabilization.questions') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Asking Questions'); ?></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Methodologies'); ?></label>
                        <textarea name="stabilization[methodologies]" class="form-control"><?php echo old('stabilization.methodologies'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Teaching Aids'); ?></label>
                        <textarea name="stabilization[teaching_aids]" class="form-control"><?php echo old('stabilization.teaching_aids'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Remarks'); ?></label>
                        <textarea name="stabilization[remarks]" class="form-control"><?php echo old('stabilization.remarks'); ?></textarea>
                    </div>
                </div>
            </div>
            <hr/>
            <h3 class="title"><?php echo trim('Evaluation'); ?></h3>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Teachers Activity'); ?></h5>
                    <div class="form-group">
                        <label><input type="checkbox" name="evaluation[class_work]" <?php echo old('evaluation.class_work') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Class Work'); ?></label>
                        <label><input type="checkbox" name="evaluation[home_work]" <?php echo old('evaluation.home_work') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Home Work'); ?></label>
                        <label><input type="checkbox" name="evaluation[assignment]" <?php echo old('evaluation.assignment') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Assignment'); ?></label>
                        <label><input type="checkbox" name="evaluation[group_work]" <?php echo old('evaluation.group_work') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Group Works'); ?></label>
                        <br/>
                        <label><?php echo trim('Others'); ?></label>
                        <input type="text" class="form-control" name="evaluation[other]" value="<?php echo old('evaluation.other'); ?>" />
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="title"><?php echo trim('Students Activity'); ?></h5>
                    <div class="form-group">
                        <label><input type="checkbox" name="evaluation[doing]" <?php echo old('evaluation.doing') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Doing'); ?></label>
                        <label><input type="checkbox" name="evaluation[participation]" <?php echo old('evaluation.participation') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Participation'); ?></label>
                        <label><input type="checkbox" name="evaluation[responding]" <?php echo old('evaluation.responding') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Responding'); ?></label>
                        <label><input type="checkbox" name="evaluation[sharing_ideas]" <?php echo old('evaluation.sharing_ideas') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Sharing Ideas'); ?></label>
                        <label><input type="checkbox" name="evaluation[notes]" <?php echo old('evaluation.notes') == 1 ? 'checked' : ''; ?> value="1"/> <?php echo trim('Taking Short Notes'); ?></label>
                        <br/>
                        <label><?php echo trim('Others'); ?></label>
                        <input type="text" class="form-control" name="evaluation[stud_other]" value="<?php echo old('evaluation.stud_other'); ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Methodologies'); ?></label>
                        <textarea name="evaluation[methodologies]" class="form-control"><?php echo old('evaluation.methodologies'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Teaching Aids'); ?></label>
                        <textarea name="evaluation[teaching_aids]" class="form-control"><?php echo old('evaluation.teaching_aids'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php echo trim('Remarks'); ?></label>
                        <textarea name="evaluation[remarks]" class="form-control"><?php echo old('evaluation.remarks'); ?></textarea>
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><?php echo trim('Save Lesson Plan'); ?></button>
            </div>
        </form>
    </div>
</div>