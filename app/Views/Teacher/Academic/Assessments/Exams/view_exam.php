<?php
//d($exam);
?>
<div>
    <a target="_blank" href="<?php echo site_url(route_to('teacher.academic.assessments.exams.print_exam', $exam->id)) ?>" class="btn btn-info"><i class="fa fa-print"></i> Print</a>
</div>
<hr/>
<div class="row">
    <div class="col-md-6">
        <span>Marks out of <b><?php echo $exam->out_of; ?></b></span>
    </div>
    <div class="col-md-6">
        <span>Duration: <b><?php echo @$exam->duration ? $exam->duration.' minutes' : '-UNDEFINED-'; ?></b></span>
    </div>
</div>
<hr/>
<div>
    <?php
    if($exam->items) {
        $n = 0;
        foreach ($exam->items as $item) {
            $n++;
            ?>
            <div class="row">
                <div class="col-md-8">
                    <p><b>Q<?php echo $n; ?>: </b><?php echo $item->question; ?></p>
                    <div class="ml-5">
                        <?php
                        foreach ($item->answers as $key=>$answer) {
                            if($answer && $answer != '') {
                                ?>
                                <label>
                                    <input type="radio" <?php echo $item->corrects->$key ? 'checked' : ''; ?> disabled /> <?php echo $answer; ?>
                                </label><br/>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-4">

                        <img src="<?php echo @$item->image; ?>" class="img-responsive" style="max-height: 200px; width: auto; max-width: 300px;"/>

                </div>
            </div>
            <div class="alert alert-neutral border-info">
                <?php
                    echo @$item->explanation;
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>