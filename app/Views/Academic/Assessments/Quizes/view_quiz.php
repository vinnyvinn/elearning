<?php
//d($quiz);
?>
<div>
    <a target="_blank" href="<?php echo site_url(route_to('admin.academic.assessments.quizes.print_quiz', $quiz->id)) ?>" class="btn btn-info"><i class="fa fa-print"></i> Print</a>
</div>
<hr/>
<div class="row">
    <div class="col-md-6">
        <span>Marks out of <b><?php echo $quiz->out_of; ?></b></span>
    </div>
    <div class="col-md-6">
        <span>Duration: <b><?php echo @$quiz->duration ? $quiz->duration.' minutes' : '-UNDEFINED-'; ?></b></span>
    </div>
</div>
<hr/>
<div>
    <?php
    if($quiz->items) {
        $n = 0;
        foreach ($quiz->items as $item) {
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
                    <?php
                    if(isset($item->image)) {
                        ?>
                        <img src="<?php echo $item->image; ?>" style="max-height: 200px; width: auto"/>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="alert alert-neutral border-default">
                <?php
                    echo @$item->explanation;
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>