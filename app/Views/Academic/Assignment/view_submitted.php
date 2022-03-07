<?php
$classid = $quiz->class->id;
$submission = (new \App\Models\AssignmentSubmissions())->where('assignment_id',$quiz->id)->where('student_id',$student->id)->first();
$items = json_decode($submission->answer);
//$sectionid = $quiz->section->id;
?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white mb-0">Writing  Assignment: <?php echo $quiz->question;?>: View Student Answer</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                    </nav>
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
            <div class="row">
                <div class="col-md-6">
                Student Name: <b><?php echo $student->profile->name;?></b>
                    <br>
                 ID: <b><?php echo $student->admission_number;?></b>
                </div>
                <div class="col-md-6">
                Given Date: <b><?php echo date('d/m/Y',strtotime($quiz->given))?></b>
                    <br>
                Deadline: <b><?php echo date("d/m/Y",strtotime($quiz->deadline));?></b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div>
                <div>
                    <form class="customSubmit" method="post" data-parsley-validate
                          action="<?php echo site_url(route_to('admin.academic.mark_writing')); ?>">
                    <?php
                    if($items) {
                        $n = 0;
                        foreach ($items as $item) {
                            $n++;?>

                            <div class="row">
                                <div class="col-md-8">
                                    <p><b>Q<?php echo $item->question_number; ?>: </b><?php echo $item->question; ?></p>
                                </div>

                            </div>
                            <input type="hidden" name="student_id" value="<?php echo $student->id;?>">
                            <input type="hidden" name="submission_id" value="<?php echo $submission->id;?>">
                            <input type="hidden" name="assignment_id" value="<?php echo $submission->assignment_id;?>">
                            <input type="hidden" name="mark_per_question" value="<?php echo $submission->mark_per_question;?>">
                            <input type="hidden" name="class_id" value="<?php echo $student->class->id;?>">
                            <input type="hidden" name="section_id" value="<?php echo $student->section->id;?>">
                            <div class="form-group">
                                <textarea name="answer[<?php echo $item->question_number;?>]" id="answer" cols="30" rows="10" class="form-control" readonly><?php echo $item->answer;?></textarea>
                            </div>
                            <label class="container">Correct
                                <input type="radio" checked="checked" name="correct[<?php echo $item->question_number;?>]" value="1">
                                <span class="checkmark"></span>
                            </label>
                            <label class="container">Wrong
                                <input type="radio" name="correct[<?php echo $item->question_number;?>]" value="0">
                                <span class="checkmark"></span>
                            </label>
                            <?php
                        }
                    }
                    ?>
                      <div class="form-group mt-4">
                          <button type="submit" class="btn btn-primary btn-sm">Save Assignment</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<style>
    /* The container */
    .container {
        display: inline;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default radio button */
    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom radio button */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
        border-radius: 50%;
    }

    /* On mouse-over, add a grey background color */
    .container:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the radio button is checked, add a blue background */
    .container input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the indicator (the dot/circle - hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the indicator (dot/circle) when checked */
    .container input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the indicator (dot/circle) */
    .container .checkmark:after {
        top: 9px;
        left: 9px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: white;
    }
</style>

<?php foreach ($items as $key => $value):?>
    <script>
        var qn = "<?php echo $value->question_number;?>";
        CKEDITOR.replace("answer["+qn+"]" );
    </script>

<?php endforeach;?>


